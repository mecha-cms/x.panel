<?php

require __DIR__ . DS . 'before.php';

$fields = [
    'default[0]' => [
        'type' => 'field',
        'title' => 'Default',
        'content' => '<output class="output">ABCDEF</output>'
    ],
    'text[0]' => [
        'type' => 'field.text',
        'title' => 'Text',
        'placeholder' => 'Text'
    ],
    'number[0]' => [
        'type' => 'field.number',
        'title' => 'Number',
        'min' => 0,
        'max' => 10,
        'step' => .5
    ],
    'range[0]' => [
        'type' => 'field.range',
        'title' => 'Range',
        'min' => 0,
        'max' => 10,
        'step' => .5
    ],
    'pass[0]' => [
        'type' => 'field.pass',
        'title' => 'Pass',
        'placeholder' => 'Pass'
    ],
    'color[0]' => [
        'type' => 'field.color',
        'title' => 'Color'
    ],
    'content[0]' => [
        'type' => 'field.content',
        'title' => 'Content',
        'placeholder' => 'Content'
    ],
    'source[0]' => [
        'type' => 'field.source',
        'title' => 'Source',
        'placeholder' => 'Source'
    ],
    'combo[0]' => [
        'type' => 'field.combo',
        'title' => 'Combo',
        'lot' => ['Red', 'Green', 'Blue']
    ],
    'combo[1]' => [
        'type' => 'field.combo',
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
        'type' => 'field.combo',
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
        'type' => 'field.combo',
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
        'type' => 'field.item',
        'title' => 'Item',
        'lot' => ['Red', 'Green', [
            'title' => 'Blue',
            'active' => false
        ]]
    ],
    'item[1]' => [
        'type' => 'field.item',
        'title' => 'Item',
        'lot' => ['#000', '#f00', '#0f0', '#00f', '#fff', '#0ff', '#f0f', [
            'title' => '#ff0',
            'active' => false
        ]]
    ],
    'items[0]' => [
        'type' => 'field.items',
        'title' => 'Items',
        'lot' => ['Red', 'Green', [
            'title' => 'Blue',
            'active' => false
        ]]
    ],
    'items[1]' => [
        'type' => 'field.items',
        'title' => 'Items',
        'lot' => ['#000', '#f00', '#0f0', '#00f', '#fff', '#0ff', '#f0f', [
            'title' => '#ff0',
            'active' => false
        ]]
    ],
    'toggle[0]' => [
        'type' => 'field.toggle',
        'title' => 'Toggle'
    ],
    'blob[0]' => [
        'type' => 'field.blob',
        'title' => 'Blob'
    ],
    'hidden[0]' => [
        'type' => 'field.hidden'
    ]
];

echo _\lot\x\panel(['lot' => [
    'desk' => [
        'type' => 'desk.form.post',
        '/' => '/foo/bar',
        2 => ['enctype' => 'multipart/form-data'],
        'lot' => [
            'body' => [
                'type' => 'desk.body',
                'lot' => [
                    'tab' => [
                        'type' => 'tab',
                        'lot' => [
                            0 => [
                                'title' => 'Test 1',
                                'lot' => [
                                    'field' => [
                                        'type' => 'fields',
                                        'lot' => $fields
                                    ]
                                ]
                            ],
                            1 => [
                                'title' => 'Test 2',
                                'lot' => [
                                    'field' => [
                                        'type' => 'fields',
                                        'lot' => \map($fields, function($field) {
                                            $field['width'] = true;
                                            return $field;
                                        })
                                    ]
                                ]
                            ],
                            2 => [
                                'title' => 'Test 3',
                                'lot' => [
                                    'field' => [
                                        'type' => 'fields',
                                        'lot' => \map($fields, function($field) {
                                            $field['width'] = true;
                                            $field['description'] = 'Description goes here.';
                                            return $field;
                                        })
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'footer' => [
                'type' => 'desk.footer',
                'lot' => [
                    'task' => [
                        'type' => 'task',
                        'lot' => [
                            0 => [
                                'type' => 'button',
                                'title' => 'Action 1',
                                'name' => 'x',
                                'stack' => 10
                            ],
                            1 => [
                                'type' => 'button',
                                'title' => 'Action 2',
                                'name' => 'x',
                                'stack' => 20
                            ],
                            2 => [
                                'active' => false,
                                'type' => 'button',
                                'title' => 'Action 3',
                                'name' => 'x',
                                'stack' => 30
                            ],
                            3 => [
                                'type' => 'button',
                                'title' => 'Action 4',
                                'icon' => ['M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z'],
                                'name' => 'x',
                                'stack' => 40
                            ]
                        ]
                    ]
                ],
                'stack' => 30
            ]
        ]
    ]
]], 0, '#');

require __DIR__ . DS . 'after.php';