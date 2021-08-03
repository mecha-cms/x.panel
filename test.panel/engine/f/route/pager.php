<?php namespace x\panel\route\__test;

function pager($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $_['title'] = 'Pager';
    $lot = [];
    $lot['pager-0'] = [
        'type' => 'pager',
        'count' => 1000,
        'chunk' => 10,
        'current' => $_['i'] ?? 1,
        'stack' => 10
    ];
    $lot['pager-2'] = [
        'type' => 'pager',
        'count' => 1000,
        'chunk' => 10,
        'current' => \Get::get('page') ?? 1,
        'ref' => function($index) use($url) {
            return $url . $url->path . $url->i . $url->query('&', [
                'page' => 1 === $index ? false : $index
            ]) . $url->hash;
        },
        'stack' => 20
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}
