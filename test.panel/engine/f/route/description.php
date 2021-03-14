<?php namespace x\panel\route\__test;

function description($_) {
    $_['title'] = 'Description';
    $lot = [
        'description-0' => [
            'type' => 'description',
            'content' => 'Lorem ipsum dolor sit amet.',
            'stack' => 0
        ]
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}
