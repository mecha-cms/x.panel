<?php

Hook::set('_', function($_) {
    if (
        !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot']) &&
        !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['type']) &&
        'files' === $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['type']
    ) {
        foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'] as $k => &$v) {
            unset($v['tasks']['restore']);
        }
    }
    return $_;
}, 10.1);

$_ = require __DIR__ . DS . 'trash.php';

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['skip'] = true;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['file']['skip'] = true;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['folder']['skip'] = true;

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['l'] = [
    'title' => 'Refresh',
    'description' => 'Refresh all cache',
    'icon' => 'M19,8L15,12H18A6,6 0 0,1 12,18C11,18 10.03,17.75 9.2,17.3L7.74,18.76C8.97,19.54 10.43,20 12,20A8,8 0 0,0 20,12H23M6,12A6,6 0 0,1 12,6C13,6 13.97,6.25 14.8,6.7L16.26,5.24C15.03,4.46 13.57,4 12,4A8,8 0 0,0 4,12H1L5,16L9,12',
    'type' => 'link',
    'url' => $_['/'] . '/::f::/de4fea16' . $url->query('&', [
        'kick' => URL::short($url->current, false),
        'q' => false,
        'tab' => false,
        'token' => $_['token']
    ]) . $url->hash,
    'skip' => 0 === q(g($_['f'])),
    'stack' => 10
];

return $_;