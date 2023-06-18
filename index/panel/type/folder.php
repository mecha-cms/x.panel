<?php

if (!$folder->exist && 'get' === $_['task']) {
    $_['alert']['error'][] = ['Path %s is not a %s.', ['<code>' . x\panel\from\path($folder->path ?? $file->path ?? P) . '</code>', 'folder']];
    $_['kick'] = [
        'part' => 1,
        'path' => dirname($_['path']),
        'query' => x\panel\_query_set(),
        'task' => 'get'
    ];
    return $_;
}

if ($folder->exist && 'set' === $_['task'] && 0 !== strpos($_['type'] . '/', 'folder/')) {
    $_['kick'] = ['task' => 'get'];
    return $_;
}

$trash = !empty($state->x->panel->trash) ? date('Y-m-d-H-i-s') : null;

$name = 'get' === $_['task'] ? ($folder->name(true) ?? "") : "";

if ("" === $name) $name = null;

return x\panel\type\folder(array_replace_recursive($_, [
    'lot' => [
        'desk' => [
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
                                    'lot' => [
                                        'folder' => [
                                            // `tab`
                                            'lot' => [
                                                'fields' => [
                                                    // `fields`
                                                    'lot' => [
                                                        'name' => ['value' => $name]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        2 => [
                            // `section`
                            'lot' => [
                                'fields' => [
                                    // `fields`
                                    'lot' => [
                                        0 => [
                                            // `field`
                                            'lot' => [
                                                'tasks' => [
                                                    // `tasks/button`
                                                    'lot' => [
                                                        'set' => [
                                                            'description' => $folder->exist && 'set' === $_['task'] ? ['Save to %s', x\panel\from\path($folder->path)] : null,
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'values' => ['trash' => $trash]
                ]
            ]
        ]
    ]
]));

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
                        'part' => 1,
                        'path' => 'get' === $_['task'] ? dirname($_['path']) : $_['path'],
                        'query' => x\panel\_query_set(),
                        'task' => 'get'
                    ]
                ],
                'set' => [
                    'description' => ['New %s', 'Folder'],
                    'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                    'skip' => 'set' === $_['task'],
                    'stack' => 10.5,
                    'title' => false,
                    'url' => [
                        'part' => 0,
                        'query' => x\panel\_query_set(['type' => 'folder']),
                        'task' => 'set'
                    ]
                ]
            ]
        ]
    ]
];

$desk = [
];

return ($_ = array_replace_recursive($_, [
    'lot' => [
        'bar' => $bar,
        'desk' => $desk
    ]
]));