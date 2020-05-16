<?php

$chops = explode('/', $p);

// `http://127.0.0.1/panel`
// `http://127.0.0.1/panel/::g::`
if (count($chops) < 3) {
    Guard::kick("");
}

// Remove the first path
array_shift($chops);

$task = $chops[0] && 0 === strpos($chops[0], '::') && '::' === substr($chops[0], -2) ? substr(array_shift($chops), 2, -2) : null;

$_['chops'] = $chops;
$_['path'] = $task ? implode('/', $chops) : null;
$_['task'] = $task;

// Normalize path value and remove any `\..` to prevent directory traversal attack
$f = LOT . DS . str_replace(DS . '..', "", strtr($_['path'], '/', DS));
$_['f'] = stream_resolve_include_path($f) ?: null;

// Make sure to have page offset on `items` view
if (null === $i && 'g' === $task && 1 === count($chops) && is_dir($f)) {
    Guard::kick($url->clean . '/1' . $url->query . $url->hash);
}

$GLOBALS['_'] = $_; // Update data

foreach ([
    0 => 'There is no error, the file uploaded with success.',
    1 => 'The uploaded file exceeds the <code>upload_max_filesize</code> directive in <code>php.ini</code>.',
    2 => 'The uploaded file exceeds the <code>MAX_FILE_SIZE</code> directive that was specified in the <abbr title="Hyper Text Markup Language">HTML</abbr> form.',
    3 => 'The uploaded file was only partially uploaded.',
    4 => 'No file was uploaded.',
    5 => '?',
    6 => 'Missing a temporary folder.',
    7 => 'Failed to write file to disk.',
    8 => 'A PHP extension stopped the file upload.'
] as $k => $v) {
    if (isset($GLOBALS['I']['#blob:' . $k])) {
        continue;
    }
    $GLOBALS['I']['#blob:' . $k] = $v;
}

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
require __DIR__ . DS . 'r' . DS . 'tag.php';
