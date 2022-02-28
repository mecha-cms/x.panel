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
        'lot' => [
            'auto-0' => [
                'lot' => [
                    0 => [
                        'lot' => [
                            0 => [
                                'content' => '<p>Row 1, Column 1</p>',
                                'stack' => 10,
                                'type' => 'column'
                            ],
                            1 => [
                                'content' => '<p>Row 1, Column 2</p>',
                                'stack' => 20,
                                'type' => 'column'
                            ]
                        ],
                        'stack' => 10,
                        'type' => 'columns'
                    ]
                ],
                'stack' => 10,
                'type' => 'row'
            ],
            'auto-1' => [
                'lot' => [
                    0 => [
                        'lot' => [
                            0 => [
                                'content' => '<p>Row 2, Column 1</p>',
                                'stack' => 10,
                                'type' => 'column'
                            ],
                            1 => [
                                'content' => '<p>Row 2, Column 2</p>',
                                'stack' => 20,
                                'type' => 'column'
                            ],
                            2 => [
                                'content' => '<p>Row 2, Column 3</p>',
                                'stack' => 30,
                                'type' => 'column'
                            ]
                        ],
                        'stack' => 10,
                        'type' => 'columns'
                    ]
                ],
                'stack' => 20,
                'type' => 'row'
            ],
            1 => [
                'content' => '<p>Row 3</p>',
                'stack' => 30,
                'type' => 'row'
            ],
            2 => [
                'content' => '<p>Row 4</p>',
                'stack' => 40,
                'type' => 'row'
            ]
        ],
        'stack' => 10,
        'type' => 'rows'
    ];
    $lot['separator-0'] = [
        'stack' => 10.5,
        'type' => 'separator'
    ];
    $lot['rows-1'] = [
        'lot' => [
            '6/6' => [
                'lot' => [
                    '6/6' => [
                        'lot' => [
                            0 => [
                                'content' => '<p>6/6</p>',
                                'stack' => 10,
                                'type' => 'column'
                            ]
                        ],
                        'type' => 'columns'
                    ]
                ],
                'type' => 'row'
            ],
            '1/6' => [
                'lot' => [
                    '1/6' => [
                        'type' => 'columns',
                        'lot' => [
                            0 => [
                                'content' => '<p>1/6</p>',
                                'size' => '1/6',
                                'stack' => 10,
                                'type' => 'column'
                            ],
                            1 => [
                                'content' => '<p>5/6</p>',
                                'size' => '5/6',
                                'stack' => 20,
                                'type' => 'column'
                            ]
                        ]
                    ]
                ],
                'type' => 'row'
            ],
            '2/6' => [
                'lot' => [
                    '2/6' => [
                        'lot' => [
                            0 => [
                                'content' => '<p>2/6</p>',
                                'size' => '2/6',
                                'stack' => 10,
                                'type' => 'column'
                            ],
                            1 => [
                                'content' => '<p>4/6</p>',
                                'size' => '4/6',
                                'stack' => 20,
                                'type' => 'column'
                            ]
                        ],
                        'type' => 'columns'
                    ]
                ],
                'type' => 'row'
            ],
            '3/6' => [
                'lot' => [
                    '3/6' => [
                        'type' => 'columns',
                        'lot' => [
                            0 => [
                                'content' => '<p>3/6</p>',
                                'size' => '3/6',
                                'stack' => 10,
                                'type' => 'column'
                            ],
                            1 => [
                                'content' => '<p>3/6</p>',
                                'size' => '3/6',
                                'stack' => 20,
                                'type' => 'column'
                            ]
                        ]
                    ]
                ],
                'type' => 'row'
            ],
            '2/?' => [
                'lot' => [
                    '2/?' => [
                        'lot' => [
                            0 => [
                                'content' => '<p>2/6</p>',
                                'size' => '2/6',
                                'stack' => 10,
                                'type' => 'column'
                            ],
                            1 => [
                                'content' => '<p>auto</p>',
                                'stack' => 20,
                                'type' => 'column'
                            ]
                        ],
                        'type' => 'columns'
                    ]
                ],
                'type' => 'row'
            ],
        ],
        'stack' => 20,
        'tags' => ['p' => true],
        'type' => 'rows'
    ];
    $lot['separator-1'] = [
        'stack' => 20.5,
        'type' => 'separator'
    ];
    $lot['rows-2'] = [
        'lot' => [
            '1+1+1+1+1+1' => [
                'lot' => [
                    '1+1+1+1+1+1' => [
                        'lot' => [
                            0 => [
                                'content' => '<p>1/6</p>',
                                'size' => '1/6',
                                'stack' => 10,
                                'type' => 'column'
                            ],
                            1 => [
                                'content' => '<p>1/6</p>',
                                'size' => '1/6',
                                'stack' => 20,
                                'type' => 'column'
                            ],
                            2 => [
                                'content' => '<p>1/6</p>',
                                'size' => '1/6',
                                'stack' => 30,
                                'type' => 'column'
                            ],
                            3 => [
                                'content' => '<p>1/6</p>',
                                'size' => '1/6',
                                'stack' => 40,
                                'type' => 'column'
                            ],
                            4 => [
                                'content' => '<p>1/6</p>',
                                'size' => '1/6',
                                'stack' => 50,
                                'type' => 'column'
                            ],
                            5 => [
                                'content' => '<p>1/6</p>',
                                'size' => '1/6',
                                'stack' => 60,
                                'type' => 'column'
                            ]
                        ],
                        'type' => 'columns'
                    ]
                ],
                'type' => 'row'
            ],
            '2+2+2' => [
                'lot' => [
                    '2+2+2' => [
                        'lot' => [
                            0 => [
                                'content' => '<p>2/6</p>',
                                'size' => '2/6',
                                'stack' => 10,
                                'type' => 'column'
                            ],
                            1 => [
                                'content' => '<p>2/6</p>',
                                'size' => '2/6',
                                'stack' => 20,
                                'type' => 'column'
                            ],
                            2 => [
                                'content' => '<p>2/6</p>',
                                'size' => '2/6',
                                'stack' => 30,
                                'type' => 'column'
                            ]
                        ],
                        'type' => 'columns'
                    ]
                ],
                'type' => 'row'
            ],
            '3+3' => [
                'lot' => [
                    '3+3' => [
                        'lot' => [
                            0 => [
                                'content' => '<p>3/6</p>',
                                'size' => '3/6',
                                'stack' => 10,
                                'type' => 'column'
                            ],
                            1 => [
                                'content' => '<p>3/6</p>',
                                'size' => '3/6',
                                'stack' => 20,
                                'type' => 'column'
                            ]
                        ],
                        'type' => 'columns'
                    ]
                ],
                'type' => 'row'
            ],
            '6' => [
                'lot' => [
                    '6' => [
                        'lot' => [
                            0 => [
                                'content' => '<p>6/6</p>',
                                'size' => '6/6',
                                'stack' => 10,
                                'type' => 'column'
                            ]
                        ],
                        'type' => 'columns'
                    ]
                ],
                'type' => 'row'
            ]
        ],
        'stack' => 30,
        'tags' => ['p' => true],
        'type' => 'rows'
    ];
    $_['lot']['desk']['width'] = true;
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}