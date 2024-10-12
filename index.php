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
        // TODO: Attempt to revert to the previous state if recent update is not compatible with other extension(s) and
        // layout(s) in the current system set up.
        // // \test($stuck);
        // // exit;
    }
}

if (!isset($state->x->user)) {
    if (\function_exists("\\abort")) {
        \abort(\i('Missing %s extension.', '<a href="https://github.com/mecha-cms/x.user" rel="nofollow" target="_blank">user</a>'));
    }
    exit(\i('Missing %s extension.', 'user'));
}

// Someone just tried to replace you!
if (!empty($user) && !($user instanceof \User)) {
    if (\function_exists("\\abort")) {
        \abort(\i('%s must be an instance of %s.', ['<code>$user</code>', '<code>User</code>']));
    }
    exit(\i('%s must be an instance of %s.', ['`$user`', '`User`']));
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

if (0 === \strpos($path, $v = $route . '/fire/')) {
    $exist = \substr(\strstr($v = \substr($path, \strlen($v)), '/') ?: "", 1);
    $task = 'fire/' . \strstr($v, '/', true);
} else if (0 === \strpos($path, $v = $route . '/get/')) {
    $exist = \substr($path, \strlen($v));
    $task = 'get';
} else if (0 === \strpos($path, $v = $route . '/let/')) {
    $exist = \substr($path, \strlen($v));
    $task = 'let';
} else if (0 === \strpos($path, $v = $route . '/set/')) {
    $exist = \substr($path, \strlen($v));
    $task = 'set';
} else {
    $exist = $task = null;
}

$f = $part = 0;
if ($exist) {
    $n = \basename($exist);
    $n = '0' !== ($n[0] ?? '0') && \strspn($n, '0123456789') === \strlen($n) ? $n : false;
    if (!$f = \stream_resolve_include_path(\LOT . \D . $exist)) {
        if ($n) {
            $f = \stream_resolve_include_path(\LOT . \D . ($exist = \dirname($exist)));
            $part = (int) $n;
        }
    } else if (!\array_key_exists('type', $_GET)) {
        if ($n) {
            $f = \dirname($f);
            $part = (int) \basename($exist);
            $exist = \dirname($exist);
        }
    }
}

foreach ([
    '%s goes here...' => "%s goes here\u{2026}",
    'Content goes here...' => "Content goes here\u{2026}",
    'Description goes here...' => "Description goes here\u{2026}"
] as $k => $v) {
    \lot('I')[$k] = \lot('I')[$k] ?? $v;
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
    'path' => $exist,
    'query' => $query,
    'sort' => $query['sort'] ?? null, // Default is `[1, 'path']`
    'status' => $f ? 200 : 404,
    'task' => \lot('_' . $r)['task'] ?? $task,
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
if ($exist && !empty($user->exist) && 0 === \strpos($path . '/', $route . '/')) {
    require __DIR__ . \D . 'index' . \D . 'panel.php';
}