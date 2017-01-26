<?php

Route::set([$__state['path'] . '/::%s%::/%*%/%i%', $__state['path'] . '/::%s%::/%*%'], function($__sgr, $__path, $__step = 1) use($__state, $__is_enter) {
    extract(Lot::get(null, []));
    $__chops = explode('/', $__path);
    $____DIR__ = Path::D(__DIR__);
    if (!$__task = File::exist($____DIR__ . DS . 'index' . DS . $__chops[0] . '.php')) {
        Shield::abort(PANEL_404);
    }
    $site->type = 'page'; // default is `page`
    $__path_shield = PANEL . DS . 'lot' . DS . 'shield' . DS . $__state['shield'];
    require $____DIR__ . DS . 'worker' . DS . 'extend.php';
    require $____DIR__ . DS . 'worker' . DS . 'extend' . DS . 'plugin.php';
    require $____DIR__ . DS . 'worker' . DS . 'f.php';
    require $____DIR__ . DS . 'worker' . DS . 'asset.php';
    $__token = Guardian::token();
    $__hash = Guardian::hash();
    require $__task;
    Lot::set([
        '__state' => o($__state),
        '__sgr' => $__sgr,
        '__path' => $__path,
        '__step' => $__step,
        '__chops' => $__chops,
        '__message' => Message::get(),
        '__token' => $__token,
        '__hash' => $__hash,
        '__path_shield' => $__path_shield,
        '__is_enter' => $__is_enter,
        '____DIR__' => $____DIR__
    ]);
    Shield::attach([
        $__path_shield . DS . $site->type . '.php',
        $____DIR__ . DS . $site->type . '.php'
    ]);
}, 1);