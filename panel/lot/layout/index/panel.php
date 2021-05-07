<?php

// Tell other(s) that this layout is using dark color scheme!
$_['is']['dark'] = true;
$_['is']['light'] = false;

$_['asset']['panel.skin.dark'] = [
    'id' => false,
    'path' => stream_resolve_include_path(__DIR__ . DS . '..' . DS . 'asset' . DS . 'css' . DS . 'index' . (defined('DEBUG') && DEBUG ? '.' : '.min.') . 'css'),
    'stack' => 20.1
];
