<?php namespace x\panel\route\__test;

function tasks($_) {
    $_['title'] = 'Tasks';
    $lot = [];
    $lot['tasks-0'] = [
        'type' => 'tasks',
        'tags' => ['p' => true],
        'lot' => [
            0 => [
                'title' => 'Task 1',
                'type' => 'button',
                'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
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
    $lot['tasks-1'] = [
        'type' => 'tasks',
        'tags' => ['p' => true],
        'lot' => [
            0 => [
                'type' => 'input',
                'value' => 'Text goes here.',
                'stack' => 10
            ],
            1 => [
                'title' => 'Action 1',
                'type' => 'button',
                'stack' => 20
            ],
            2 => [
                'type' => 'input',
                'stack' => 30
            ],
            3 => [
                'title' => 'Action 2',
                'type' => 'button',
                'stack' => 40
            ]
        ]
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}