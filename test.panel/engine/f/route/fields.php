<?php namespace x\panel\route\__test;

function fields($_) {
    $_['title'] = 'Fields';
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'][0] = [
        'title' => 'Title Goes Here',
        'description' => 'Description goes here.',
        'type' => 'fields',
        'lot' => [
            'blob' => [
                'title' => 'Blob',
                'type' => 'blob',
                'name' => 'default[blob]'
            ],
            'blobs' => [
                'title' => 'Blobs',
                'type' => 'blobs',
                'name' => 'default[blobs]'
            ],
            'button' => [
                'title' => 'Button',
                'hint' => 'A Button',
                'type' => 'button',
                'name' => 'default[button]'
            ],
            'buttons' => [
                'title' => 'Buttons',
                'type' => 'buttons',
                'name' => 'default[buttons]',
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
                        'title' => 'Button 3',
                        'value' => 3
                    ],
                ]
            ],
            'color' => [
                'title' => 'Color',
                'type' => 'color',
                'value' => '#f00'
            ],
            'colors' => [
                'title' => 'Colors',
                'type' => 'colors',
                'lot' => ['#f00', '#0f0', '#00f'],
                'sort' => false
            ],
            'content' => [
                'title' => 'Content',
                'type' => 'content',
                'name' => 'default[content]'
            ],
            'date' => [
                'title' => 'Date',
                'type' => 'date',
                'name' => 'default[date]'
            ],
            'date-time' => [
                'title' => 'Date/Time',
                'type' => 'date-time',
                'name' => 'default[date-time]'
            ],
            'description' => [
                'title' => 'Description',
                'type' => 'description',
                'name' => 'default[description]'
            ],
            'email' => [
                'title' => 'Email',
                'type' => 'email',
                'name' => 'default[email]'
            ],
            'flex' => [
                'type' => 'flex',
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
                ]
            ],
            'hidden' => [
                'title' => 'Hidden',
                'type' => 'hidden',
                'name' => 'default[hidden]'
            ],
            'item' => [
                'title' => 'Item',
                'type' => 'item',
                'name' => 'default[item]',
                'lot' => [
                    1 => 'Item 1',
                    2 => 'Item 2',
                    3 => 'Item 3'
                ],
                'value' => 2
            ],
            'items' => [
                'title' => 'Items',
                'type' => 'items',
                'name' => 'default[items]',
                'lot' => [
                    1 => 'Item 1',
                    2 => 'Item 2',
                    3 => 'Item 3'
                ],
                'value' => [
                    2 => true,
                    3 => true
                ]
            ],
            'link' => [
                'title' => 'Link',
                'type' => 'link',
                'name' => 'default[link]'
            ],
            'name' => [
                'title' => 'Name',
                'type' => 'name',
                'name' => 'default[name]'
            ],
            'number' => [
                'title' => 'Number',
                'type' => 'number',
                'name' => 'default[number]'
            ],
            'option' => [
                'title' => 'Option',
                'type' => 'option',
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
                    ]
                ]
            ],
            'path' => [
                'title' => 'Path',
                'type' => 'path',
                'name' => 'default[path]'
            ],
            'pass' => [
                'title' => 'Pass',
                'type' => 'pass',
                'name' => 'default[pass]'
            ],
            'query' => [
                'title' => 'Query',
                'type' => 'query',
                'name' => 'default[query]'
            ],
            'range' => [
                'title' => 'Range',
                'type' => 'range',
                'name' => 'default[range]',
                'range' => [0, 100]
            ],
            'set' => [
                'title' => 'Set',
                'description' => 'Example field set.',
                'type' => 'set',
                'content' => ""
            ],
            'source' => [
                'title' => 'Source',
                'type' => 'source',
                'name' => 'default[source]'
            ],
            'text' => [
                'title' => 'Text',
                'type' => 'text',
                'name' => 'default[text]'
            ],
            'text-with-before' => [
                'title' => 'Text',
                'type' => 'text',
                'name' => 'default[text-with-before]',
                'value-before' => 'http://'
            ],
            'text-with-after' => [
                'title' => 'Text',
                'type' => 'text',
                'name' => 'default[text-with-after]',
                'value-after' => '.html'
            ],
            'text-with-before-after' => [
                'title' => 'Text',
                'type' => 'text',
                'name' => 'default[text-with-before-after]',
                'value-before' => ['icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z'],
                'value-after' => 'text'
            ],
            'time' => [
                'title' => 'Time',
                'type' => 'time',
                'name' => 'default[time]'
            ],
            'title' => [
                'title' => 'Title',
                'type' => 'title',
                'name' => 'default[title]'
            ],
            'toggle' => [
                'title' => 'Toggle',
                'hint' => 'Toggle hint.',
                'type' => 'toggle',
                'name' => 'default[toggle]'
            ],
            'u-r-l' => [
                'title' => 'URL',
                'type' => 'u-r-l',
                'name' => 'default[u-r-l]'
            ]
        ]
    ];
    return $_;
}
