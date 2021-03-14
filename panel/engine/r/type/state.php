<?php

if (is_dir($f = $_['f']) && 'g' === $_['task']) {
    $_['alert']['error'][] = ['Path %s is not a %s.', ['<code>' . x\panel\from\path($f) . '</code>', 'file']];
    $_['kick'] = $_['/'] . '/::g::/' . $_['path'] . $url->query('&', [
        'type' => false
    ]) . $url->hash;
    return $_;
}

$fields = [];

if (is_file($f)) {
    $i = 10;
    foreach ((array) require x\panel\to\fresh($f) as $k => $v) {
        // Pre-defined field type
        $field = [
            'type' => 'text',
            'width' => true,
            'value' => is_array($v) ? json_encode($v) : s($v),
            'stack' => $i
        ];
        if (true === $v || false === $v) {
            $field['type'] = 'toggle';
            unset($field['width']);
        } else if (is_float($v) || is_int($v)) {
            $field['type'] = 'number';
            $field['step'] = is_float($v) ? '.1' : '1';
            unset($field['width']);
        }
        $fields[$k] = $field;
        $i += 10;
    }
}

$bar = [
    // type: bar
    'lot' => [
        // type: bar/menu
        0 => [
            'lot' => [
                'folder' => ['skip' => true],
                'link' => [
                    'url' => $_['/'] . '/::g::/' . ('g' === $_['task'] ? dirname($_['path']) : $_['path']) . '/1' . $url->query('&', [
                        'tab' => false,
                        'type' => false
                    ]) . $url->hash,
                    'skip' => false
                ]
            ]
        ]
    ]
];

$desk = [
    // type: desk
    'lot' => [
        'form' => [
            // type: form/post
            'lot' => [
                'fields' => [
                    'type' => 'fields',
                    'lot' => [
                        'seal' => [
                            'type' => 'hidden',
                            'name' => 'file[seal]',
                            'value' => '0600'
                        ],
                        'token' => [
                            'type' => 'hidden',
                            'value' => $_['token']
                        ],
                        'type' => [
                            'type' => 'hidden',
                            'value' => $_['type']
                        ]
                    ],
                    'stack' => -1
                ],
                1 => [
                    // type: section
                    'lot' => [
                        'tabs' => [
                            // type: tabs
                            'lot' => [
                                'file' => [
                                    'lot' => [
                                        'fields' => [
                                            'type' => 'fields',
                                            'lot' => $fields,
                                            'stack' => 10
                                        ]
                                    ],
                                    'stack' => 10
                                ]
                            ]
                        ]
                    ]
                ],
                2 => [
                    // type: section
                    'lot' => [
                        'fields' => [
                            'type' => 'fields',
                            'lot' => [
                                0 => [
                                    'title' => "",
                                    'type' => 'field',
                                    'lot' => [
                                        'tasks' => [
                                            'type' => 'tasks/button',
                                            'lot' => [
                                                's' => [
                                                    'title' => 'g' === $_['task'] ? 'Update' : 'Create',
                                                    'type' => 'submit',
                                                    'name' => false,
                                                    'stack' => 10
                                                ],
                                                'l' => ['skip' => true]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            'stack' => 10
                        ]
                    ]
                ]
            ]
        ]
    ]
];

return ($_ = array_replace_recursive($_, [
    'lot' => [
        'bar' => $bar,
        'desk' => $desk
    ]
]));
