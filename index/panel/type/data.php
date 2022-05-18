<?php

if (is_dir(($file = $_['file'] ?? $_['folder']) ?? P) && 'get' === $_['task']) {
    $_['alert']['error'][$file] = ['Path %s is not a %s.', ['<code>' . x\panel\from\path($file) . '</code>', 'file']];
    $_['kick'] = [
        'part' => 1,
        'path' => dirname($_['path']),
        'query' => [
            'chunk' => null,
            'deep' => null,
            'query' => null,
            'stack' => null,
            'tab' => null,
            'type' => null,
            'x' => null
        ],
        'task' => 'get'
    ];
    return $_;
}

$name = is_file($file ?? P) ? pathinfo($file, PATHINFO_FILENAME) : null;
$content = $name ? file_get_contents($file) : null;

$path = 'get' === $_['task'] ? dirname($file) : $file;
$x = glob($path . '.{archive,draft,page}', GLOB_BRACE | GLOB_NOSORT);
$x = $x ? '.' . pathinfo($x[0], PATHINFO_EXTENSION) : null;

$trash = !empty($state->x->panel->guard->trash) ? date('Y-m-d-H-i-s') : null;

$bar = [
    // `bar`
    'lot' => [
        // `links`
        0 => [
            'lot' => [
                'folder' => ['skip' => true],
                'link' => [
                    'skip' => false,
                    'url' => [
                        'part' => $x ? 0 : 1,
                        'path' => ('get' === $_['task'] ? dirname($_['path']) : $_['path']) . $x,
                        'query' => [
                            'chunk' => null,
                            'deep' => null,
                            'query' => null,
                            'stack' => null,
                            'tab' => ['data'],
                            'type' => null,
                            'x' => null
                        ],
                        'task' => 'get'
                    ]
                ],
                'set' => [
                    'description' => ['New %s', 'Data'],
                    'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                    'skip' => 'set' === $_['task'],
                    'stack' => 10.5,
                    'title' => false,
                    'url' => [
                        'part' => 0,
                        'path' => dirname($_['path']),
                        'query' => [
                            'chunk' => null,
                            'deep' => null,
                            'query' => null,
                            'stack' => null,
                            'tab' => null,
                            'type' => 'data',
                            'x' => null
                        ],
                        'task' => 'set'
                    ]
                ]
            ]
        ]
    ]
];

$desk = [
    // `desk`
    'lot' => [
        'form' => [
            // `form/post`
            'lot' => [
                1 => [
                    // `section`
                    'lot' => [
                        'tabs' => [
                            // `tabs`
                            'gap' => false,
                            'lot' => [
                                'data' => [
                                    'lot' => [
                                        'fields' => [
                                            'lot' => [
                                                'content' => [
                                                    'height' => true,
                                                    'name' => 'file[content]',
                                                    'stack' => 10,
                                                    'type' => 'source',
                                                    'value' => $content,
                                                    'width' => true
                                                ],
                                                'name' => [
                                                    'focus' => true,
                                                    'name' => 'data[name]',
                                                    'stack' => 20,
                                                    'type' => 'name',
                                                    'value' => $name,
                                                    'value-after' => '.data',
                                                    'width' => true,
                                                    'x' => false
                                                ]
                                            ],
                                            'stack' => 10,
                                            'type' => 'fields'
                                        ]
                                    ],
                                    'stack' => 10
                                ]
                            ]
                        ]
                    ]
                ],
                2 => [
                    // `section`
                    'lot' => [
                        'fields' => [
                            'lot' => [
                                0 => [
                                    'lot' => [
                                        'tasks' => [
                                            'lot' => [
                                                'set' => [
                                                    'description' => ['Create in %s', x\panel\from\path($file)],
                                                    'name' => false,
                                                    'stack' => 10,
                                                    'title' => 'get' === $_['task'] ? 'Update' : 'Create',
                                                    'type' => 'submit'
                                                ],
                                                'let' => [
                                                    'name' => 'task',
                                                    'skip' => 'set' === $_['task'],
                                                    'stack' => 20,
                                                    'title' => 'Delete',
                                                    'value' => 'let'
                                                ]
                                            ],
                                            'type' => 'tasks/button'
                                        ]
                                    ],
                                    'title' => "",
                                    'type' => 'field'
                                ]
                            ],
                            'stack' => 10,
                            'type' => 'fields'
                        ]
                    ]
                ]
            ],
            'values' => [
                'file' => ['seal' => '0600'],
                'query' => ['tab' => 'get' === $_['task'] || 'set' === $_['task'] ? ['data'] : null],
                'token' => $_['token'],
                'trash' => $trash,
                'type' => $_['type']
            ]
        ]
    ]
];

$GLOBALS['file'] = is_file($file ?? P) ? new File($file) : new File;

return ($_ = array_replace_recursive($_, [
    'lot' => [
        'bar' => $bar,
        'desk' => $desk
    ]
]));