<?php

Hook::set('start', function() {
    Asset::let(); // Again: remove all asset(s)
    $f = __DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'asset' . DS;
    Asset::set($f . 'css' . DS . 'panel.css', 20);
    Asset::set($f . 'css' . DS . 'panel' . DS . 'field' . DS . 'query.css', 20.1);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'alert.js', 20.2);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'menu.js', 20.2);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'tab.js', 20.2);
    Asset::set($f . 'js' . DS . 'tags-input-beautifier.js', 20.2);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'field' . DS . 'query.js', 20.2);
    extract($GLOBALS);
    require __DIR__ . DS . 'content.php';
}, 20);