<?php namespace _\lot\x\panel\route\__test;

function section($_) {
    $_['title'] = 'Section';
    $lot = [
        'section-0' => [
            'title' => 'Section 1',
            'description' => 'Description goes here.',
            'type' => 'section',
            'content' => '<p>Content goes here.</p>',
            'stack' => 10
        ],
        'section-1' => [
            'title' => 'Section 2',
            'description' => 'Description goes here.',
            'type' => 'section',
            'content' => '<p>Content goes here.</p>',
            'stack' => 20
        ]
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}
