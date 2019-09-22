<?php

return [
    'bar' => [
        // type: Bar
        'lot' => [
            1 => [
                // type: List
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
            ],
            2 => [
                // type: List
                'lot' => [
                    'alert' => [
                        'title' => false,
                        'icon' => ['M21,19V20H3V19L5,17V11C5,7.9 7.03,5.17 10,4.29C10,4.19 10,4.1 10,4A2,2 0 0,1 12,2A2,2 0 0,1 14,4C14,4.1 14,4.19 14,4.29C16.97,5.17 19,7.9 19,11V17L21,19M14,21A2,2 0 0,1 12,23A2,2 0 0,1 10,21'],
                        'url' => '/' // TODO
                    ]
                ]
            ]
        ]
    ]
];