<?php

$state = Extend::state('panel');
$p = $state['path'];

$i = $url->i;
$chops = explode('/', $url->path);

if ($chops && $chops[0] === $p) {

    $p = array_shift($chops);
    $act = array_shift($chops);
    $path = implode('/', $chops);

    if ($f = File::exist(LOT . DS . $path)) {
        if ($i !== null && $f = File::exist(LOT . DS . $path . DS . $i)) {
            $GLOBALS['URL']['path'] .= '/' . $i;
            $GLOBALS['URL']['clean'] .= '/' . $i;
            $GLOBALS['URL']['i'] = null;
        }
        Config::set('is', [
            'error' => false,
            'file' => is_file($f) ? $f : false,
            'files' => is_dir($f) ? $f : false
        ]);
    }

    require __DIR__ . DS . 'engine' . DS . 'ignite.php';
    require __DIR__ . DS . 'engine' . DS . 'fire.php';

    require __DIR__ . DS . 'lot' . DS . 'worker' . DS . 'worker' . DS . 'route.php';

}