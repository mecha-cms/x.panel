<?php namespace x\panel\route\__test;

function tasks($_) {
    $_['title'] = 'Tasks';
    $lot = [];
    $lot['tasks-0'] = [
        'lot' => [
            0 => [
                'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                'stack' => 10,
                'title' => 'Task 1',
                'type' => 'button'
            ],
            1 => [
                'stack' => 20,
                'title' => 'Task 2',
                'type' => 'button'
            ],
            2 => [
                'stack' => 30,
                'title' => 'Task 3',
                'type' => 'button'
            ],
            3 => [
                'active' => false,
                'stack' => 40,
                'title' => 'Task 4',
                'type' => 'button'
            ]
        ],
        'tags' => ['p' => true],
        'type' => 'tasks'
    ];
    $lot['tasks-1'] = [
        'lot' => [
            0 => [
                'stack' => 10,
                'type' => 'input',
                'value' => 'Text goes here.'
            ],
            1 => [
                'stack' => 20,
                'title' => 'Action 1',
                'type' => 'button'
            ],
            2 => [
                'stack' => 30,
                'type' => 'input'
            ],
            55 => [
                'lot' => ['Option 1', 'Option 2', 'Option 3'],
                'stack' => 30,
                'type' => 'select'
            ],
            3 => [
                'stack' => 40,
                'title' => 'Action 2',
                'type' => 'button'
            ]
        ],
        'type' => 'tasks',
        'tags' => ['p' => true]
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}