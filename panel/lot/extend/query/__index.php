<?php

Hook::set('on.panel.ready', function() use($language) {
    $__s = __DIR__ . DS . 'lot' . DS . 'asset' . DS;
    Asset::set([
        $__s . 'css' . DS . 't-i-b.min.css',
        $__s . 'js' . DS . 't-i-b.min.js',
        $__s . 'js' . DS . 't-i-b.fire.min.js'
    ], [
        10.2,
        10.2,
        10.21
    ]);
    Config::set('panel.o.js.TIB', array_replace((array) File::open(__DIR__ . DS . 'lot' . DS . 'state' . DS . 'config.php')->import(), [
        'text' => $language->__->panel->TIB->text
    ]));
}, 1);