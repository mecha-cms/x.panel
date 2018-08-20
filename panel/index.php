<?php

require __DIR__ . DS . 'engine' . DS . 'ignite.php';
require __DIR__ . DS . 'engine' . DS . 'fire.php';

Route::set(['panel/::%s%::/%*%/%i%', 'panel/::%s%::/%*%'], function($act = 'g', $path = "", $step = null) use($language, $config) {
    Asset::reset();
    $chops = explode('/', $path);
    Lot::set([
        '_act' => $act,
        '_chops' => $chops,
        '_path' => $path,
        '_step' => $step
    ]);
    Config::set('trace', new Anemon([$language->{$chops[0]}, $config->title], ' &#x00B7; '));
    Shield::attach(__DIR__ . DS . 'lot' . DS . 'shield' . DS . 'files.php');
});