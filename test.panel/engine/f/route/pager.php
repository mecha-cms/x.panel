<?php namespace x\panel\route\__test;

function pager($_) {
    $_['title'] = 'Pager';
    $lot = [
        'pager-0' => [
            'type' => 'pager',
            'count' => 1000,
            'chunk' => 10,
            'current' => $_['i'] ?? 1,
            'stack' => 10
        ]
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}
