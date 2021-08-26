<?php

Hook::set('get', function() {
    require __DIR__ . DS . 'layout.php';
    extract($GLOBALS);
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
    $out['panel.skin'] = [
        'id' => false,
        'path' => $f . 'css' . DS . 'index' . $z . 'css',
        'stack' => 20
    ];
    $out[$f . 'js' . DS . 'index' . $z . 'js'] = ['stack' => 20];
    $GLOBALS['_']['asset'] = array_replace_recursive($_['asset'] ?? [], $out);
}, 20);

Hook::set('layout', function() {
    // Load content first to queue the icon data
    if (isset($GLOBALS['_']['content'])) {
        $content = x\panel\type\lot([
            'content' => $GLOBALS['_']['content'] ?? [],
            'tags' => ['p' => false]
        ], 0);
    } else if (isset($GLOBALS['_']['lot'])) {
        $content = x\panel\type\lot([
            'lot' => $GLOBALS['_']['lot'] ?? [],
            'tags' => ['p' => false]
        ], 0);
    }
    extract($GLOBALS, EXTR_SKIP);
    // Build icon(s)
    $icons = "";
    if (!empty($_['icon'])) {
        $icons .= '<svg xmlns="http://www.w3.org/2000/svg" display="none">';
        foreach ($_['icon'] as $k => $v) {
            $icons .= '<symbol id="icon:' . $k . '" viewBox="0 0 24 24">';
            $icons .= 0 === strpos($v, '<') ? $v : '<path d="' . $v . '"></path>';
            $icons .= '</symbol>';
        }
        $icons .= '</svg>';
    }
    // Put icon(s) before content. Why? Because HTML5!
    $GLOBALS['panel'] = $icons . $content;
    if (isset($_['f'])) {
        $_['f'] = To::URL($_['f']);
    }
    if (isset($_['ff'])) {
        $_['ff'] = To::URL($_['ff']);
    }
    // Remove sensitive data
    unset($_['asset'], $_['icon'], $_['lot'], $_['skin'], $_['user']);
    Asset::script('window._=Object.assign(window._||{},' . json_encode($_) . ');', 0);
    \x\panel\_set_asset();
}, 20);