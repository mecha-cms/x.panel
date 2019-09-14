<?php

$fields = [
    'default[0]' => [
        'type' => 'Field',
        'title' => 'Default',
        'content' => '<output class="output">ABCDEF</output>'
    ],
    'unknown[0]' => [
        'type' => null,
        'title' => 'Unknown',
        'value' => "Foo\nBar\n<b>Baz</b>"
    ],
    'unknown[1]' => [
        'type' => 'Foo',
        'title' => 'Unknown',
        'value' => "Foo\nBar\n<b>Baz</b>"
    ],
    'text[0]' => [
        'type' => 'Text',
        'title' => 'Text',
        'alter' => 'Text'
    ],
    'text[1]' => [
        'type' => 'Text',
        'title' => 'Text',
        'before' => ['icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z']
    ],
    'text[2]' => [
        'type' => 'Text',
        'title' => 'Text',
        'after' => '.php'
    ],
    'text[3]' => [
        'type' => 'Text',
        'title' => 'Text',
        'before' => 'IDR',
        'after' => '.00'
    ],
    'text[4]' => [
        'type' => 'Text',
        'title' => 'Text',
        'alter' => 'Text',
        'required' => true
    ],
    'number[0]' => [
        'type' => 'Number',
        'title' => 'Number',
        'min' => 0,
        'max' => 10,
        'step' => .5
    ],
    'range[0]' => [
        'type' => 'Range',
        'title' => 'Range',
        'min' => 0,
        'max' => 10,
        'step' => .5
    ],
    'pass[0]' => [
        'type' => 'Pass',
        'title' => 'Pass',
        'alter' => 'Pass'
    ],
    'color[0]' => [
        'type' => 'Color',
        'title' => 'Color'
    ],
    'colors[0]' => [
        'type' => 'Colors',
        'title' => 'Colors',
        'lot' => ['#ff0000', '#00ff00', '#0000ff']
    ],
    'content[0]' => [
        'type' => 'Content',
        'title' => 'Content',
        'alter' => 'Content'
    ],
    'source[0]' => [
        'type' => 'Source',
        'title' => 'Source',
        'alter' => 'Source'
    ],
    'combo[0]' => [
        'type' => 'Combo',
        'title' => 'Combo',
        'lot' => ['Red', 'Green', 'Blue']
    ],
    'combo[1]' => [
        'type' => 'Combo',
        'title' => 'Combo',
        'lot' => [
            'red' => 'Red',
            'green' => 'Green',
            'blue' => 'Blue',
            'gray' => [
                'title' => 'Gray',
                'active' => false
            ]
        ]
    ],
    'combo[2]' => [
        'type' => 'Combo',
        'title' => 'Combo Group',
        'lot' => [
            'color' => [
                'title' => 'Color',
                'lot' => ['Red', 'Green', 'Blue']
            ],
            'size' => [
                'title' => 'Size',
                'lot' => ['Small', 'Medium', 'Large']
            ]
        ]
    ],
    'combo[3]' => [
        'type' => 'Combo',
        'title' => 'Combo Group',
        'lot' => [
            'color' => [
                'title' => 'Color',
                'active' => false,
                'lot' => ['Red', 'Green', 'Blue']
            ],
            'size' => [
                'title' => 'Size',
                'lot' => [
                    'small' => 'Small',
                    'medium' => 'Medium',
                    'large' => 'Large',
                    'x-large' => [
                        'title' => 'X-Large',
                        'active' => false
                    ]
                ]
            ]
        ]
    ],
    'item[0]' => [
        'type' => 'Item',
        'title' => 'Item',
        'value' => 0,
        'lot' => ['Red', 'Green', [
            'title' => 'Blue',
            'active' => false
        ]]
    ],
    'item[1]' => [
        'type' => 'Item',
        'title' => 'Item',
        'value' => 2,
        'lot' => ['Item 1', 'Item 2', 'Item 3', 'Item 4', 'Item 5', 'Item 6', 'Item 7', [
            'title' => 'Item 8',
            'active' => false
        ]]
    ],
    'items[0]' => [
        'type' => 'Items',
        'title' => 'Items',
        'value' => 0,
        'lot' => ['Red', 'Green', [
            'title' => 'Blue',
            'active' => false
        ]]
    ],
    'items[1]' => [
        'type' => 'Items',
        'title' => 'Items',
        'value' => [2, 3],
        'lot' => ['Item 1', 'Item 2', 'Item 3', 'Item 4', 'Item 5', 'Item 6', 'Item 7', [
            'title' => 'Item 8',
            'active' => false
        ]]
    ],
    'toggle[0]' => [
        'type' => 'Toggle',
        'title' => 'Toggle'
    ],
    'blob[0]' => [
        'type' => 'Blob',
        'title' => 'Blob',
        'to' => 'asset'
    ],
    'hidden[0]' => [
        'type' => 'Hidden'
    ]
];

