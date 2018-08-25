<?php

$state = Extend::state('panel');
$p = $state['path'];

$chops = explode('/', $url->path);
$r = array_shift($chops);

if ($r === $p) {

    Lot::set('panel', $panel = new State([
        '$' => $r,
        '>>' => ($act = str_replace('::', "", array_shift($chops))),
        'id' => ($id = array_shift($chops)),
        'chops' => $chops,
        'path' => implode('/', $chops),
        'state' => $state,
        'view' => ($view = basename(HTTP::get('view', 'file', false))),
        'v' => $view . (!$chops || $url->i !== null ? 's' : "")
    ]));

    require __DIR__ . DS . 'engine' . DS . 'ignite.php';
    require __DIR__ . DS . 'engine' . DS . 'fire.php';

    if ($f = File::exist(__DIR__ . DS . 'lot' . DS . 'worker' . DS . $panel->v . '.php')) require $f;
    if ($f = File::exist(__DIR__ . DS . 'lot' . DS . 'worker' . DS . $panel->v . DS . $id . '.php')) require $f;

    require __DIR__ . DS . 'lot' . DS . 'worker' . DS . 'worker' . DS . 'route.php';

}