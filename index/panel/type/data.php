<?php

if (!$file->exist && 'get' === $_['task']) {
    $_['alert']['error'][] = ['Path %s is not a %s.', ['<code>' . x\panel\from\path($file->path ?? $folder->path ?? P) . '</code>', 'file']];
    $_['kick'] = [
        'part' => 1,
        'path' => dirname($_['path']),
        'query' => x\panel\_query_set(),
        'task' => 'get'
    ];
    return $_;
}

if ($file->exist && 'set' === $_['task']) {
    $_['kick'] = ['task' => 'get'];
    return $_;
}

$name = $file->name;
$content = $name ? $file->content : null;

$path = 'get' === $_['task'] ? $file->parent->path : $file->path;
$x = glob($path . '.{archive,draft,page}', GLOB_BRACE | GLOB_NOSORT);
$x = $x ? '.' . pathinfo($x[0], PATHINFO_EXTENSION) : null;
$trash = !empty($state->x->panel->trash) ? date('Y-m-d-H-i-s') : null;

return x\panel\type\data(array_replace_recursive($_, [
    'lot' => [
        'bar' => [
            // `bar`
            'lot' => [
                0 => [
                    // `links`
                    'lot' => [
                        'link' => [
                            'url' => [
                                'part' => $x ? 0 : 1,
                                'path' => ('get' === $_['task'] ? dirname($_['path']) : $_['path']) . $x
                            ]
                        ]
                    ]
                ]
            ]
        ],
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
                                        'data' => [
                                            // `tab`
                                            'lot' => [
                                                'fields' => [
                                                    // `fields`
                                                    'lot' => [
                                                        'content' => ['value' => $content],
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