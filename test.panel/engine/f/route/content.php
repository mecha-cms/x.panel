<?php namespace x\panel\route\__test;

function content($_) {
    $_['title'] = 'Content';
    $lot = [];
    $lot['content-0'] = [
        'content' => '<p>Content goes here.</p>',
        'description' => 'Description goes here.',
        'stack' => 10,
        'title' => 'Content 1',
        'type' => 'content'
    ];
    $lot['content-1'] = [
        'content' => '<p>Content goes here.</p>',
        'description' => 'Description goes here.',
        'stack' => 20,
        'title' => 'Content 2',
        'type' => 'content'
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}