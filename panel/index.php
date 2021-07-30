<?php

if (!is_dir(LOT . DS . 'user') || null === State::get('x.user')) {
    return;
}

// Create `$user` variable just in case `user` extension is too late
// to be loaded due to the default extension order.
// Because `panel` is less than `user` when sorted alphabetically.
// At least we have the cookie that is always available in the global scope.
if (empty($GLOBALS['user']) && $key = Cookie::get('user.key')) {
    $GLOBALS['user'] = $user = new User(LOT . DS . 'user' . DS . $key . '.page');
}

// Someone just trying to replace you!
if (isset($user) && !$user instanceof User) {
    Guard::abort('<code>$user</code> must be an instance of <code>User</code>.');
}

$state = State::get('x.panel', true);

$GLOBALS['_'] = $_ = array_replace_recursive([
    'alert' => [],
    'asset' => [],
    'can' => ['fetch' => !empty($state['fetch'])],
    'chop' => [],
    'chunk' => $state['chunk'] ?? 20,
    'content' => null,
    'f' => null,
    'form' => [
        'lot' => e($GLOBALS['_' . ($k = $_SERVER['REQUEST_METHOD'] ?? 'GET')] ?? []),
        'type' => strtolower($k)
    ],
    'has' => [],
    'i' => $i = $url['i'],
    'icon' => [],
    'id' => null,
    'is' => [],
    'kick' => null,
    'lot' => [],
    'not' => [],
    'path' => null,
    'skin' => [],
    'sort' => $state['sort'] ?? 1,
    'state' => $state,
    'task' => null,
    'title' => null,
    'token' => $user['token'] ?? null,
    'trash' => !empty($state['guard']['trash']),
    'type' => $GLOBALS['_' . $k]['type'] ?? null,
    'user' => $u = State::get('x.user', true),
    '/' => $url . ($pp = '/' . trim($u['guard']['path'] ?? $state['guard']['path'], '/'))
], $GLOBALS['_'] ?? []);

$p = $url['path'];

if (null !== $i && stream_resolve_include_path(LOT . DS . (explode('::/', $p, 2)[1] ?? P) . DS . $i)) {
    $url->path .= '/' . $i;
    $p .= '/' . $i;
    $GLOBALS['_']['i'] = $_['i'] = $url->i = $i = null;
}

if (0 === strpos('/' . $p, $pp . '/::')) {
    require __DIR__ . DS . 'engine' . DS . 'fire.php';
}

require __DIR__ . DS . 'index' . DS . 'hook.php';
require __DIR__ . DS . 'index' . DS . 'user.php';
