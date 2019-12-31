<?php

Hook::set('get', function() use($_) {
    Asset::let(); // Again: remove all asset(s)
    $f = __DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'asset' . DS;
    Asset::set($f . 'css' . DS . 'panel.min.css', 20);
    Asset::set($f . 'js' . DS . 'panel.min.js', 20);
    extract($GLOBALS);
    $js = $_;
    if (isset($js['f'])) {
        $js['f'] = To::URL($js['f']);
    }
    if (isset($js['ff'])) {
        $js['ff'] = To::URL($js['ff']);
    }
    // Remove sensitive data
    unset($js['lot'], $js['user']);
    Asset::script('window._=Object.assign(window._||{},' . json_encode($js) . ');', 0);
    require __DIR__ . DS . 'layout.php';
}, 20);