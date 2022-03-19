<?php namespace x\panel\route\__test;

function fields($_) {
    $_['title'] = 'Fields';
    $lot = [];
    $lot['fields-0'] = [
        'description' => 'Description goes here.',
        'lot' => [
            'blob' => [
                'name' => 'default[blob]',
                'title' => 'Blob',
                'type' => 'blob'
            ],
            'blobs' => [
                'name' => 'default[blobs]',
                'title' => 'Blobs',
                'type' => 'blobs'
            ],
            'button' => [
                'hint' => 'A Button',
                'name' => 'default[button]',
                'title' => 'Button',
                'type' => 'button'
            ],
            'buttons' => [
                'lot' => [
                    1 => [
                        'title' => 'Button 1',
                        'value' => 1
                    ],
                    2 => [
                        'title' => 'Button 2',
                        'value' => 2
                    ],
                    3 => [
                        'active' => false,
                        'title' => 'Button 3',
                        'value' => 3
                    ]
                ],
                'name' => 'default[buttons]',
                'title' => 'Buttons',
                'type' => 'buttons'
            ],
            'color' => [
                'title' => 'Color',
                'type' => 'color',
                'value' => '#f00'
            ],
            'colors' => [
                'lot' => ['#f00', '#0f0', '#00f'],
                'sort' => false,
                'title' => 'Colors',
                'type' => 'colors'
            ],
            'content' => [
                'name' => 'default[content]',
                'title' => 'Content',
                'type' => 'content'
            ],
            'content-disabled' => [
                'active' => false,
                'name' => 'default[content-disabled]',
                'title' => 'Content',
                'type' => 'content'
            ],
            'content-readonly' => [
                'fix' => true,
                'name' => 'default[content-readonly]',
                'title' => 'Content',
                'type' => 'content'
            ],
            'date' => [
                'name' => 'default[date]',
                'title' => 'Date',
                'type' => 'date'
            ],
            'date-time' => [
                'name' => 'default[date-time]',
                'title' => 'Date/Time',
                'type' => 'date-time'
            ],
            'description' => [
                'name' => 'default[description]',
                'title' => 'Description',
                'type' => 'description'
            ],
            'email' => [
                'name' => 'default[email]',
                'title' => 'Email',
                'type' => 'email'
            ],
            'flex' => [
                'lot' => [
                    'text-1' => [
                        'title' => 'Field 1',
                        'type' => 'text',
                        'width' => true
                    ],
                    'text-2' => [
                        'title' => 'Field 2',
                        'type' => 'text',
                        'width' => true
                    ],
                    'text-3' => [
                        'title' => 'Field 3',
                        'type' => 'text',
                        'width' => true
                    ]
                ],
                'type' => 'flex'
            ],
            'hidden' => [
                'name' => 'default[hidden]',
                'title' => 'Hidden',
                'type' => 'hidden'
            ],
            'item' => [
                'lot' => [
                    1 => 'Item 1',
                    2 => 'Item 2',
                    3 => [
                        'active' => false,
                        'title' => 'Item 3'
                    ],
                    4 => [
                        'active' => false,
                        'title' => 'Item 4'
                    ]
                ],
                'name' => 'default[item]',
                'title' => 'Item',
                'type' => 'item',
                'value' => 3
            ],
            'items' => [
                'lot' => [
                    1 => 'Item 1',
                    2 => 'Item 2',
                    3 => [
                        'active' => false,
                        'title' => 'Item 3'
                    ],
                    4 => [
                        'active' => false,
                        'title' => 'Item 4'
                    ]
                ],
                'name' => 'default[items]',
                'title' => 'Items',
                'type' => 'items',
                'values' => [
                    2 => true,
                    3 => true
                ]
            ],
            'link' => [
                'name' => 'default[link]',
                'title' => 'Link',
                'type' => 'link'
            ],
            'name' => [
                'name' => 'default[name]',
                'title' => 'Name',
                'type' => 'name'
            ],
            'number' => [
                'name' => 'default[number]',
                'title' => 'Number',
                'type' => 'number'
            ],
            'option' => [
                'lot' => [
                    1 => 'Item 1',
                    2 => 'Item 2',
                    3 => [
                        'title' => 'Item 3',
                        'value' => 'value:3'
                    ],
                    4 => [
                        'title' => 'Item 4',
                        'lot' => [
                            '4.1' => 'Item 4.1',
                            '4.2' => 'Item 4.2',
                            '4.3' => [
                                'title' => 'Item 4.3',
                                'value' => 'value:4.3'
                            ]
                        ]
                    ],
                    5 => [
                        'active' => false,
                        'title' => 'Other'
                    ]
                ],
                'name' => 'default[option]',
                'title' => 'Option',
                'type' => 'option'
            ],
            'option-disabled' => [
                'active' => false,
                'lot' => [
                    1 => 'Item 1',
                    2 => 'Item 2',
                    3 => 'Item 3'
                ],
                'name' => 'default[option-disabled]',
                'title' => 'Option',
                'type' => 'option'
            ],
            'path' => [
                'name' => 'default[path]',
                'title' => 'Path',
                'type' => 'path',
            ],
            'pass' => [
                'name' => 'default[pass]',
                'title' => 'Pass',
                'type' => 'pass'
            ],
            'query' => [
                'name' => 'default[query]',
                'title' => 'Query',
                'type' => 'query',
                // 'value' => 'foo, bar',
                'values' => ['foo', 'bar']
            ],
            'query-disabled' => [
                'active' => false,
                'name' => 'default[query-disabled]',
                'title' => 'Query',
                'type' => 'query',
                // 'value' => 'foo, bar',
                'values' => ['foo', 'bar']
            ],
            'query-readonly' => [
                'fix' => true,
                'name' => 'default[query-readonly]',
                'title' => 'Query',
                'type' => 'query',
                // 'value' => 'foo, bar',
                'values' => ['foo', 'bar']
            ],
            'range' => [
                'name' => 'default[range]',
                'range' => [0, 100],
                'title' => 'Range',
                'type' => 'range'
            ],
            'range-disabled' => [
                'active' => false,
                'name' => 'default[range]',
                'range' => [0, 100],
                'title' => 'Range',
                'type' => 'range'
            ],
            'set' => [
                'content' => '<p>Lorem ipsum dolor sit amet.</p>',
                'description' => 'Example field set.',
                'title' => 'Set',
                'type' => 'set'
            ],
            'source' => [
                'name' => 'default[source]',
                'title' => 'Source',
                'type' => 'source'
            ],
            'text' => [
                'name' => 'default[text]',
                'title' => 'Text',
                'type' => 'text'
            ],
            'text-disabled' => [
                'active' => false,
                'name' => 'default[text-disabled]',
                'title' => 'Text',
                'type' => 'text'
            ],
            'text-readonly' => [
                'fix' => true,
                'name' => 'default[text-readonly]',
                'title' => 'Text',
                'type' => 'text'
            ],
            'text-with-before' => [
                'name' => 'default[text-with-before]',
                'title' => 'Text',
                'type' => 'text',
                'value-before' => 'http://'
            ],
            'text-with-after' => [
                'name' => 'default[text-with-after]',
                'title' => 'Text',
                'type' => 'text',
                'value-after' => '.html'
            ],
            'text-with-before-after' => [
                'name' => 'default[text-with-before-after]',
                'title' => 'Text',
                'type' => 'text',
                'value-after' => 'text',
                'value-before' => ['icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z']
            ],
            'text-with-lot' => [
                'hint' => 'Select color&hellip;',
                'lot' => ['Red', 'Green', 'Blue'],
                'name' => 'default[text-with-lot]',
                'title' => 'Text',
                'type' => 'text',
            ],
            'time' => [
                'name' => 'default[time]',
                'title' => 'Time',
                'type' => 'time'
            ],
            'title' => [
                'name' => 'default[title]',
                'title' => 'Title',
                'type' => 'title'
            ],
            'toggle' => [
                'hint' => 'Toggle hint.',
                'name' => 'default[toggle]',
                'title' => 'Toggle',
                'type' => 'toggle'
            ],
            'u-r-l' => [
                'name' => 'default[u-r-l]',
                'title' => 'URL',
                'type' => 'u-r-l'
            ]
        ],
        'title' => 'Title Goes Here',
        'type' => 'fields'
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}