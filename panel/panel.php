<?php

$chop = explode('/', $p);

// `http://127.0.0.1/panel`
// `http://127.0.0.1/panel/::g::`
if (count($chop) < 3) {
    Guard::kick("");
}

// Remove the first path
array_shift($chop);

// Make sure to have page offset on `items` view
$task = $chop[0] && strpos($chop[0], '::') === 0 && substr($chop[0], -2) === '::' ? substr(array_shift($chop), 2, -2) : null;
if ($i === null && $task === 'g' && count($chop) === 1) {
    Guard::kick($url->clean . '/1' . $url->query . $url->hash);
}

$_['chop'] = $chop;
$_['path'] = $task ? '/' . implode('/', $chop) : null;
$_['task'] = $task;

// Normalize path value and remove any `\..` to prevent directory traversal attack
$f = LOT . DS . str_replace(DS . '..', "", strtr($_['path'], '/', DS));
$_['f'] = stream_resolve_include_path($f) ?: null;

$GLOBALS['_'] = $_; // Update data

require __DIR__ . DS . 'engine' . DS . 'f.php';
require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'asset.php';
require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'file.php';
require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'hook.php';
require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'language.php';
require __DIR__ . DS . 'engine' . DS . 'r' . DS . 'route.php';