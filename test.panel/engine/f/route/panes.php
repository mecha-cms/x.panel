<?php namespace x\panel\route\__test;

function panes($_) {
    $style = <<<CSS
.content\:column p,
.content\:row p {
  background: #000;
  color: #fff;
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
    $_['lot']['desk']['width'] = true;
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}