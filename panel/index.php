<?php

if (!is_dir(LOT . DS . 'user') || null === State::get('x.user')) {
    return;
}

$state = State::get('x.panel', true);

$GLOBALS['_'] = $_ = array_replace_recursive([
    'alert' => [],
    'chops' => [],
    'chunk' => $state['chunk'] ?? 20,
    'layout' => $_GET['layout'] ?? 'file',
    'sort' => $state['sort'] ?? 1,
    'f' => null,
    'i' => $i = $url['i'],
    'kick' => null,
    'lot' => [],
    'path' => null,
    'peek' => $state['peek'] ?? 2,
    'state' => $state,
    'task' => null,
    'token' => content(LOT . DS . 'user' . DS . Cookie::get('user.key') . DS . 'token.data'),
    'trash' => !empty($state['guard']['trash']),
    'user' => $u = State::get('x.user', true),
    '/' => $pp = ($u['guard']['path'] ?? $state['guard']['path'])
], $GLOBALS['_'] ?? []);

$p = $url['path'];

if (null !== $i && stream_resolve_include_path(LOT . DS . (explode('::/', $p, 2)[1] ?? P) . DS . $i)) {
    $url->path .= '/' . $i;
    $p .= '/' . $i;
    $GLOBALS['_']['i'] = $_['i'] = $url->i = $i = null;
}

if (0 === strpos('/' . $p, $pp . '/::')) {
    Asset::let(); // Remove all asset(s)
    Route::let(); // Remove all route(s)
    require __DIR__ . DS . 'engine' . DS . 'fire.php';
}

require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'user.php';
