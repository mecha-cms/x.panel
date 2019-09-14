<?php

// TODO
File::$config['x']['htaccess'] = 1;
File::$config['x']['php'] = 1;

$state = state('panel');

$GLOBALS['_'] = $_ = [
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
];

$p = trim($url->path, '/');
if (strpos('/' . $p, $pp . '/::') === 0) {
    $chop = explode('/', $p);
    // `http://127.0.0.1/panel`
    // `http://127.0.0.1/panel/::g::`
    if (count($chop) < 3) {
        Guard::kick("");
    }
    array_shift($chop); // Remove the first path
    $task = $chop[0] && strpos($chop[0], '::') === 0 && substr($chop[0], -2) === '::' ? substr(array_shift($chop), 2, -2) : null;
    if ($i === null && $task === 'g' && count($chop) === 1) {
        // Make sure to have page offset on `items` view
        Guard::kick($url->clean . '/1' . $url->query . $url->hash);
    }
    $_['chop'] = $chop;
    $_['path'] = $task ? '/' . implode('/', $chop) : null;
    $_['task'] = $task;
    $GLOBALS['_'] = $_;
    require __DIR__ . DS . 'engine' . DS . 'f.php';
    require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'asset.php';
    require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'hook.php';
    require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'language.php';
    require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'route.php';
}

require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'user.php';