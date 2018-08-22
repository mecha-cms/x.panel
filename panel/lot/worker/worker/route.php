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
        'id' => ($id = array_shift($chops)),
        'path' => implode(DS, $chops)
    ]));
    Config::set('trace', new Anemon([$language->{$id}, $config->title], ' &#x00B7; '));
    Shield::attach(__DIR__ . DS . '..' . DS . '..' . DS . 'shield' . DS . 'files.php');
}, 19);