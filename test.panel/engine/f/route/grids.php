<?php namespace x\panel\route\__test;

function grids($_) {
    $style = <<<CSS
.content\:column p,
.content\:row p {
  background: var(--fill-input);
  border: 1px solid;
  border-color: var(--stroke-input);
  color: var(--color-input);
  line-height: 1;
  padding: 1em;
}
CSS;
    $_['title'] = 'Rows and Columns';
    $_['asset']['style']['preview'] = [
        'content' => $style,
        'stack' => 10
    ];
    $lot = [];
    $lot['rows-0'] = [
        'type' => 'rows',
        'lot' => [
            'auto-0' => [
                'type' => 'row',
                'lot' => [
                    0 => [
                        'type' => 'columns',
                        'lot' => [
                            0 => [
                                'type' => 'column',
                                'content' => '<p>Row 1, Column 1</p>',
                                'stack' => 10
                            ],
                            1 => [
                                'type' => 'column',
                                'content' => '<p>Row 1, Column 2</p>',
                                'stack' => 20
                            ]
                        ],
                        'stack' => 10
                    ]
                ],
                'stack' => 10
            ],
            'auto-1' => [
                'type' => 'row',
                'lot' => [
                    0 => [
                        'type' => 'columns',
                        'lot' => [
                            0 => [
                                'type' => 'column',
                                'content' => '<p>Row 2, Column 1</p>',
                                'stack' => 10
                            ],
                            1 => [
                                'type' => 'column',
                                'content' => '<p>Row 2, Column 2</p>',
                                'stack' => 20
                            ],
                            2 => [
                                'type' => 'column',
                                'content' => '<p>Row 2, Column 3</p>',
                                'stack' => 30
                            ]
                        ],
                        'stack' => 10
                    ]
                ],
                'stack' => 20
            ],
            1 => [
                'type' => 'row',
                'content' => '<p>Row 3</p>',
                'stack' => 30
            ],
            2 => [
                'type' => 'row',
                'content' => '<p>Row 4</p>',
                'stack' => 40
            ]
        ],
        'stack' => 10
    ];
    $lot['separator-0'] = [
        'type' => 'separator',
        'tags' => ['my:2' => true],
        'stack' => 10.5
    ];
    $lot['rows-1'] = [
        'type' => 'rows',
        'lot' => [
            '6/6' => [
                'type' => 'row',
                'lot' => [
                    '6/6' => [
                        'type' => 'columns',
                        'lot' => [
                            0 => [
                                'type' => 'column',
                                'content' => '<p>6/6</p>',
                                'stack' => 10
                            ]
                        ]
                    ]
                ]
            ],
            '1/6' => [
                'type' => 'row',
                'lot' => [
                    '1/6' => [
                        'type' => 'columns',
                        'lot' => [
                            0 => [
                                'type' => 'column',
                                'size' => '1/6',
                                'content' => '<p>1/6</p>',
                                'stack' => 10
                            ],
                            1 => [
                                'type' => 'column',
                                'size' => '5/6',
                                'content' => '<p>5/6</p>',
                                'stack' => 20
                            ]
                        ]
                    ]
                ]
            ],
            '2/6' => [
                'type' => 'row',
                'lot' => [
                    '2/6' => [
                        'type' => 'columns',
                        'lot' => [
                            0 => [
                                'type' => 'column',
                                'size' => '2/6',
                                'content' => '<p>2/6</p>',
                                'stack' => 10
                            ],
                            1 => [
                                'type' => 'column',
                                'size' => '4/6',
                                'content' => '<p>4/6</p>',
                                'stack' => 20
                            ]
                        ]
                    ]
                ]
            ],
            '3/6' => [
                'type' => 'row',
                'lot' => [
                    '3/6' => [
                        'type' => 'columns',
                        'lot' => [
                            0 => [
                                'type' => 'column',
                                'size' => '3/6',
                                'content' => '<p>3/6</p>',
                                'stack' => 10
                            ],
                            1 => [
                                'type' => 'column',
                                'size' => '3/6',
                                'content' => '<p>3/6</p>',
                                'stack' => 20
                            ]
                        ]
                    ]
                ]
            ],
            '2/?' => [
                'type' => 'row',
                'lot' => [
                    '2/?' => [
                        'type' => 'columns',
                        'lot' => [
                            0 => [
                                'type' => 'column',
                                'size' => '2/6',
                                'content' => '<p>2/6</p>',
                                'stack' => 10
                            ],
                            1 => [
                                'type' => 'column',
                                'content' => '<p>auto</p>',
                                'stack' => 20
                            ]
                        ]
                    ]
                ]
            ],
        ],
        'stack' => 20
    ];
    $lot['separator-1'] = [
        'type' => 'separator',
        'tags' => ['my:2' => true],
        'stack' => 20.5
    ];
    $lot['rows-2'] = [
        'type' => 'rows',
        'lot' => [
            '1+1+1+1+1+1' => [
                'type' => 'row',
                'lot' => [
                    '1+1+1+1+1+1' => [
                        'type' => 'columns',
                        'lot' => [
                            0 => [
                                'type' => 'column',
                                'size' => '1/6',
                                'content' => '<p>1/6</p>',
                                'stack' => 10
                            ],
                            1 => [
                                'type' => 'column',
                                'size' => '1/6',
                                'content' => '<p>1/6</p>',
                                'stack' => 20
                            ],
                            2 => [
                                'type' => 'column',
                                'size' => '1/6',
                                'content' => '<p>1/6</p>',
                                'stack' => 30
                            ],
                            3 => [
                                'type' => 'column',
                                'size' => '1/6',
                                'content' => '<p>1/6</p>',
                                'stack' => 40
                            ],
                            4 => [
                                'type' => 'column',
                                'size' => '1/6',
                                'content' => '<p>1/6</p>',
                                'stack' => 50
                            ],
                            5 => [
                                'type' => 'column',
                                'size' => '1/6',
                                'content' => '<p>1/6</p>',
                                'stack' => 60
                            ]
                        ]
                    ]
                ]
            ],
            '2+2+2' => [
                'type' => 'row',
                'lot' => [
                    '2+2+2' => [
                        'type' => 'columns',
                        'lot' => [
                            0 => [
                                'type' => 'column',
                                'size' => '2/6',
                                'content' => '<p>2/6</p>',
                                'stack' => 10
                            ],
                            1 => [
                                'type' => 'column',
                                'size' => '2/6',
                                'content' => '<p>2/6</p>',
                                'stack' => 20
                            ],
                            2 => [
                                'type' => 'column',
                                'size' => '2/6',
                                'content' => '<p>2/6</p>',
                                'stack' => 30
                            ]
                        ]
                    ]
                ]
            ],
            '3+3' => [
                'type' => 'row',
                'lot' => [
                    '3+3' => [
                        'type' => 'columns',
                        'lot' => [
                            0 => [
                                'type' => 'column',
                                'size' => '3/6',
                                'content' => '<p>3/6</p>',
                                'stack' => 10
                            ],
                            1 => [
                                'type' => 'column',
                                'size' => '3/6',
                                'content' => '<p>3/6</p>',
                                'stack' => 20
                            ]
                        ]
                    ]
                ]
            ],
            '6' => [
                'type' => 'row',
                'lot' => [
                    '6' => [
                        'type' => 'columns',
                        'lot' => [
                            0 => [
                                'type' => 'column',
                                'size' => '6/6',
                                'content' => '<p>6/6</p>',
                                'stack' => 10
                            ]
                        ]
                    ]
                ]
            ]
        ],
        'stack' => 30
    ];
    $_['lot']['desk']['width'] = true;
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}