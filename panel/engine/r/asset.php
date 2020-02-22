<?php

Hook::set('get', function() use($_) {
    Asset::let(); // Again: remove all asset(s)
    $f = __DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'asset' . DS;
    $z = defined('DEBUG') && DEBUG ? '.' : '.min.';
    if (null !== State::get('x.scss')) {
        Asset::set($f . 'scss' . DS . 'panel.scss', 20);
    } else {
        Asset::set($f . 'css' . DS . 'panel' . $z . 'css', 20);
    }
    Asset::set($f . 'js' . DS . 'panel' . $z . 'js', 20);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'alert' . $z . 'js', 20.1);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'fetch' . $z . 'js', 20.1);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'menu' . $z . 'js', 20.1);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'tab' . $z . 'js', 20.1);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'field' . DS . 'query' . $z . 'js', 20.2);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'field' . DS . 'source' . $z . 'js', 20.2);
    extract($GLOBALS, EXTR_SKIP);
    $data = $_;
    if (isset($data['f'])) {
        $data['f'] = To::URL($data['f']);
    }
    if (isset($data['ff'])) {
        $data['ff'] = To::URL($data['ff']);
    }
    // Remove sensitive data
    unset($data['lot'], $data['user']);
    Asset::script('window._=Object.assign(window._||{},' . json_encode($data) . ');', 0);
    require __DIR__ . DS . 'layout.php';
}, 20);
