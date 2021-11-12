<?php

$skin = $state->x->panel->skin ?? 'default';
$variant = $_['form']['lot']['cookie']['panel-skin-variant'] ?? $_COOKIE['panel-skin-variant'] ?? null;

// Load asset and enable variant option if current `skin` value is `default`
if ('default' === $skin) {
    if ('post' === $_['form']['type']) {
        if ($variant) {
            setcookie('panel-skin-variant', $variant, strtotime('+1 year'), '/', "", true, false);
        }
    }
    $_['asset']['panel.skin.' . $skin] = [
        'id' => false,
        'path' => stream_resolve_include_path(__DIR__ . DS . '..' . DS . 'lot' . DS . 'asset' . DS . 'css' . DS . 'index' . (defined('DEBUG') && DEBUG ? '.' : '.min.') . 'css'),
        'stack' => 30
    ];
    $_['is'][$variant] = true;
}

// Add `default` skin option
if ('.state' === $_['path'] && 'g' === $_['task']) {
    $_['asset'][stream_resolve_include_path(__DIR__ . DS . '..' . DS . 'lot' . DS . 'asset' . DS . 'js' . DS . 'index' . (defined('DEBUG') && DEBUG ? '.' : '.min.') . 'js')] = [
        'id' => false,
        'stack' => 50
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['panel']['lot']['fields']['lot']['skin'] = [
        'name' => 'state[x][panel][skin]',
        'type' => 'option',
        'value' => $skin,
        'lot' => [
            'default' => 'Default',
            'none' => 'None' // Allow user to disable skin feature :)
        ],
        'stack' => 30
    ];
    if ('default' === $skin) {
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
}