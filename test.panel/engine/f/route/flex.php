<?php namespace x\panel\route\__test;

function flex($_) {
    $_['title'] = 'Flex';
    $lot = [];
    $lot['flex-0'] = [
        'description' => 'Description goes here.',
        'lot' => [
            0 => [
                'content' => '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>',
                'description' => 'Description goes here.',
                'stack' => 10,
                'title' => 'Content 1'
            ],
            1 => [
                'content' => '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>',
                'description' => 'Description goes here.',
                'stack' => 10,
                'title' => 'Content 2'
            ]
        ],
        'stack' => 10,
        'title' => 'Flex 1',
        'type' => 'flex'
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}