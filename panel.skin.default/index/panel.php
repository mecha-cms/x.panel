<?php

$_['skin']['default'] = [
    'title' => 'Default',
    'path' => stream_resolve_include_path(__DIR__ . DS . '..' . DS . 'lot' . DS . 'asset' . DS . 'css' . DS . 'index' . (defined('DEBUG') && DEBUG ? '.' : '.min.') . 'css')
];

// Add dark/light mode variant
$variant = $_COOKIE['panel-skin-variant'] ?? 'dark';
if ('default' === ($state->x->panel->skin ?? P)) {
    if ('post' === $_['form']['type']) {
        $variant = $_['form']['lot']['cookie']['panel-skin-variant'] ?? null;
        if ($variant) {
            \setcookie('panel-skin-variant', $variant, \strtotime('+1 year'), '/', "", true, false);
        }
    }
    Hook::set('_', function($_) use($variant) {
        $_['asset'][stream_resolve_include_path(__DIR__ . DS . '..' . DS . 'lot' . DS . 'asset' . DS . 'js' . DS . 'index' . (defined('DEBUG') && DEBUG ? '.' : '.min.') . 'js')] = [
            'id' => false,
            'stack' => 50
        ];
        if ('.state' === $_['path'] && 'g' === $_['task']) {
            $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['panel']['lot']['fields']['lot']['variant'] = [
                'name' => 'cookie[panel-skin-variant]',
                'type' => 'item',
                'value' => $variant,
                'lot' => [
                    'dark' => 'Dark',
                    'light' => 'Light'
                ],
                'stack' => 30.1
            ];
        }
        return $_;
    });
    State::set('is.' . $variant, true); // Add skin variant class
}