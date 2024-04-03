<?php namespace x\panel;

$prefix = \ENGINE . \D . 'log' . \D . 'error';
if (\is_file($log = $prefix . '-x') || \is_file($log = $prefix . '-y')) {
    $log = \file_get_contents($log);
    if (\preg_match_all('/' . \x(\LOT . \D . '[xy]' . \D) . '[^' . \x(\D) . ']+/', $log, $m)) {
        $stuck = [];
        foreach ($m[0] as $v) {
            if (\is_file($v . \D . '.index.php')) {
                $stuck[] = $v;
            }
        }
        // TODO: Attempt to revert to the previous state if recent update(s) are not compatible with other extension(s)
        // and layout(s) in the current system set up.
        // // \test($stuck);
        // // exit;
    }
}

if (!isset($state->x->user)) {
    \abort(\i('Missing %s extension.', ['<a href="https://github.com/mecha-cms/x.user" rel="nofollow" target="_blank">user</a>']));
}

// Someone just tried to replace you!
if (!empty($user) && !($user instanceof \User)) {
    \abort(\i('%s must be an instance of %s.', ['<code>$user</code>', '<code>User</code>']));
}

// Set proper redirect target for non super user
function on__user__enter($file) {
    \extract(\lot(), \EXTR_SKIP);
    $user = new \User($file);
    $route = \trim($state->x->panel->route ?? $state->x->user->guard->route ?? $state->x->user->route ?? 'user', '/');
    $status = $user->status ?? 0;
    // If current user is not super user
    if (1 !== $status) {
        // And if current user is not an editor
        if (2 !== $status) {
            // Redirect to the user page
            \kick('/' . $route . '/get/user/' . $user->name(true));
        }
        // Else, redirect to the default page
        $kick = \trim($state->x->panel->kick ?? 'get/asset/1', '/');
        // Redirect target without `/` prefix will be resolved relative to the panel base URL
        if (0 !== \strpos($kick, '/') && false === \strpos($kick, '://')) {
            $kick = '/' . $route . '/' . $kick;
        }
        \kick($kick);
    }
}

// Clear file and folder marker(s)
function on__user__exit() {
    unset($_SESSION['_']);
}

\Hook::set('on.user.enter', __NAMESPACE__ . "\\on__user__enter");
\Hook::set('on.user.exit', __NAMESPACE__ . "\\on__user__exit");

$path = \trim($url->path ?? "", '/');
$query = \From::query($url->query ?? "");

$r = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$route = \trim($state->x->panel->route ?? $state->x->user->guard->route ?? $state->x->user->route ?? 'user', '/');
$test = \preg_match('/^' . \x($route) . '\/(fire\/[^\/]+|[gls]et)\/(.+)$/', $path, $m);

// File/folder path is taken from the current path or from the current path without the numeric suffix which is
// commonly used to indicate current pagination offset.
$f = $part = 0;
if ($test) {
    if (!$f = \stream_resolve_include_path(\LOT . \D . $m[2])) {
        if (\preg_match('/^(.*)\/([1-9]\d*)$/', $m[2], $mm)) {
            $f = \stream_resolve_include_path(\LOT . \D . $mm[1]);
            $part = (int) $mm[2];
            $m[2] = $mm[1]; // Path without the numeric suffix
        }
    }
}

\lot('_', $_ = \array_replace_recursive([
    '0' => null,
    '1' => null,
    '2' => [],
    'alert' => [],
    'are' => (array) ($state->are ?? []), // Inherit to the front-end state(s)
    'as' => [],
    'asset' => [],
    'author' => $user->user ?? null,
    'base' => $url . '/' . $route,
    'can' => (array) ($state->can ?? []), // Inherit to the front-end state(s)
    'chunk' => $query['chunk'] ?? 20,
    'content' => null,
    'count' => 0,
    'deep' => $query['deep'] ?? 0,
    'description' => null,
    'file' => $f && \is_file($f) ? $f : null,
    'folder' => $f && \is_dir($f) ? $f : null,
    'has' => (array) ($state->has ?? []), // Inherit to the front-end state(s)
    'hash' => $url['hash'],
    'icon' => [],
    'is' => \array_replace((array) ($state->is ?? []), ['error' => false]), // Inherit to the front-end state(s)
    'kick' => null,
    'lot' => [],
    'not' => (array) ($state->not ?? []), // Inherit to the front-end state(s)
    'of' => [],
    'part' => (int) $part,
    'path' => $test ? $m[2] : null,
    'query' => $query,
    'sort' => $query['sort'] ?? null, // Default is `[1, 'path']`
    'status' => $f ? 200 : 404,
    'task' => \lot('_' . $r)['task'] ?? ($test ? $m[1] : null),
    'title' => null,
    'token' => $user->token ?? null,
    'type' => \lot('_' . $r)['type'] ?? null,
    'with' => [],
    'x' => $query['x'] ?? null
], \lot('_') ?? []));

// Modify default log-in redirection to the panel page if it is not set
if ('GET' === $r && !\array_key_exists('kick', $_GET)) {
    if (!\is_dir(\LOT . \D . 'user') || $path === \trim($state->x->user->guard->route ?? $state->x->user->route ?? 'user', '/')) {
        $kick = \trim($state->x->panel->kick ?? 'get/asset/1', '/');
        // Redirect target without `/` prefix will be resolved relative to the panel base URL
        if (0 !== \strpos($kick, '/') && false === \strpos($kick, '://')) {
            $kick = '/' . $route . '/' . $kick;
        }
        $_GET['kick'] = $kick;
    }
}

// Load the panel interface only if current location path is at least started with `http://127.0.0.1/panel/`
if (!empty($user->exist) && 0 === \strpos($path . '/', $route . '/') && $test) {
    require __DIR__ . \D . 'index' . \D . 'panel.php';
}