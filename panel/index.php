<?php

$state = state('panel');

$GLOBALS['_'] = $_ = array_replace_recursive([
    'alert' => [],
    'chop' => [],
    'chunk' => $state['chunk'] ?? 20,
    'content' => $_GET['content'] ?? 'file', // `blob`, `file` or `folder`
    'f' => null,
    'i' => $i = $url->i,
    'kick' => null,
    'lot' => [],
    'path' => null,
    'peek' => $state['peek'] ?? 2,
    'state' => $state,
    'task' => null,
    'token' => content(USER . DS . Cookie::get('user.key') . DS . 'token.data'),
    '//' => $pp = '/' . $state['//']
], $GLOBALS['_'] ?? []);

$p = trim($url->path, '/');
if (strpos('/' . $p, $pp . '/::') === 0) {
    require __DIR__ . DS . 'panel.php';
}

require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'user.php';