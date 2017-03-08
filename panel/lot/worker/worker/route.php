<?php

Route::set([$__state->path . '/::%s%::/%*%/%i%', $__state->path . '/::%s%::/%*%'], function($__sgr, $__path, $__step = 1) use($__state, $__is_enter) {
    extract(Lot::get(null, []));
    $__sgr = To::url_decode($__sgr, true);
    $__path = To::url_decode($__path, true);
    $__path_shield = PANEL . DS . 'lot' . DS . 'shield' . DS . $__state->shield;
    $__chops = explode('/', $__path);
    $__DIR = Path::D(__DIR__);
    $__s = $__DIR . DS . 'worker' . DS;
    $__task = File::exist($__DIR . DS . 'index' . DS . $__chops[0] . '.php');
    $site->is = 'page'; // default is `page`
    require $__s . 'extend.php';
    require $__s . 'extend' . DS . 'plugin.php';
    require $__s . DS . 'shield.php';
    require $__s . DS . 'f.php';
    require $__s . DS . 'n.php';
    require $__s . DS . 'asset.php';
    require $__s . DS . 'lot.php';
    if (!$__task) {
        foreach (glob(EXTEND . DS . '*' . DS . 'lot' . DS . 'worker' . DS . 'index' . DS . $__chops[0] . '.php', GLOB_NOSORT) as $__v) {
            $__task = $__v;
            break;
        }
        if (!$__task) {
            Shield::abort(PANEL_404);
        }
    }
    $__token = Guardian::token();
    $__hash = Guardian::hash();
    require $__task;
    Lot::set([
        '__sgr' => $__sgr,
        '__path' => $__path,
        '__step' => $__step,
        '__chops' => $__chops,
        '__message' => Message::get(),
        '__token' => $__token,
        '__hash' => $__hash,
        '__path_shield' => $__path_shield,
        '__is_enter' => $__is_enter,
        '__f' => $__f,
        '__n' => $__n
    ]);
    Shield::attach([
        $__path_shield . DS . $site->is . '.php',
        $__DIR . DS . $site->is . '.php'
    ]);
}, 1);