<?php

$folders = [];

$i = .9;
foreach (g(LOT) as $k => $v) {
    if ($v === 0) {
        $folders[$n = basename($k)] = [
            'icon' => ['M10,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V8C22,6.89 21.1,6 20,6H12L10,4Z'],
            'title' => $language->{$n === 'x' ? 'extension' : $n},
            'url' => $url . '/' . $n, // TODO
            'stack' => $i + .1
        ];
    }
}

return [
    'bar' => [
        // type: Bar
        'lot' => [
            0 => [
                // type: List
                'lot' => [
                    0 => [
                        // tags: [main]
                        'lot' => $folders
                    ]
                ]
            ],
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
                            ]
                        ],
                        'stack' => 30
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