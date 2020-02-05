<?php

$fields = [
    'default[0]' => [
        'title' => 'Default',
        'type' => 'Field',
        'content' => '<output class="output">ABCDEF</output>'
    ],
    'unknown[0]' => [
        'title' => 'Unknown',
        'type' => null,
        'value' => "Foo\nBar\n<b>Baz</b>"
    ],
    'unknown[1]' => [
        'title' => 'Unknown',
        'type' => 'Foo',
        'value' => "Foo\nBar\n<b>Baz</b>"
    ],
    'text[0]' => [
        'title' => 'Text',
        'type' => 'Text',
        'alt' => 'Text'
    ],
    'text[1]' => [
        'title' => 'Text',
        'type' => 'Text',
        'before' => ['icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z']
    ],
    'text[2]' => [
        'title' => 'Text',
        'type' => 'Text',
        'after' => '.php'
    ],
    'text[3]' => [
        'title' => 'Text',
        'type' => 'Text',
        'before' => 'IDR',
        'after' => '.00'
    ],
    'text[4]' => [
        'title' => 'Text',
        'type' => 'Text',
        'alt' => 'Text',
        'required' => true
    ],
    'number[0]' => [
        'title' => 'Number',
        'type' => 'Number',
        'min' => 0,
        'max' => 10,
        'step' => .5
    ],
    'range[0]' => [
        'title' => 'Range',
        'type' => 'Range',
        'min' => 0,
        'max' => 10,
        'step' => .5
    ],
    'pass[0]' => [
        'title' => 'Pass',
        'type' => 'Pass',
        'alt' => 'Pass'
    ],
    'color[0]' => [
        'title' => 'Color',
        'type' => 'Color'
    ],
    'colors[0]' => [
        'title' => 'Colors',
        'type' => 'Colors',
        'lot' => ['#ff0000', '#00ff00', '#0000ff']
    ],
    'content[0]' => [
        'title' => 'Content',
        'type' => 'Content',
        'alt' => 'Content'
    ],
    'source[0]' => [
        'title' => 'Source',
        'type' => 'Source',
        'alt' => 'Source'
    ],
    'combo[0]' => [
        'title' => 'Combo',
        'type' => 'Combo',
        'lot' => ['Red', 'Green', 'Blue']
    ],
    'combo[1]' => [
        'title' => 'Combo',
        'type' => 'Combo',
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
        'title' => 'Combo Group',
        'type' => 'Combo',
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
        'title' => 'Combo Group',
        'type' => 'Combo',
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
        'title' => 'Item',
        'type' => 'Item',
        'value' => 0,
        'lot' => ['Red', 'Green', [
            'title' => 'Blue',
            'active' => false
        ]]
    ],
    'item[1]' => [
        'title' => 'Item',
        'type' => 'Item',
        'value' => 2,
        'lot' => ['Item 1', 'Item 2', 'Item 3', 'Item 4', 'Item 5', 'Item 6', 'Item 7', [
            'title' => 'Item 8',
            'active' => false
        ]]
    ],
    'items[0]' => [
        'title' => 'Items',
        'type' => 'Items',
        'value' => ['red'],
        'flat' => true,
        'lot' => [
            'red' => 'Red',
            'green' => 'Green',
            'blue' => 'Blue',
            'yellow' => [
                'title' => 'Yellow',
                'active' => false
            ]
        ]
    ],
    'items[1]' => [
        'title' => 'Items',
        'type' => 'Items',
        'value' => [2, 3],
        'lot' => ['Item 1', 'Item 2', 'Item 3', 'Item 4', 'Item 5', 'Item 6', 'Item 7', [
            'title' => 'Item 8',
            'active' => false
        ]]
    ],
    'toggle[0]' => [
        'title' => 'Toggle',
        'type' => 'Toggle'
    ],
    'blob[0]' => [
        'title' => 'Blob',
        'type' => 'Blob',
        'to' => 'asset'
    ],
    'hidden[0]' => [
        'type' => 'Hidden'
    ],
    'set[0]' => [
        'type' => 'Set',
        'title' => 'Group Title',
        'lot' => [
            0 => [
                'type' => 'Fields',
                'title' => 'Fields Title',
                'description' => 'Fields description.',
                'lot' => [
                    0 => [
                        'type' => 'Text'
                    ],
                    1 => [
                        'type' => 'Text'
                    ]
                ]
            ]
        ],
        'stack' => 5
    ],
    'set[1]' => [
        'type' => 'Set',
        'title' => 'Group Title (<a href="">a link</a>)',
        'lot' => [
            0 => [
                'type' => 'Fields',
                'title' => 'Fields Title',
                'description' => 'Fields description.',
                'lot' => [
                    0 => [
                        'type' => 'Text'
                    ],
                    1 => [
                        'type' => 'Text'
                    ]
                ]
            ]
        ],
        'stack' => 5.1
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
                                            'tasks' => [
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
                                                    ],
                                                    8 => [
                                                        'active' => false,
                                                        'type' => 'Link',
                                                        'tags' => ['is:text'],
                                                        'title' => 'Disabled Link',
                                                        'stack' => 10.8
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
