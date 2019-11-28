<?php

$lot = require __DIR__ . DS . '..' . DS . $_['layout'] . '.php';

$uses = [
    'alert' => 1,
    'asset' => 1,
    'layout' => 1,
    'page' => 1,
    'panel' => 1,
    'user' => 1,
    'y-a-m-l' => 1
];

if (isset($uses[$_['chops'][1]])) {
    $lot['desk']['lot']['form']['lot'][2]['lot']['fields']['lot'][0]['lot']['tasks']['lot']['l']['hidden'] = true;
}

if ('POST' !== $_SERVER['REQUEST_METHOD'] && 'php' === pathinfo($_['path'], PATHINFO_EXTENSION)) {
    $GLOBALS['_']['alert']['warning'][md5(__FILE__)] = 'Unless you are very familiar with what you are doing, I advise you not to edit the extension files directly through the control panel interface. It might prevent your page from loading completely when you make some mistakes.';
}

return $lot;