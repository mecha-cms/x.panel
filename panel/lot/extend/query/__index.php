<?php

Hook::set('on.panel.ready', function() use($language) {
    Asset::set(__DIR__ . DS . 'lot' . DS . 'asset' . DS . 'css' . DS . 't-i-b.min.css', 10.2);
    Asset::set(__DIR__ . DS . 'lot' . DS . 'asset' . DS . 'js' . DS . 't-i-b.min.js', 10.2);
    Asset::set(__DIR__ . DS . 'lot' . DS . 'asset' . DS . 'js' . DS . 't-i-b.fire.min.js', 10.21);
    Config::set('panel.c.js.TIB', array_replace(require __DIR__ . DS . 'lot' . DS . 'state' . DS . 'config.php', [
        'text' => $language->TIB->text
    ]));
}, 1);