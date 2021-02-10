<?php

$chop = explode('/', $p);

// `http://127.0.0.1/panel`
// `http://127.0.0.1/panel/::g::`
if (count($chop) < 3) {
    Guard::kick("");
}

// Remove the first path
array_shift($chop);

$task = $chop[0] && 0 === strpos($chop[0], '::') && '::' === substr($chop[0], -2) ? substr(array_shift($chop), 2, -2) : null;

$_['chop'] = $chop;
$_['path'] = $task ? implode('/', $chop) : null;
$_['task'] = $task;

// Normalize path value and remove any `../` to prevent directory traversal attack
$f = LOT . DS . strtr($_['path'], [
    '/' => DS,
    '../' => ""
]);

$_['f'] = stream_resolve_include_path($f) ?: null;

// Make sure to have page offset on `items` view
if (null === $i && 'g' === $task && 1 === count($chop) && is_dir($f)) {
    Guard::kick($url->clean . '/1' . $url->query . $url->hash);
}

$GLOBALS['_'] = $_; // Update data

foreach ([
    '%s goes here...' => "%s goes here\u{2026}",
    'Content goes here...' => "Content goes here\u{2026}",
    'Description goes here...' => "Description goes here\u{2026}",
    'You don\'t have permission to change the %s value.' => "You don\u{2019}t have permission to change the %s value."
] as $k => $v) {
    if (isset($GLOBALS['I'][$k])) {
        continue;
    }
    $GLOBALS['I'][$k] = $v;
}

require __DIR__ . DS . 'f.php';
require __DIR__ . DS . 'r' . DS . 'alert.php';
require __DIR__ . DS . 'r' . DS . 'asset.php';
require __DIR__ . DS . 'r' . DS . 'file.php';
require __DIR__ . DS . 'r' . DS . 'route.php';
require __DIR__ . DS . 'r' . DS . 'user.php';
