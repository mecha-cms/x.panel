<?php namespace x\panel\route\__test;

function typography($_) {
    $_['title'] = 'Typography';
    $lot = [];
    $lot['typography-0'] = [
        'content' => file_get_contents(__DIR__ . \D . 'typography.html'),
        'description' => 'Typography test.',
        'level' => 1,
        'stack' => 10,
        'title' => 'Typography',
        'type' => 'content'
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}