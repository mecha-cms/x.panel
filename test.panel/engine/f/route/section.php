<?php namespace x\panel\route\__test;

function section($_) {
    $_['title'] = 'Section';
    $lot = [];
    $lot['section-0'] = [
        'content' => '<p>Content goes here.</p>',
        'description' => 'Description goes here.',
        'stack' => 10,
        'title' => 'Section 1',
        'type' => 'section'
    ];
    $lot['section-1'] = [
        'content' => '<p>Content goes here.</p>',
        'description' => 'Description goes here.',
        'stack' => 20,
        'title' => 'Section 2',
        'type' => 'section'
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}