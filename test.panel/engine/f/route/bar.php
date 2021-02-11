<?php namespace _\lot\x\panel\route\__test;

function bar($_) {
    $_['title'] = 'Bar';
    $lot = [
        'bar-0' => [
            'type' => 'bar',
            'lot' => [
                'bar-0' => [
                    'lot' => [
                        'menu-0' => [
                            'title' => 'Menu 1',
                            'url' => '/',
                            'stack' => 10
                        ],
                        'menu-1' => [
                            'title' => 'Menu 2',
                            'url' => '/',
                            'stack' => 20
                        ],
                        'menu-2' => [
                            'title' => 'Menu 3',
                            'url' => '/',
                            'lot' => [
                                'menu-2-0' => [
                                    'title' => 'Menu 3.1',
                                    'url' => '/',
                                    'stack' => 10
                                ],
                                'menu-2-1' => [
                                    'title' => 'Menu 3.2',
                                    'url' => '/',
                                    'lot' => [
                                        'menu-2-1-0' => [
                                            'title' => 'Menu 3.2.1',
                                            'description' => 'Default.',
                                            'url' => '/',
                                            'stack' => 10
                                        ],
                                        'menu-2-1-1' => [
                                            'current' => true,
                                            'title' => 'Menu 3.2.2',
                                            'description' => 'Current.',
                                            'url' => '/',
                                            'stack' => 20
                                        ],
                                        'menu-2-1-2' => [
                                            'active' => false,
                                            'title' => 'Menu 3.2.3',
                                            'description' => 'Disabled.',
                                            'url' => '/',
                                            'stack' => 30
                                        ],
                                        'menu-2-1-3' => [
                                            'active' => false,
                                            'current' => true,
                                            'title' => 'Menu 3.2.4',
                                            'description' => 'Current, disabled.',
                                            'url' => '/',
                                            'stack' => 40
                                        ]
                                    ],
                                    'stack' => 20
                                ],
                                'menu-2-2' => [
                                    'title' => 'Menu 3.3',
                                    'url' => '/',
                                    'stack' => 30
                                ],
                            ],
                            'stack' => 30
                        ]
                    ],
                    'stack' => 10
                ]
            ],
            'stack' => 10
        ],
        'bar-1' => [
            'type' => 'bar',
            'lot' => [
                'bar-0' => [
                    'lot' => [
                        'menu-0' => [
                            'title' => 'Menu 1',
                            'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                            'url' => '/',
                            'stack' => 10
                        ],
                        'menu-1' => [
                            'title' => false,
                            'description' => 'Menu 2',
                            'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                            'url' => '/',
                            'stack' => 20
                        ],
                        'menu-2' => [
                            'title' => 'Menu 3',
                            'icon' => ['M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z', 'M17.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,9A1.5,1.5 0 0,1 19,10.5A1.5,1.5 0 0,1 17.5,12M14.5,8A1.5,1.5 0 0,1 13,6.5A1.5,1.5 0 0,1 14.5,5A1.5,1.5 0 0,1 16,6.5A1.5,1.5 0 0,1 14.5,8M9.5,8A1.5,1.5 0 0,1 8,6.5A1.5,1.5 0 0,1 9.5,5A1.5,1.5 0 0,1 11,6.5A1.5,1.5 0 0,1 9.5,8M6.5,12A1.5,1.5 0 0,1 5,10.5A1.5,1.5 0 0,1 6.5,9A1.5,1.5 0 0,1 8,10.5A1.5,1.5 0 0,1 6.5,12M12,3A9,9 0 0,0 3,12A9,9 0 0,0 12,21A1.5,1.5 0 0,0 13.5,19.5C13.5,19.11 13.35,18.76 13.11,18.5C12.88,18.23 12.73,17.88 12.73,17.5A1.5,1.5 0 0,1 14.23,16H16A5,5 0 0,0 21,11C21,6.58 16.97,3 12,3Z'],
                            'url' => '/',
                            'stack' => 30
                        ]
                    ],
                    'stack' => 10
                ]
            ],
            'stack' => 20
        ],
        'bar-2' => [
            'type' => 'bar',
            'lot' => [
                'bar-0' => [
                    'lot' => [
                        'menu-0' => [
                            'title' => 'Menu 1',
                            'description' => 'Default.',
                            'url' => '/',
                            'stack' => 10
                        ],
                        'menu-1' => [
                            'current' => true,
                            'title' => 'Menu 2',
                            'description' => 'Current.',
                            'url' => '/',
                            'stack' => 20
                        ],
                        'menu-2' => [
                            'active' => false,
                            'title' => 'Menu 3',
                            'description' => 'Disabled.',
                            'url' => '/',
                            'stack' => 30
                        ],
                        'menu-3' => [
                            'active' => false,
                            'current' => true,
                            'title' => 'Menu 4',
                            'description' => 'Current, disabled.',
                            'url' => '/',
                            'stack' => 40
                        ]
                    ]
                ]
            ],
            'stack' => 30
        ]
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}
