<?php

Route::set([$__state->path . '/::%s%::/%*%/%i%', $__state->path . '/::%s%::/%*%'], function($__sgr, $__path, $__step = 1) use($__state, $__is_enter) {
    extract(Lot::get(null, []));
    $__path_shield = PANEL . DS . 'lot' . DS . 'shield' . DS . $__state->shield;
    $__chops = explode('/', $__path);
    $____DIR__ = Path::D(__DIR__);
    $__task = File::exist($____DIR__ . DS . 'index' . DS . $__chops[0] . '.php');
    $site->type = 'page'; // default is `page`
    $__s = $____DIR__ . DS . 'worker' . DS;
    require $__s . 'extend.php';
    require $__s . 'extend' . DS . 'plugin.php';
    require $__s . DS . 'shield.php';
    require $__s . DS . 'f.php';
    require $__s . DS . 'n.php';
    require $__s . DS . 'asset.php';
    if (!$__task) {
        Shield::abort(PANEL_404);
    }
    $__token = Guardian::token();
    $__hash = Guardian::hash();
    require $__task;
    Lot::set([
        '__sgr' => $__sgr,
        '__f' => $__f,
        '__n' => $__n,
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