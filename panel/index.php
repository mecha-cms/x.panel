<?php

$state = Extend::state('panel');
$p = $state['path'];

$chops = explode('/', $url->path);
$r = array_shift($chops);
$c = str_replace('::', "", array_shift($chops));
$id = array_shift($chops);
$path = implode('/', $chops);

if ($r === $p) {

    $worker = __DIR__ . DS . 'lot' . DS . 'worker' . DS;
    $f = rtrim(LOT . DS . $id . DS . strtr($path, '/', DS), DS);
    Lot::set('panel', $GLOBALS['Panel'] = $panel = new State([
        'c' => $c, // Command
        'id' => $id, // Current folder
        'chops' => $chops,
        'path' => $path,
        'file' => is_file($f) ? $f : null,
        'folder' => is_dir($f) ? $f : null,
        'state' => \o($state),
        'view' => ($view = basename(HTTP::get('view', 'file', false))),
        'r' => $r, // root
        'v' => $view . (!$chops && $c === 'g' || $url->i !== null ? 's' : "") // Plural or singular?
    ]));

    require __DIR__ . DS . 'engine' . DS . 'ignite.php';
    require __DIR__ . DS . 'engine' . DS . 'fire.php';

    // Clean-up `GET` and `POST` data
    Set::get(fn\panel\_clean(Get::get()), false);
    Set::post(fn\panel\_clean(Get::post()), false);

    $tok = HTTP::get('token');
    if ($tok && Guardian::check($tok)) {
        require $worker . 'worker' . DS . 'task.php';
        require $worker . 'worker' . DS . 'h-t-t-p.php';
    } else if (HTTP::is('post')) {
        echo error('Invalid token.');
        exit;
    } else if ($c === 'a' || $c === 'r') {
        echo error('Invalid token.');
        exit;
    }

    if ($f = File::exist($worker . $panel->v . '.php')) require $f;
    if ($f = File::exist($worker . $panel->v . DS . $id . '.php')) require $f;

    require $worker . 'worker' . DS . 'route.php';

}