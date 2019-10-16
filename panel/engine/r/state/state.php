<?php

$lot = [
    'token' => [
        'type' => 'Hidden',
        'value' => $_['token']
    ]
];

/*
foreach (\State::get(null, true) as $k => $v) {
    if ($k === 'x') continue;
    $type = 'Source';
    if (is_int($v) || is_float($v)) {
        $type = 'Number';
    } else if ($v === true || $v === false) {
        $type = 'Toggle';
    } else if (is_string($v) && strpos($v, "\n") === false) {
        $type = 'Text';
    }
    $lot[$k] = [
        'type' => $type,
        'width' => true,
        'value' => is_array($v) ? json_encode($v) : $v
    ];
}
*/

return [
    'bar' => [
        // type: Bar
        'lot' => [
            // type: List
            0 => [
                'lot' => [
                    'folder' => [
                        'icon' => 'M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z',
                        'url' => $url . $_['/'] . '::g::' . ($_['task'] === 'g' ? dirname($_['path']) : $_['path']) . '/1' . $url->query('&', ['content' => false, 'tab' => false]) . $url->hash,
                        'lot' => false // Disable sub-menu(s)
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
                                                'lot' => $lot,
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
                                                        'title' => $_['task'] === 'g' ? 'Update' : 'Create',
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