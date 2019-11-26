<?php

Hook::set('get', function() {
    Asset::let(); // Again: remove all asset(s)
    $f = __DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'asset' . DS;
    Asset::set($f . 'css' . DS . 'panel.css', 20);
    Asset::set($f . 'js' . DS . 'panel.js', 20);
    extract($GLOBALS);
    require __DIR__ . DS . 'layout.php';
}, 20);
