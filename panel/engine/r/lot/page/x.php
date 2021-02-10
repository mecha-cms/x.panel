<?php

$_ = require __DIR__ . DS . '..' . DS . 'page.php';

$uses = [
    'alert' => 1,
    'asset' => 1,
    'layout' => 1,
    'page' => 1,
    'panel' => 1,
    'user' => 1,
    'y-a-m-l' => 1
];

if (isset($uses[$_['chop'][1]])) {
    $_['lot']['desk']['lot']['form']['lot'][2]['lot']['fields']['lot'][0]['lot']['tasks']['lot']['l']['skip'] = true;
}

if ('post' !== $_['form']['type'] && 'php' === pathinfo($_['f'], PATHINFO_EXTENSION)) {
    $_['alert']['warning'][md5(__FILE__)] = 'Unless you are very familiar with what you are doing, I advise you not to edit the extension files directly through the control panel interface. It might prevent your page from loading completely when you make some mistakes.';
}

return $_;
