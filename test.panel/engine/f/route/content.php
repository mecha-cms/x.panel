<?php namespace x\panel\route\__test;

function content($_) {
    $_['title'] = 'Content';
    $lot = [];
    $lot['content-0'] = [
        'title' => 'Content 1',
        'description' => 'Description goes here.',
        'type' => 'content',
        'content' => '<p>Content goes here.</p>',
        'stack' => 10
    ];
    $lot['content-1'] = [
        'title' => 'Content 2',
        'description' => 'Description goes here.',
        'type' => 'content',
        'content' => '<p>Content goes here.</p>',
        'stack' => 20
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}
