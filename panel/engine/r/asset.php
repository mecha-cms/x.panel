<?php

Hook::set('start', function() {
    Asset::let();
    Asset::set(__DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'asset' . DS . 'css' . DS . 'panel.css');
    Asset::set(__DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'asset' . DS . 'css' . DS . '@media.css');
    Asset::set(__DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'asset' . DS . 'css' . DS . 'panel' . DS . state('panel')['skin'] . '.css');
}, 10);