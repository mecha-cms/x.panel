<?php

$chop = explode('/', $p);

// `http://127.0.0.1/panel`
// `http://127.0.0.1/panel/::g::`
if (count($chop) < 3) {
    Guard::kick("");
}

// Remove the first path
array_shift($chop);

$task = $chop[0] && strpos($chop[0], '::') === 0 && substr($chop[0], -2) === '::' ? substr(array_shift($chop), 2, -2) : null;

$_['chop'] = $chop;
$_['path'] = $task ? '/' . implode('/', $chop) : null;
$_['task'] = $task;

// Normalize path value and remove any `\..` to prevent directory traversal attack
$f = LOT . str_replace(DS . '..', "", strtr($_['path'], '/', DS));
$_['f'] = stream_resolve_include_path($f) ?: null;

// Make sure to have page offset on `items` view
if ($i === null && $task === 'g' && count($chop) === 1 && is_dir($f)) {
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
    $GLOBALS['I']['Blob: [' . $k . ']'] = $v;
}

$GLOBALS['I']['%s goes here...'] = "%s goes here\u{2026}";
$GLOBALS['I']['Content goes here...'] = "Content goes here\u{2026}";
$GLOBALS['I']['Description goes here...'] = "Description goes here\u{2026}";

require __DIR__ . DS . 'f.php';
require __DIR__ . DS . 'r' . DS . 'alert.php';
require __DIR__ . DS . 'r' . DS . 'asset.php';
require __DIR__ . DS . 'r' . DS . 'file.php';
require __DIR__ . DS . 'r' . DS . 'hook.php';
require __DIR__ . DS . 'r' . DS . 'route.php';