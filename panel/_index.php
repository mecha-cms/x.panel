<?php

ini_set('max_execution_time', 300); // 5 minute(s)

$worker = __DIR__ . DS . 'lot' . DS . 'worker' . DS;
$f = rtrim(LOT . DS . $id . DS . strtr($path, '/', DS), DS);
$i = (string) $url->i;

// Fix URL data for numeric file/folder name
if ($i !== "" && file_exists($f . DS . $i)) {
    $path .= '/' . $i;
    $f .= DS . $i;
    $chops[] = $i;
    $i = null;
    $GLOBALS['URL']['path'] .= '/' . $i;
    $GLOBALS['URL']['clean'] .= '/' . $i;
    $GLOBALS['URL']['i'] = null;
}

Lot::set('panel', $GLOBALS['panel'] = $panel = new State([
    'c' => $c, // Command
    'id' => $id, // Current folder
    'chops' => $chops,
    'path' => $path,
    'file' => is_file($f) ? $f : null,
    'folder' => is_dir($f) ? $f : null,
    'state' => o($state),
    'view' => ($view = basename(HTTP::get('view', 'file', false))),
    'r' => $r, // root
    'v' => $view . (!$chops && $c === 'g' || $i !== "" ? 's' : "") // Plural or singular?
]));

require __DIR__ . DS . 'engine' . DS . 'ignite.php';
require __DIR__ . DS . 'engine' . DS . 'fire.php';

// Clean-up `FILES`, `GET`, `POST`, and `REQUEST` data
// Empty string and array will be removed from HTTP request
// Long string contains only white-space is considered empty in this case
// `0`, `false` and `null` is not considered empty in this case
Set::files(fn\panel\_clean(Get::files()), false);
Set::get(fn\panel\_clean(Get::get()), false);
Set::post(fn\panel\_clean(Get::post()), false);
Set::request(fn\panel\_clean(Get::request()), false);

// Check form token
$token = HTTP::get('token');
if ($token && $token === $user->token) {
    require $worker . 'worker' . DS . 'task.php';
    require $worker . 'worker' . DS . 'h-t-t-p.php';
} else if ($c === 'a' || $c === 'r') {
    Guardian::abort('Invalid token.');
}

if ($f = File::exist($worker . $panel->v . '.php')) require $f;
if ($f = File::exist($worker . $panel->v . DS . '$.php')) require $f;
if ($f = File::exist($worker . $panel->v . DS . $id . '.php')) require $f;

// User
if (is_numeric($i = $user->status)) {
    if ($f = File::exist($worker . $i . '.php')) require $f;
    if ($f = File::exist($worker . $i . DS . $id . '.php')) require $f;
}

require $worker . 'worker' . DS . 'route.php';