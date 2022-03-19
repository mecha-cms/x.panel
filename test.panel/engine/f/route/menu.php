<?php namespace x\panel\route\__test;

function menu($_) {
    $_['title'] = 'Menus';
    $lot = [];
    $lot['menu-0'] = [
        'description' => 'Menu description.',
        'lot' => [
            0 => [
                'stack' => 10,
                'title' => 'Menu 1',
                'url' => '/'
            ],
            1 => [
                'description' => 'Description for Menu 2.',
                'lot' => [
                    0 => [
                        'stack' => 10,
                        'title' => 'Menu 2.1',
                        'url' => '/'
                    ],
                    1 => [
                        'stack' => 20,
                        'status' => 24, // Test badge
                        'title' => 'Menu 2.2',
                        'url' => '/'
                    ],
                    2 => [
                        'stack' => 30,
                        'title' => 'Menu 2.3',
                        'url' => '/'
                    ]
                ],
                'stack' => 20,
                'title' => 'Menu 2',
                'url' => '/'
            ],
            3 => [
                'description' => 'Description for Menu 3.',
                'lot' => [
                    0 => [
                        'stack' => 10,
                        'title' => 'Menu 3.1',
                        'url' => '/'
                    ],
                    1 => [
                        'stack' => 20,
                        'status' => 24, // Test badge
                        'title' => 'Menu 3.2',
                        'url' => '/'
                    ],
                    2 => [
                        'stack' => 30,
                        'title' => 'Menu 3.3',
                        'url' => '/'
                    ]
                ],
                'stack' => 20,
                'title' => 'Menu 3',
                'url' => '/',
                // Enable description and title in sub-menu
                'with' => [
                    'description' => 'Menu description.',
                    'title' => 'Menu Title'
                ]
            ],
            4 => [
                'stack' => 30,
                'title' => 'Menu 4',
                'url' => '/'
            ],
            '4.5' => [
                'stack' => 30.5,
                'type' => 'separator'
            ],
            5 => [
                'description' => 'Default.',
                'stack' => 40,
                'title' => 'Menu 5',
                'url' => '/'
            ],
            6 => [
                'current' => true,
                'description' => 'Current.',
                'stack' => 40.1,
                'title' => 'Menu 6',
                'url' => '/'
            ],
            7 => [
                'active' => false,
                'description' => 'Disabled.',
                'stack' => 40.2,
                'title' => 'Menu 7',
                'url' => '/'
            ],
            8 => [
                'active' => false,
                'current' => true,
                'description' => 'Current, disabled.',
                'stack' => 40.3,
                'title' => 'Menu 8',
                'url' => '/'
            ],
        ],
        'title' => 'Menu Title',
        'type' => 'menu'
    ];
    $lot['menu-1'] = [
        'lot' => ['Foo', 'Bar', 'Baz'],
        'type'=> 'menu'
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}