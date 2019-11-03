<?php

if (!defined('USER') || null === State::get('x.user')) {
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
    'user' => $u = State::get('x.user', true),
    'task' => null,
    'token' => content(USER . DS . Cookie::get('user.key') . DS . 'token.data'),
    '/' => $pp = ($u['guard']['path'] ?? $state['guard']['path']) . '/'
], $GLOBALS['_'] ?? []);

$p = trim($url->path, '/');
if (0 === strpos('/' . $p, $pp . '::')) {
    Asset::let(); // Remove all asset(s)
    Route::let(); // Remove all route(s)
    require __DIR__ . DS . 'engine' . DS . 'fire.php';
}

require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'user.php';