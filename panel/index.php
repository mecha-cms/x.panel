<?php

$state = Extend::state('panel');
$p = $state['path'];

$chops = explode('/', $url->path);
$r = array_shift($chops);

if ($r === $p) {

    $worker = __DIR__ . DS . 'lot' . DS . 'worker' . DS;
    Lot::set('panel', $panel = new State([
        'c' => ($c = str_replace('::', "", array_shift($chops))), // command
        'id' => ($id = array_shift($chops)),
        'chops' => $chops,
        'path' => implode('/', $chops),
        'state' => $state,
        'view' => ($view = basename(HTTP::get('view', 'file', false))),
        'r' => $r, // root
        'v' => $view . (!$chops && $c === 'g' || $url->i !== null ? 's' : "") // plural or singular?
    ]));

    require __DIR__ . DS . 'engine' . DS . 'ignite.php';
    require __DIR__ . DS . 'engine' . DS . 'fire.php';

    // Clean-up `GET` and `POST` data
    Set::get(panel\_clean(Get::get()), false);
    Set::post(panel\_clean(Get::post()), false);

    $tok = HTTP::get('token');
    if ($tok && Guardian::check($tok)) {
        require $worker . 'gate.php';
    } else if (HTTP::is('post')) {
        exit('Invalid token.');
    }

    if ($f = File::exist($worker . $panel->v . '.php')) require $f;
    if ($f = File::exist($worker . $panel->v . DS . $id . '.php')) require $f;

    require $worker . 'worker' . DS . 'route.php';

}