<?php namespace x\panel\route\__test;

function description($_) {
    $_['title'] = 'Description';
    $lot = [];
    $lot['description-0'] = [
        'content' => 'Lorem ipsum dolor sit amet.',
        'stack' => 0,
        'type' => 'description'
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}