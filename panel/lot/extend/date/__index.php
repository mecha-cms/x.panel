<?php

Hook::set('on.panel.ready', function() {
    $__s = __DIR__ . DS . 'lot' . DS . 'asset' . DS;
    Asset::set([
        $__s . 'css' . DS . 't-p.min.css',
        $__s . 'js' . DS . 't-p.min.js',
        $__s . 'js' . DS . 't-p.fire.min.js'
    ], [
        10.2,
        10.2,
        10.21
    ]);
}, 1);