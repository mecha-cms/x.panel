<?php

if (!isset($state->x->user)) {
    abort('Missing <a href="https://github.com/mecha-cms/x.user" rel="nofollow" target="_blank">user</a> extension.');
}

// Set proper redirect target for non super user
function _user_enter($file) {
    extract($GLOBALS, EXTR_SKIP);
    $user = new User($file);
    $route = trim($state->x->panel->route ?? $state->x->user->guard->route ?? $state->x->user->route ?? 'user', '/');
    $status = $user->status ?? 0;
    // If current user is not super user
    if (1 !== $status) {
        // And if current user is not an editor
        if (2 !== $status) {
            // Redirect to the user page
            kick('/' . $route . '/get/user/' . $user->name(true));
        }
        // Else, redirect to the default page
        $kick = trim($state->x->panel->kick ?? 'get/asset/1', '/');
        // Redirect target without `/` prefix will be resolved relative to the panel base URL
        if (0 !== strpos($kick, '/') && false === strpos($kick, '://')) {
            $kick = '/' . $route . '/' . $kick;
        }
        kick($kick);
    }
}

// Clear file and folder marker(s)
function _user_exit() {
    unset($_SESSION['_']);
}

Hook::set('on.user.enter', '_user_enter');
Hook::set('on.user.exit', '_user_exit');

$path = trim($url->path ?? "", '/');
$query = From::query($url->query ?? "");

$r = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$route = trim($state->x->panel->route ?? $state->x->user->guard->route ?? $state->x->user->route ?? 'user', '/');
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

// File/folder path is taken from the current path or from the current path without the numeric suffix which is
// commonly used to indicate current pagination offset.
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
    '0' => null,
    '1' => null,
    '2' => [],
    'alert' => [],
    'are' => [],
    'as' => [],
    'asset' => [],
    'author' => $user->user ?? null,
    'base' => $url . '/' . $route,
    'can' => [],
    'chunk' => $query['chunk'] ?? null, // Default is `20`
    'content' => null,
    'count' => 0,
    'deep' => $query['deep'] ?? null, // Default is `0`
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
    'of' => [],
    'part' => (int) $part,
    'path' => $test ? $m[2] : null,
    'query' => $query,
    'sort' => $query['sort'] ?? null, // Default is `[1, 'path']`
    'status' => $f ? 200 : 404,
    'task' => $GLOBALS['_' . $r]['task'] ?? ($test ? $m[1] : null),
    'title' => null,
    'token' => $user->token ?? null,
    'type' => $GLOBALS['_' . $r]['type'] ?? null,
    'with' => [],
    'x' => $query['x'] ?? null
], $GLOBALS['_'] ?? []);

// Modify default log-in redirection to the panel page if it is not set
if ('GET' === $r && !array_key_exists('kick', $_GET)) {
    if (!is_dir(LOT . D . 'user') || $path === trim($state->x->user->guard->route ?? $state->x->user->route ?? 'user', '/')) {
        $kick = trim($state->x->panel->kick ?? 'get/asset/1', '/');
        // Redirect target without `/` prefix will be resolved relative to the panel base URL
        if (0 !== strpos($kick, '/') && false === strpos($kick, '://')) {
            $kick = '/' . $route . '/' . $kick;
        }
        $_GET['kick'] = $kick;
    }
}

// Load the panel interface only if current location path is at least started with `http://127.0.0.1/panel/`
if (!empty($user) && 0 === strpos($path . '/', $route . '/') && $test) {
    require __DIR__ . D . 'index' . D . 'panel.php';
}