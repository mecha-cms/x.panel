<?php

if (!is_dir(LOT . D . 'user') || !isset($state->x->user)) {
    return;
}

$path = trim($url->path ?? "", '/');
$route = trim($state->x->panel->guard->route ?? $state->x->user->guard->route ?? $state->x->user->route ?? 'user', '/');

$req = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$test = preg_match('/^' . x($route) . '\/(fire\/[^\/]+|[gls]et)\/(.+)$/', $path, $m);

// Create `$user` variable just in case `user` extension is too late to be loaded due to the default extension order.
// Since `panel` is less than `user` when sorted alphabetically, then this `panel` extension will most likely be loaded
// before `user` extension. Here we use the user’s cookie data to reconstruct the variable.
if (empty($user) && ($key = cookie('user.key')) && ($token = cookie('user.token'))) {
    if (is_file($file = LOT . D . 'user' . D . $key . '.page')) {
        if ($token === content(LOT . D . 'user' . D . $key . D . 'token.data')) {
            $GLOBALS['user'] = $user = new User($file);
        }
    }
}

// Someone just tried to replace you!
if (!empty($user) && !($user instanceof User)) {
    abort('<code>$user</code> must be an instance of <code>User</code>.');
}

// File/folder path takes from the current path or from the current path without the
// numeric suffix which is commonly used to indicate current pagination offset.
$f = $part = 0;
if ($test) {
    if (!$f = stream_resolve_include_path(LOT . D . $m[2])) {
        if (preg_match('/^(.*)\/([1-9]\d*)$/', $m[2], $mm)) {
            $f = stream_resolve_include_path(LOT . D . $mm[1]);
            $part = (int) $mm[2];
            $m[2] = $mm[1]; // Path without the numeric suffix
        }
    }
}

$GLOBALS['_'] = $_ = array_replace_recursive([
    'alert' => [],
    'asset' => [],
    'author' => $user->user ?? null,
    'base' => $url . '/' . $route,
    'can' => ['fetch' => !empty($state->x->panel->fetch)],
    'chunk' => null, // Default is `20`
    'content' => null,
    'count' => 0,
    'description' => null,
    'file' => $f && is_file($f) ? $f : null,
    'folder' => $f && is_dir($f) ? $f : null,
    'has' => [],
    'hash' => $url['hash'],
    'icon' => [],
    'is' => [],
    'kick' => null,
    'lot' => [],
    'not' => [],
    'part' => (int) $part,
    'path' => $test ? $m[2] : null,
    'query' => $_GET ?? [],
    'sort' => null, // Default is `[1, 'path']`
    'status' => $f ? 200 : 404,
    'task' => $GLOBALS['_' . $req]['task'] ?? ($test ? $m[1] : null),
    'title' => null,
    'token' => $user->token ?? null,
    'type' => $GLOBALS['_' . $req]['type'] ?? null
], $GLOBALS['_'] ?? []);

// Modify default log-in redirection to the panel page if it is not set
if ('GET' === $req && !array_key_exists('kick', $_GET)) {
    if ($path === trim($state->x->user->guard->route ?? $state->x->user->route ?? 'user', '/')) {
        $_GET['kick'] = '/' . $route . '/get/' . trim($state->x->panel->route ?? 'asset', '/');
    }
}

// Load the panel interface only if the location value is at least started with `http://127.0.0.1/panel/`
if (!empty($user) && 0 === strpos($path . '/', $route . '/') && $test) {
    require __DIR__ . D . 'index' . D . 'panel.php';
}