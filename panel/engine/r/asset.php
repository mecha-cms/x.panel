<?php

Hook::set('get', function() {
    Asset::let(); // Again: remove all asset(s)
    $f = __DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'asset' . DS;
    $z = defined('DEBUG') && DEBUG ? '.' : '.min.';
    extract($GLOBALS, EXTR_SKIP);
    if (isset($state->x->scss)) {
        Asset::set($f . 'scss' . DS . 'panel.scss', 20);
    } else {
        Asset::set($f . 'css' . DS . 'panel' . $z . 'css', 20);
    }
    Asset::set($f . 'js' . DS . 'panel' . $z . 'js', 20);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'alert' . $z . 'js', 20.1);
    if (!empty($state->x->panel->fetch)) {
        Asset::set($f . 'js' . DS . 'panel' . DS . 'fetch' . $z . 'js', 30); // Make sure to put this script on the last stack
    }
    Asset::set($f . 'js' . DS . 'panel' . DS . 'menu' . $z . 'js', 20.1);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'tab' . $z . 'js', 20.1);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'field' . DS . 'query' . $z . 'js', 20.2);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'field' . DS . 'source' . $z . 'js', 20.2);
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
    Asset::script('this._=Object.assign(this._||{},' . json_encode($_) . ');', 0);
}, 20);
