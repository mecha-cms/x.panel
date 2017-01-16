<?php

Route::set([$state['path'] . '/::%s%::/%*%/%i%', $state['path'] . '/::%s%::/%*%'], function($sgr, $path, $step = 1) use($state) {
    extract(Lot::get(null, []));
    $chops = explode('/', $path);
    if (!$task = File::exist(Path::D(__DIR__) . DS . $chops[0] . '.php')) {
        Shield::abort();
    }
    $site->type = 'page';
    $shield_path = PANEL . DS . 'lot' . DS . 'shield' . DS . $state['shield'];
    require __DIR__ . DS . 'f.php';
    require __DIR__ . DS . 'asset.php';
    require $task;
    Lot::set([
        'state' => o($state),
        'sgr' => $sgr,
        'path' => $path,
        'step' => $step,
        'chops' => $chops,
        'shield_path' => $shield_path,
        'message' => Message::get(),
        'token' => Guardian::token(),
        'hash' => Guardian::hash()
    ]);
    require __DIR__ . DS . 'extend.php';
    require __DIR__ . DS . 'extend' . DS . 'plugin.php';
    Shield::attach($shield_path . DS . $site->type . '.php');
}, 1);