<?php namespace x\panel\route\__test;

function tasks($_) {
    $_['title'] = 'Tasks';
    $lot = [];
    $lot['tasks-0'] = [
        'type' => 'tasks',
        'lot' => [
            0 => [
                'title' => 'Task 1',
                'type' => 'button',
                'stack' => 10
            ],
            1 => [
                'title' => 'Task 2',
                'type' => 'button',
                'stack' => 20
            ],
            2 => [
                'title' => 'Task 3',
                'type' => 'button',
                'stack' => 30
            ],
            3 => [
                'active' => false,
                'title' => 'Task 4',
                'type' => 'button',
                'stack' => 40
            ]
        ]
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}