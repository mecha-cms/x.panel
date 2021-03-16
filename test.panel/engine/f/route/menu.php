<?php namespace x\panel\route\__test;

function menu($_) {
    $_['title'] = 'Menus';
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = [
        'menu-0' => [
            'type' => 'menu',
            'lot' => [
                0 => [
                    'title' => 'Menu 1',
                    'url' => '/',
                    'stack' => 10
                ],
                1 => [
                    'title' => 'Menu 2',
                    'url' => '/',
                    'lot' => [
                        0 => [
                            'title' => 'Menu 2.1',
                            'url' => '/',
                            'stack' => 10
                        ],
                        1 => [
                            'title' => 'Menu 2.2',
                            'url' => '/',
                            'info' => 24, // Test badge
                            'stack' => 20
                        ],
                        2 => [
                            'title' => 'Menu 2.3',
                            'url' => '/',
                            'stack' => 30
                        ]
                    ],
                    'stack' => 20
                ],
                2 => [
                    'title' => 'Menu 3',
                    'url' => '/',
                    'stack' => 30
                ],
                2.5 => [
                    'type' => 'separator',
                    'stack' => 30.5
                ],
                3 => [
                    'title' => 'Menu 4',
                    'description' => 'Default.',
                    'url' => '/',
                    'stack' => 40
                ],
                4 => [
                    'current' => true,
                    'title' => 'Menu 5',
                    'description' => 'Current.',
                    'url' => '/',
                    'stack' => 40.1
                ],
                5 => [
                    'active' => false,
                    'title' => 'Menu 6',
                    'description' => 'Disabled.',
                    'url' => '/',
                    'stack' => 40.2
                ],
                6 => [
                    'active' => false,
                    'current' => true,
                    'title' => 'Menu 7',
                    'description' => 'Current, disabled.',
                    'url' => '/',
                    'stack' => 40.3
                ],
            ]
        ]
    ];
    return $_;
}
