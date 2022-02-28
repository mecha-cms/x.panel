<?php namespace x\panel\route\__test;

function separator($_) {
    $_['title'] = 'Separator';
    $lot = [];
    $lot['separator-0'] = [
        'stack' => 0,
        'type' => 'separator'
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}