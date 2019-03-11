<?php

$worker = __DIR__ . DS . 'lot' . DS . 'worker' . DS;
$f = rtrim(LOT . DS . $id . DS . strtr($path, '/', DS), DS);
$i = (string) $url->i;

// Fix URL data for numeric file/folder name
if ($i !== "" && is_dir($f . DS . $i)) {
    $path .= '/' . $i;
    $f .= DS . $i;
    $chops[] = $i;
    $GLOBALS['URL']['path'] .= '/' . $i;
    $GLOBALS['URL']['clean'] .= '/' . $i;
    $GLOBALS['URL']['i'] = $i = null;
}

Lot::set('panel', $GLOBALS['panel'] = $panel = new State([
    'c' => $c, // Command
    'id' => $id, // Current folder
    'chops' => $chops,
    'path' => $path,
    'file' => is_file($f) ? $f : null,
    'folder' => is_dir($f) ? $f : null,
    'state' => o($state),
    'view' => ($view = basename(HTTP::get('view', false) ?? 'file')),
    'r' => $r, // root
    'v' => $view . (!$chops && $c === 'g' || $i !== "" ? 's' : "") // Plural or singular?
]));

Lot::set('_file', $GLOBALS['_file'] = $file = $panel->file ?: $panel->folder);

Config::set('is.panel', true);
if ($file) {
    Config::set('is.panel:' . $id, true);
}

require __DIR__ . DS . 'engine' . DS . 'ignite.php';
require __DIR__ . DS . 'engine' . DS . 'fire.php';

// Clean-up `FILES`, `GET`, `POST`, and `REQUEST` data
// Empty string and array will be removed from HTTP request
// Long string contains only white-space is considered empty in this case
// `0`, `false` and `null` is not considered empty in this case
$_FILES = fn\panel\_clean($_FILES ?? []);
$_GET = fn\panel\_clean($_GET ?? []);
$_POST = fn\panel\_clean($_POST ?? []);
$_REQUEST = fn\panel\_clean($_REQUEST ?? []);

// Check form token
$token = HTTP::post('token') ?? HTTP::get('token');
if ($token && $token === $user->token) {
    require $worker . 'worker' . DS . 'task.php';
    require $worker . 'worker' . DS . 'h-t-t-p.php';
} else if ($c === 'a' || $c === 'r') {
    Guard::abort('Invalid token.');
}

if ($f = File::exist($worker . $panel->v . '.php')) require $f;
if ($f = File::exist($worker . $panel->v . DS . '$.php')) require $f;
if ($f = File::exist($worker . $panel->v . DS . $id . '.php')) require $f;
$recurse_a = explode('/', $path);
$recurse_b = "";
while ($recurse_c = array_shift($recurse_a)) {
    $recurse_b .= DS . $recurse_c;
    if ($f = File::exist($worker . $panel->v . DS . $id . $recurse_b . '.php')) require $f;
}

// User
if (is_numeric($i = $user->status)) {
    if ($f = File::exist($worker . $i . '.php')) require $f;
    if ($f = File::exist($worker . $i . DS . $id . '.php')) require $f;
    $recurse_a = explode('/', $path);
    $recurse_b = "";
    while ($recurse_c = array_shift($recurse_a)) {
        $recurse_b .= DS . $recurse_c;
        if ($f = File::exist($worker . $i . DS . $id . $recurse_b . '.php')) require $f;
    }
}

require $worker . 'worker' . DS . 'route.php';