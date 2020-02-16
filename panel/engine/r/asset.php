<?php

Hook::set('get', function() use($_) {
    Asset::let(); // Again: remove all asset(s)
    $f = __DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'asset' . DS;
    $dot = defined('DEBUG') && DEBUG ? '.' : '.min.';
    if (null !== State::get('x.scss')) {
        Asset::set($f . 'scss' . DS . 'panel.scss', 20);
    } else {
        Asset::set($f . 'css' . DS . 'panel' . $dot . 'css', 20);
    }
    Asset::set($f . 'js' . DS . 'panel' . $dot . 'js', 20);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'alert' . $dot . 'js', 20.1);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'fetch' . $dot . 'js', 20.1);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'menu' . $dot . 'js', 20.1);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'tab' . $dot . 'js', 20.1);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'field' . DS . 'query' . $dot . 'js', 20.2);
    Asset::set($f . 'js' . DS . 'panel' . DS . 'field' . DS . 'source' . $dot . 'js', 20.2);
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
