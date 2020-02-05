<?php

if (is_dir($f = $_['f']) && 'g' === $_['task']) {
    Alert::error('Path %s is not a %s.', ['<code>' . _\lot\x\panel\h\path($f) . '</code>', 'file']);
    Guard::kick($url . $_['/'] . '::g::' . $_['path'] . $url->query('&', [
        'layout' => false
    ]) . $url->hash);
}

$fields = [];

if (is_file($f)) {
    $i = 10;
    foreach ((array) require $f as $k => $v) {
        // Pre-defined field type
        $field = [
            'type' => 'Text',
            'width' => true,
            'value' => is_array($v) ? json_encode($v) : s($v),
            'stack' => $i
        ];
        if (true === $v || false === $v) {
            $field['type'] = 'Toggle';
            unset($field['width']);
        } else if (is_float($v) || is_int($v)) {
            $field['type'] = 'Number';
            $field['step'] = is_float($v) ? '.1' : '1';
            unset($field['width']);
        }
        $fields[$k] = $field;
        $i += 10;
    }
}

return [
    'bar' => [
        // type: Bar
        'lot' => [
            // type: List
            0 => [
                'lot' => [
                    'folder' => ['hidden' => true],
                    'link' => [
                        'url' => $url . $_['/'] . '::g::' . ('g' === $_['task'] ? dirname($_['path']) : $_['path']) . '/1' . $url->query('&', ['layout' => false, 'tab' => false]) . $url->hash,
                        'hidden' => false
                    ]
                ]
            ]
        ]
    ],
    'desk' => [
        // type: Desk
        'lot' => [
            'form' => [
                // type: Form.Post
                'lot' => [
                    'fields' => [
                        'type' => 'Fields',
                        'lot' => [ // Hidden field(s)
                            'token' => [
                                'type' => 'Hidden',
                                'value' => $_['token']
                            ],
                            'seal' => [
                                'type' => 'Hidden',
                                'name' => 'file[seal]',
                                'value' => '0600'
                            ]
                        ],
                        'stack' => -1
                    ],
                    1 => [
                        // type: Section
                        'lot' => [
                            'tabs' => [
                                // type: Tabs
                                'lot' => [
                                    'file' => [
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
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
                                'type' => 'Fields',
                                'lot' => [
                                    0 => [
                                        'title' => "",
                                        'type' => 'Field',
                                        'lot' => [
                                            'tasks' => [
                                                'type' => 'Tasks.Button',
                                                'lot' => [
                                                    's' => [
                                                        'title' => 'g' === $_['task'] ? 'Update' : 'Create',
                                                        'type' => 'Submit',
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
