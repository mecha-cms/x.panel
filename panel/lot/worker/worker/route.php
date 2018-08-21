<?php

Route::set([
    'panel/::%s%::/%*%/%i%',
    'panel/::%s%::/%*%'
], function($act = 'g', $path = "", $step = null) {
    Asset::reset();
    extract(Lot::get(null, []));
    $chops = explode('/', $path);
    Lot::set('panel', new State([
        '>>' => $act,
        'chops' => $chops,
        'path' => $path
    ]));
    Config::set('trace', new Anemon([$language->{$chops[0]}, $config->title], ' &#x00B7; '));
    Shield::attach(__DIR__ . DS . '..' . DS . '..' . DS . 'shield' . DS . 'files.php');
}, 1);