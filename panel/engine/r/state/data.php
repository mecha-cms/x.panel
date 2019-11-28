<?php

$name = is_file($f = $_['f']) ? pathinfo($f, PATHINFO_FILENAME) : null;
$content = $name ? file_get_contents($f) : null;

$path = 'g' === $_['task'] ? dirname($f) : $f;
$x = glob($path . '.{archive,draft,page}', GLOB_BRACE | GLOB_NOSORT);
$x = $x ? '.' . pathinfo($x[0], PATHINFO_EXTENSION) : '/1';

$trash = date('Y-m-d-H-i-s');

return [
    'bar' => [
        // type: Bar
        'lot' => [
            // type: List
            0 => [
                'lot' => [
                    'folder' => ['hidden' => true],
                    'link' => [
                        'url' => $url . $_['/'] . '::g::' . ('g' === $_['task'] ? dirname($_['path']) : $_['path']) . $x . $url->query('&', ['layout' => false, 'tab' => ['data']]) . $url->hash,
                        'hidden' => false
                    ],
                    's' => [
                        'hidden' => 's' === $_['task'],
                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                        'title' => false,
                        'description' => ['New %s', 'Data'],
                        'url' => str_replace('::g::', '::s::', dirname($url->clean)) . $url->query('&', ['layout' => 'data', 'tab' => false]) . $url->hash,
                        'stack' => 10.5
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
                                    'data' => [
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => [
                                                    'token' => [
                                                        'type' => 'Hidden',
                                                        'value' => $_['token']
                                                    ],
                                                    'seal' => [
                                                        'type' => 'Hidden',
                                                        'name' => 'file[seal]',
                                                        'value' => '0600'
                                                    ],
                                                    'content' => [
                                                        'type' => 'Source',
                                                        'name' => 'data[content]',
                                                        'alt' => 'Content goes here...',
                                                        'value' => $content,
                                                        'width' => true,
                                                        'height' => true,
                                                        'stack' => 10
                                                    ],
                                                    'name' => [
                                                        'type' => 'Text',
                                                        'pattern' => "^([_]?[a-z\\d]+([_-][a-z\\d]+)*)?$",
                                                        'after' => '.data',
                                                        'focus' => true,
                                                        'name' => 'data[name]',
                                                        'alt' => 'g' === $_['task'] ? ($name ?? 'foo-bar') : 'foo-bar',
                                                        'value' => $name,
                                                        'width' => true,
                                                        'stack' => 20
                                                    ]
                                                ],
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
                                                        'description' => ['Create in %s', _\lot\x\panel\h\path($_['f'])],
                                                        'type' => 'Submit',
                                                        'name' => false,
                                                        'stack' => 10
                                                    ],
                                                    'l' => [
                                                        'title' => 'Delete',
                                                        'type' => 'Link',
                                                        'url' => str_replace('::g::', '::l::', $url->clean . $url->query('&', [
                                                            'layout' => 'data',
                                                            'token' => $_['token'],
                                                            'trash' => $trash
                                                        ])),
                                                        'hidden' => 's' === $_['task'],
                                                        'stack' => 20
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
                ]
            ]
        ]
    ]
];