<?php

Hook::set('start', function() {
    Asset::let();
    Asset::set(__DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'asset' . DS . 'css' . DS . 'panel.css');
    Asset::set(__DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'asset' . DS . 'js' . DS . 'panel' . DS . 'menu.js');
    Asset::set(__DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'asset' . DS . 'js' . DS . 'panel' . DS . 'tab.js');
    extract($GLOBALS);
    require __DIR__ . DS . 'content.php';
}, 20);