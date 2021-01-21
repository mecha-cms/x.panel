<?php

Hook::set('get', function() {
    $assets = Asset::get();
    $out = [];
    foreach (['.css', '.js'] as $v) {
        if (!empty($assets[$v])) {
            foreach ((array) $assets[$v] as $kk => $vv) {
                $out[$kk][2] = (array) ($vv[2] ?? []);
                $out[$kk]['path'] = $vv['path'] ?? null;
                $out[$kk]['skip'] = true;
                $out[$kk]['stack'] = (float) ($vv['stack'] ?? 10);
                $out[$kk]['url'] = $vv['url'] ?? null;
            }
        }
    }
    foreach (['script', 'style', 'template'] as $v) {
        if (!empty($assets[$v])) {
            foreach ((array) $assets[$v] as $kk => $vv) {
                $out[$v][$kk][2] = (array) ($vv[2] ?? []);
                $out[$v][$kk]['content'] = (string) ($vv['content'] ?? $vv[1] ?? "");
                $out[$v][$kk]['skip'] = true;
                $out[$v][$kk]['stack'] = (float) ($vv['stack'] ?? 10);
            }
        }
    }
    Asset::let(); // Remove all asset(s)
    $f = __DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'asset';
    $f = stream_resolve_include_path($f) . DS;
    $z = defined('DEBUG') && DEBUG ? '.' : '.min.';
    $out[$f . 'css' . DS . '0' . $z . 'css'] = ['stack' => 19.9];
    $out[$f . 'css' . DS . 'index' . $z . 'css'] = ['stack' => 20];
    $out[$f . 'js' . DS . '0' . $z . 'js'] = ['stack' => 19.8];
    $out[$f . 'js' . DS . '1' . $z . 'js'] = ['stack' => 19.9];
    $out[$f . 'js' . DS . 'index' . $z . 'js'] = ['stack' => 20];
    $GLOBALS['_']['asset'] = array_replace_recursive($out, $GLOBALS['_']['asset'] ?? []);
    extract($GLOBALS, EXTR_SKIP);
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
