<?php namespace x\panel\route\__test;

function separator($_) {
    $_['title'] = 'Separator';
    $lot = [];
    $lot['separator-0'] = [
        'type' => 'separator',
        'stack' => 10
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}
