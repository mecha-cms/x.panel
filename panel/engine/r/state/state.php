<?php

if (is_dir($f = $_['f']) && 'g' === $_['task']) {
    Alert::error('Path %s is not a %s.', ['<code>' . _\lot\x\panel\h\path($f) . '</code>', 'file']);
    Guard::kick($url . $_['/'] . '/::g::/' . $_['path'] . $url->query('&', [
        'layout' => false
    ]) . $url->hash);
}

$fields = [];

if (is_file($f)) {
    $i = 10;
    foreach ((array) require $f as $k => $v) {
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

return [
    'bar' => [
        // type: bar
        'lot' => [
            // type: bar/menu
            0 => [
                'lot' => [
                    'folder' => ['hidden' => true],
                    'link' => [
                        'url' => $url . $_['/'] . '/::g::/' . ('g' === $_['task'] ? dirname($_['path']) : $_['path']) . '/1' . $url->query('&', ['layout' => false, 'tab' => false]) . $url->hash,
                        'hidden' => false
                    ]
                ]
            ]
        ]
    ],
    'desk' => [
        // type: desk
        'lot' => [
            'form' => [
                // type: form/post
                'lot' => [
                    'fields' => [
                        'type' => 'fields',
                        'lot' => [ // Hidden field(s)
                            'token' => [
                                'type' => 'hidden',
                                'value' => $_['token']
                            ],
                            'seal' => [
                                'type' => 'hidden',
                                'name' => 'file[seal]',
                                'value' => '0600'
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
                        // type: Section
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
                                                    'l' => ['hidden' => true]
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
    ]
];
