<?php

Hook::set('on.panel.ready', function() {
    Asset::set(__DIR__ . DS . 'lot' . DS . 'asset' . DS . 'css' . DS . 't-p.min.css', 10.2);
    Asset::set(__DIR__ . DS . 'lot' . DS . 'asset' . DS . 'js' . DS . 't-p.min.js', 10.2);
    Asset::set(__DIR__ . DS . 'lot' . DS . 'asset' . DS . 'js' . DS . 't-p.fire.min.js', 10.21);
}, 1);