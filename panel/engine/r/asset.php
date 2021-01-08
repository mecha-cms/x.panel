<?php

Hook::set('get', function() {
    Asset::let(); // Again: remove all asset(s)
    $f = __DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'asset' . DS;
    $z = defined('DEBUG') && DEBUG ? '.' : '.min.';
    extract($GLOBALS, EXTR_SKIP);
    Asset::set($f . 'css' . DS . 'r' . $z . 'css', 19.9);
    Asset::set($f . 'css' . DS . 'index' . $z . 'css', 20);
    Asset::set($f . 'js' . DS . 'r' . $z . 'js', 19.9);
    Asset::set($f . 'js' . DS . 'index' . $z . 'js', 20);
    require __DIR__ . DS . 'layout.php';
}, 20);

Hook::set('layout', function() {
    extract($GLOBALS);
    if (isset($_['f'])) {
        $_['f'] = To::URL($_['f']);
    }
    if (isset($_['ff'])) {
        $_['ff'] = To::URL($_['ff']);
    }
    // Remove sensitive data
    unset($_['lot'], $_['user']);
    Asset::script('window._=Object.assign(window._||{},' . json_encode($_) . ');', 0);
}, 20);
