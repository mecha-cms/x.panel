<?php

if ($_['file'] && 'get' === $_['task']) {
    $_['alert']['error'][] = ['Path %s is not a %s.', ['<code>' . x\panel\from\path($_['file']) . '</code>', 'folder']];
    $_['kick'] = x\panel\to\link([
        'path' => $_['path'],
        'query' => ['type' => false],
        'task' => 'get'
    ]);
    return $_;
}

$folder = $_['folder'];

$name = 'get' === $_['task'] ? basename($_['folder']) : "";

if ("" === $name) $name = null;

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
                'trash' => 'let' === $_['task'] ? (!empty($state->x->panel->guard->trash) ? date('Y-m-d-H-i-s') : null) : null,
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
                                                    'value' => 'set' === $_['task'] ? ['kick' => 1] : []
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

return ($_ = array_replace_recursive($_, [
    'lot' => [
        'bar' => $bar,
        'desk' => $desk
    ]
]));