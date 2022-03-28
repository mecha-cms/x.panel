<?php

$skin = $state->x->panel->skin ?? 'default';
$variant = $_POST['cookie']['variant'] ?? cookie('panel.skin.default.variant') ?? null;

// Load asset and enable variant option if current `skin` value is `default`
if ('default' === $skin) {
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        if ($variant) {
            cookie('panel.skin.default.variant', $variant, strtotime('+1 year'));
        }
    }
    $_['asset']['panel.skin.' . $skin] = [
        'id' => false,
        'path' => stream_resolve_include_path(__DIR__ . D . '..' . D . 'index' . (defined('TEST') && TEST ? '.' : '.min.') . 'css'),
        'stack' => 30
    ];
    $_['is'][$variant ?? 'dark'] = true;
}

// Add `default` skin option
if ('.state' === $_['path'] && 'get' === $_['task']) {
    $_['asset'][stream_resolve_include_path(__DIR__ . D . '..' . D . 'index' . (defined('TEST') && TEST ? '.' : '.min.') . 'js')] = [
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
            'name' => 'cookie[variant]',
            'type' => 'item',
            'value' => $variant ?? 'dark',
            'lot' => [
                'dark' => 'Dark',
                'light' => 'Light'
            ],
            'stack' => 30.1
        ];
    }
}