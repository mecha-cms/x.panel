<?php

if (is_file($folder = $_['folder'] ?? $_['file']) && 'get' === $_['task']) {
    $_['alert']['error'][$folder] = ['Path %s is not a %s.', ['<code>' . x\panel\from\path($folder) . '</code>', 'folder']];
    $_['kick'] = x\panel\to\link([
        'part' => 1,
        'path' => dirname($_['path']),
        'query' => [
            'query' => null,
            'stack' => null,
            'tab' => null,
            'type' => null
        ],
        'task' => 'get'
    ]);
    return $_;
}

$name = 'get' === $_['task'] ? basename($_['folder']) : "";

if ("" === $name) $name = null;

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
                    'url' => x\panel\to\link([
                        'part' => 1,
                        'path' => 'get' === $_['task'] ? dirname($_['path']) : $_['path'],
                        'query' => [
                            'tab' => false,
                            'type' => false
                        ],
                        'task' => 'get'
                    ])
                ],
                'set' => [
                    'description' => ['New %s', 'Folder'],
                    'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                    'skip' => 'set' === $_['task'],
                    'stack' => 10.5,
                    'title' => false,
                    'url' => x\panel\to\link([
                        'part' => 0,
                        'query' => [
                            'tab' => false,
                            'type' => 'folder'
                        ],
                        'task' => 'set'
                    ])
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
            'data' => [
                'token' => $_['token'],
                'trash' => $trash,
                'type' => $_['type']
            ],
            'lot' => [
                1 => [
                    // `section`
                    'lot' => [
                        'tabs' => [
                            // `tabs`
                            'lot' => [
                                'folder' => [
                                    'lot' => [
                                        'fields' => [
                                            'lot' => [
                                                'name' => [
                                                    'focus' => true,
                                                    'name' => 'folder[name]',
                                                    'stack' => 10,
                                                    'type' => 'path',
                                                    'value' => $name,
                                                    'width' => true
                                                ],
                                                'options' => [
                                                    'block' => true,
                                                    'lot' => ['kick' => 'Redirect to folder'],
                                                    'stack' => 20,
                                                    'title' => "",
                                                    'type' => 'items',
                                                    'value' => 'set' === $_['task'] ? ['kick' => true] : []
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
                            'type' => 'fields',
                            'lot' => [
                                0 => [
                                    'lot' => [
                                        'tasks' => [
                                            'lot' => [
                                                'set' => [
                                                    'description' => ['Create in %s', x\panel\from\path($_['folder'])],
                                                    'name' => 'task',
                                                    'stack' => 10,
                                                    'title' => 'get' === $_['task'] ? 'Update' : 'Create',
                                                    'type' => 'submit',
                                                    'value' => $_['task']
                                                ],
                                                'let' => [
                                                    'name' => 'task',
                                                    'skip' => 'set' === $_['task'],
                                                    'stack' => 20,
                                                    'title' => 'Delete',
                                                    'value'=> 'let'
                                                ]
                                            ],
                                            'type' => 'tasks/button'
                                        ]
                                    ],
                                    'title' => "",
                                    'type' => 'field'
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

$GLOBALS['folder'] = is_dir($folder) ? new Folder($folder) : new Folder;

return ($_ = array_replace_recursive($_, [
    'lot' => [
        'bar' => $bar,
        'desk' => $desk
    ]
]));