<?php

$fields = [
    'default[0]' => [
        'title' => 'Default',
        'type' => 'field',
        'content' => '<output class="output">ABCDEF</output>'
    ],
    'unknown[0]' => [
        'title' => 'Unknown',
        'type' => null,
        'value' => "Foo\nBar\n<b>Baz</b>"
    ],
    'unknown[1]' => [
        'title' => 'Unknown',
        'type' => 'foo',
        'value' => "Foo\nBar\n<b>Baz</b>"
    ],
    'text[0]' => [
        'title' => 'Text',
        'type' => 'text',
        'alt' => 'Text'
    ],
    'text[1]' => [
        'title' => 'Text',
        'type' => 'text',
        'before' => ['icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z']
    ],
    'text[2]' => [
        'title' => 'Text',
        'type' => 'text',
        'after' => '.php'
    ],
    'text[3]' => [
        'title' => 'Text',
        'type' => 'text',
        'before' => 'IDR',
        'after' => '.00'
    ],
    'text[4]' => [
        'title' => 'Text',
        'type' => 'text',
        'alt' => 'Text',
        'required' => true
    ],
    'number[0]' => [
        'title' => 'Number',
        'type' => 'number',
        'min' => 0,
        'max' => 10,
        'step' => .5
    ],
    'range[0]' => [
        'title' => 'Range',
        'type' => 'range',
        'min' => 0,
        'max' => 10,
        'step' => .5
    ],
    'pass[0]' => [
        'title' => 'Pass',
        'type' => 'pass',
        'alt' => 'Pass'
    ],
    'color[0]' => [
        'title' => 'Color',
        'type' => 'color'
    ],
    'colors[0]' => [
        'title' => 'Colors',
        'type' => 'colors',
        'lot' => ['#ff0000', '#00ff00', '#0000ff']
    ],
    'content[0]' => [
        'title' => 'Content',
        'type' => 'content',
        'alt' => 'Content'
    ],
    'source[0]' => [
        'title' => 'Source',
        'type' => 'source',
        'alt' => 'Source'
    ],
    'combo[0]' => [
        'title' => 'Combo',
        'type' => 'combo',
        'lot' => ['Red', 'Green', 'Blue']
    ],
    'combo[1]' => [
        'title' => 'Combo',
        'type' => 'combo',
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
        'type' => 'combo',
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
        'type' => 'combo',
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
        'type' => 'item',
        'value' => 0,
        'lot' => ['Red', 'Green', [
            'title' => 'Blue',
            'active' => false
        ]]
    ],
    'item[1]' => [
        'title' => 'Item',
        'type' => 'item',
        'value' => 2,
        'lot' => ['Item 1', 'Item 2', 'Item 3', 'Item 4', 'Item 5', 'Item 6', 'Item 7', [
            'title' => 'Item 8',
            'active' => false
        ]]
    ],
    'items[0]' => [
        'title' => 'Items',
        'type' => 'items',
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
        'type' => 'items',
        'value' => [2, 3],
        'lot' => ['Item 1', 'Item 2', 'Item 3', 'Item 4', 'Item 5', 'Item 6', 'Item 7', [
            'title' => 'Item 8',
            'active' => false
        ]]
    ],
    'toggle[0]' => [
        'title' => 'Toggle',
        'type' => 'toggle'
    ],
    'blob[0]' => [
        'title' => 'Blob',
        'type' => 'blob',
        'to' => 'asset'
    ],
    'hidden[0]' => [
        'type' => 'hidden'
    ],
    'set[0]' => [
        'type' => 'set',
        'title' => 'Group Title and <a href="">Group Title Link</a>',
        'lot' => [
            0 => [
                'type' => 'fields',
                'title' => 'Fields Title',
                'description' => 'Fields description goes here.',
                'lot' => [
                    'Field 1' => [
                        'type' => 'text'
                    ],
                    'Field 2' => [
                        'type' => 'text'
                    ]
                ]
            ]
        ],
        'stack' => 5
    ]
];

return [
    'desk' => [
        // type: desk
        'lot' => [
            'form' => [
                // type: form/post
                'lot' => [
                    1 => [
                        // type: section
                        'lot' => [
                            'tabs' => [
                                // type: tabs
                                'lot' => [
                                    0 => [
                                        'title' => 'Test 1',
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'fields',
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
                                                'type' => 'fields',
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
                                                'type' => 'fields',
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
                        // type: section
                        'lot' => [
                            'fields' => [
                                'type' => 'fields',
                                'lot' => [
                                    0 => [
                                        'type' => 'field',
                                        'title' => "",
                                        'lot' => [
                                            'tasks' => [
                                                'type' => 'tasks/button',
                                                'lot' => [
                                                    0 => [
                                                        'title' => 'Default',
                                                        'name' => 'x',
                                                        'stack' => 10
                                                    ],
                                                    1 => [
                                                        'type' => 'button',
                                                        'title' => 'Button',
                                                        'name' => 'x',
                                                        'stack' => 10.1
                                                    ],
                                                    2 => [
                                                        'type' => 'submit',
                                                        'title' => 'Submit',
                                                        'name' => 'x',
                                                        'stack' => 10.2
                                                    ],
                                                    3 => [
                                                        'type' => 'reset',
                                                        'title' => 'Reset',
                                                        'name' => 'x',
                                                        'stack' => 10.3
                                                    ],
                                                    4 => [
                                                        'type' => 'link',
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
                                                        'type' => 'link',
                                                        'title' => 'Disabled Link',
                                                        'stack' => 10.7
                                                    ],
                                                    8 => [
                                                        'active' => false,
                                                        'type' => 'link',
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
