<?php

if (!is_dir(LOT . D . 'user') || !isset($state->x->user)) {
    return;
}

$req = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Create `$user` variable just in case `user` extension is too late to be loaded due to
// the default extension order. Because `panel` is less than `user` when sorted alphabetically.
// At least we have the cookie that is always available in the global scope.
if (empty($user) && $key = cookie('user.key')) {
    if (is_file($file = LOT . D . 'user' . D . $key . '.page')) {
        $GLOBALS['user'] = $user = new User($file);
    }
}

// Someone just trying to replace you!
if (!empty($user) && !($user instanceof User)) {
    abort('<code>$user</code> must be an instance of <code>User</code>.');
}

$GLOBALS['_'] = $_ = array_replace_recursive([
    'alert' => [],
    'asset' => [],
    'author' => $user->user ?? null,
    'base' => $url . "",
    'can' => ['fetch' => !empty($state->x->panel->fetch)],
    'content' => null,
    'description' => null,
    'file' => null,
    'folder' => null,
    'has' => [],
    'hash' => $url['hash'],
    'icon' => [],
    'is' => [],
    'kick' => null,
    'lot' => [],
    'not' => [],
    'path' => null,
    'query' => $_GET ?? [],
    'status' => 403,
    'task' => $GLOBALS['_' . $req]['task'] ?? null,
    'title' => null,
    'token' => $user->token ?? null,
    'type' => $GLOBALS['_' . $req]['type'] ?? null
], $GLOBALS['_'] ?? []);

$path = trim($url->path ?? "", '/');
$route = trim($state->x->user->guard->route ?? $state->x->user->route ?? 'user', '/');

// Modify default user redirection to the default panel page if not set
if ('GET' === $req && !array_key_exists('kick', $_GET)) {
    if ($path === $route) {
        $_GET['kick'] = '/' . $route . '/get/' . trim($state->x->panel->route ?? 'asset', '/');
    }
}

if (0 === strpos($path . '/', $route . '/') && preg_match('/^' . x($route) . '\/(fire\/[^\/]+|get|let|set)\/(.+)$/', $path, $m)) {
    $_['base'] .= '/' . $route . '/' . $m[1];
    $_['path'] = $m[2];
    $f = stream_resolve_include_path(LOT . D . $m[2]) ?: stream_resolve_include_path(LOT . D . preg_replace('/\/[1-9]\d*$/', "", $m[2]));
    $f && ($_[is_dir($f) ? 'folder' : 'file'] = $f);
    Hook::let('route'); // Remove all front-end route(s)
    Hook::set('route', function($path, $query, $hash) use($_) {
        Is::user() && Hook::fire('route.panel', [$_, $path, $query, $hash]);
    }, 0);
    Hook::set('route.panel', function($_) {
        $id = strtok($_['path'], '/');
        $GLOBALS['t'][] = i('Panel');
        $GLOBALS['t'][] = i($_['title'] ?? ('x' === $id ? 'Extension' : To::title($id)));
        $GLOBALS['content'] = 'Yo!';
        $GLOBALS['description'] = $_['description'] ?? null;
        $GLOBALS['status'] = $_['status'] ?? 403;
        $GLOBALS['title'] = (string) $GLOBALS['t']->reverse();
        Asset::let(); // Remove all front-end asset(s)
        Hook::fire('layout', ['panel']);
    }, 100);
    if (!is_file(LOT . D . 'layout' . D . 'panel.php')) {
        Layout::set('panel', __DIR__ . D . 'lot' . D . 'layout' . D . 'panel.php');
    }
    $GLOBALS['_'] = $_;
}