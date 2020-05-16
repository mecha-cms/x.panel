<?php

return [
    'bar' => [
        // type: bar
        'lot' => [
            1 => [
                // type: bar/menu
                'lot' => [
                    1 => [
                        'title' => 'Menu',
                        'url' => '/',
                        'stack' => 10
                    ],
                    2 => [
                        'title' => 'Menu',
                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                        'description' => 'Menu description.',
                        'url' => '/',
                        'stack' => 20
                    ],
                    3 => [
                        'title' => 'Menu',
                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                        'url' => '/',
                        'lot' => [
                            0 => [
                                'title' => 'Foo',
                                'url' => '/'
                            ],
                            1 => [
                                'title' => 'Bar',
                                'url' => '/',
                                'lot' => [
                                    0 => [
                                        'title' => 'Bar Foo',
                                        'url' => '/'
                                    ],
                                    1 => [
                                        'title' => 'Bar Bar',
                                        'url' => '/'
                                    ],
                                    2 => [
                                        'title' => 'Bar Baz',
                                        'url' => '/'
                                    ]
                                ]
                            ],
                            2 => [
                                'title' => 'Baz',
                                'url' => '/'
                            ],
                            3 => [
                                'title' => 'Disabled',
                            ],
                            4 => [
                                'title' => 'Current',
                                'url' => '/',
                                'current' => true
                            ],
                            5 => [
                                'title' => 'Current Disabled',
                                'current' => true
                            ]
                        ],
                        'stack' => 30
                    ],
                    4 => [
                        'title' => 'Menu',
                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                        'stack' => 40
                    ]
                ]
            ]
        ]
    ]
];
