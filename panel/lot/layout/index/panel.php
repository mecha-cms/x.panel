<?php

$_['skin']['minima'] = [
    'title' => 'Minima',
    'path' => stream_resolve_include_path(__DIR__ . DS . '..' . DS . 'asset' . DS . 'css' . DS . 'index' . (defined('DEBUG') && DEBUG ? '.' : '.min.') . 'css')
];

// Add dark/light mode variant
if ('minima' === ($state->x->panel->skin ?? 'none')) {
    if ('post' === $_['form']['type']) {
        $variant = $_['form']['lot']['cookie']['panel-skin-variant'] ?? null;
        if ($variant) {
            \setcookie('panel-skin-variant', $variant, \strtotime('+1 year'), '/', "", true, false);
        }
    }
    $variant = $_COOKIE['panel-skin-variant'] ?? 'dark';
    $_['is'][$variant] = true; // Add skin variant class
    Hook::set('_', function($_) use($variant) {
        if ('.state' === $_['path'] && 'g' === $_['task']) {
            $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['panel']['lot']['fields']['lot']['variant'] = [
                'name' => 'cookie[panel-skin-variant]',
                'type' => 'item',
                'value' => $variant,
                'lot' => [
                    'dark' => 'Dark',
                    'light' => [
                        'active' => false, // TODO
                        'title' => 'Light'
                    ]
                ],
                'stack' => 30.1
            ];
        }
        return $_;
    });
}
