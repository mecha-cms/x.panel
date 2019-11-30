<?php

Hook::set('get', function() use($_) {
    Asset::let(); // Again: remove all asset(s)
    $f = __DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'asset' . DS;
    Asset::set($f . 'css' . DS . 'panel.css', 20, [
        'href' => function($href) use($_) {
            $q = false === strpos($href, '?') ? '?' : '&';
            return $href . $q . 'token=' . $_['token'];
        }
    ]);
    Asset::set($f . 'js' . DS . 'panel.js', 20, [
        'src' => function($src) use($_) {
            $q = false === strpos($src, '?') ? '?' : '&';
            return $src . $q . 'token=' . $_['token'];
        }
    ]);
    extract($GLOBALS);
    $js = $_;
    if (isset($js['f'])) {
        $js['f'] = To::URL($js['f']);
    }
    if (isset($js['ff'])) {
        $js['ff'] = To::URL($js['ff']);
    }
    unset($js['lot']);
    Asset::script('_=Object.assign(_||{},' . json_encode($js) . ');', 0);
    require __DIR__ . DS . 'layout.php';
}, 20);
