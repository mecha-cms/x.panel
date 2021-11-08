<?php namespace x\panel\route\__test;

function typography($_) {
    $_['title'] = 'Typography';
    $lot = [];
    $lot['typography-0'] = [
        'title' => 'Typography',
        'level' => 1,
        'description' => 'Typography test.',
        'type' => 'content',
        'content' => file_get_contents(__DIR__ . \DS . 'typography.html'),
        'stack' => 10
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}