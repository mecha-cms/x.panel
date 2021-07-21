<?php

// Tell other(s) that this layout is using dark color scheme!
$_['is']['dark'] = true;
$_['is']['light'] = false;

$_['skin']['minima'] = stream_resolve_include_path(__DIR__ . DS . '..' . DS . 'asset' . DS . 'css' . DS . 'index' . (defined('DEBUG') && DEBUG ? '.' : '.min.') . 'css');

Hook::set('_', function($_) {
    if ('.state' === $_['path'] && 'g' === $_['task']) {
        if (
            isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['panel']['lot']['fields']['lot']['skin']['type']) &&
            'option' === $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['panel']['lot']['fields']['lot']['skin']['type']
        ) {
            $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['panel']['lot']['fields']['lot']['skin']['lot']['minima'] = 'Minima';
        }
    }
    return $_;
});