return [
    'desk' => [
        // type: Desk
        'lot' => [
            'form' => [
                // type: Form.Post
                'lot' => [
                    1 => [
                        // type: Section
                        'lot' => [
                            'tabs' => [
                                // type: Tabs
                                'lot' => [
                                    0 => [
                                        'title' => 'Test 1',
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => \map($fields, function($field, $key) {
                                                    $field['name'] = $key . '[0]';
                                                    return $field;
                                                })
                                            ]
                                        ]
                                    ],
                                    1 => [
                                        'title' => 'Test 2',
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => \map($fields, function($field, $key) {
                                                    $field['name'] = $key . '[1]';
                                                    $field['width'] = true;
                                                    return $field;
                                                })
                                            ]
                                        ]
                                    ],
                                    2 => [
                                        'title' => 'Test 3',
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => \map($fields, function($field, $key) {
                                                    $field['name'] = $key . '[2]';
                                                    $field['width'] = true;
                                                    $field['description'] = 'Description goes here.';
                                                    return $field;
                                                })
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'stack' => 20
                    ],
                    2 => [
                        // type: Section
                        'lot' => [
                            'fields' => [
                                'type' => 'Fields',
                                'lot' => [
                                    0 => [
                                        'type' => 'Field',
                                        'title' => "",
                                        'lot' => [
                                            0 => [
                                                'type' => 'Tasks.Button',
                                                'lot' => [
                                                    0 => [
                                                        'title' => 'Default',
                                                        'name' => 'x',
                                                        'stack' => 10
                                                    ],
                                                    1 => [
                                                        'type' => 'Button',
                                                        'title' => 'Button',
                                                        'name' => 'x',
                                                        'stack' => 10.1
                                                    ],
                                                    2 => [
                                                        'type' => 'Submit',
                                                        'title' => 'Submit',
                                                        'name' => 'x',
                                                        'stack' => 10.2
                                                    ],
                                                    3 => [
                                                        'type' => 'Reset',
                                                        'title' => 'Reset',
                                                        'name' => 'x',
                                                        'stack' => 10.3
                                                    ],
                                                    4 => [
                                                        'type' => 'Link',
                                                        'title' => 'Link',
                                                        'link' => 'https://example.com',
                                                        'stack' => 10.4
                                                    ],
                                                    5 => [
                                                        'title' => 'With Icon',
                                                        'icon' => ['M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z'],
                                                        'name' => 'x',
                                                        'stack' => 10.5
                                                    ],
                                                    6 => [
                                                        'active' => false,
                                                        'title' => 'Disabled',
                                                        'name' => 'x',
                                                        'stack' => 10.6
                                                    ],
                                                    7 => [
                                                        'active' => false,
                                                        'type' => 'Link',
                                                        'title' => 'Disabled Link',
                                                        'stack' => 10.7
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                'stack' => 10
                            ]
                        ]
                    ]
                ],
                'stack' => 10
            ]
        ],
        'stack' => 20
    ]
];