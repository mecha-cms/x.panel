<?php namespace x\panel\type;

function blank(array $_ = []) {
    return \x\panel\type(\array_replace_recursive([
        'status' => 200,
        'type' => 'blank'
    ], $_));
}

function blob(array $_ = []) {
    $options = [];
    if (\extension_loaded('zip')) {
        $options['extract'] = [
            'name' => 'options[zip][extract]',
            'title' => 'Extract package automatically.'
        ];
        // $options['keep'] = [
        //     'name' => 'options[zip][keep]',
        //     'title' => 'Keep package after extract.'
        // ];
    }
    $path = $_['path'] ?? null;
    $query = (array) ($_['query'] ?? []);
    $task = $_['task'] ?? 'set';
    $token = $_['token'] ?? null;
    $type = $_['type'] ?? 'blob';
    return \x\panel\type(\array_replace_recursive([
        'lot' => [
            'bar' => [
                // `bar`
                'lot' => [
                    0 => [
                        // `links`
                        'lot' => [
                            'folder' => ['skip' => true],
                            'link' => [
                                'skip' => false,
                                'url' => [
                                    'part' => 1,
                                    'path' => 'get' === $task && $path ? \dirname($path) : $path,
                                    'query' => \x\panel\_query_set(),
                                    'task' => 'get'
                                ]
                            ],
                            'set' => [
                                'description' => ['New %s', 'File'],
                                'icon' => 'M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z',
                                'skip' => 'set' === $task,
                                'stack' => 10.5,
                                'title' => false,
                                'url' => [
                                    'part' => 0,
                                    'query' => \x\panel\_query_set(['type' => $type]),
                                    'task' => 'set'
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
                                            'blob' => [
                                                // `tab`
                                                'lot' => [
                                                    'fields' => [
                                                        // `fields`
                                                        'lot' => [
                                                            'blob' => [
                                                                'focus' => true,
                                                                'name' => 'blobs',
                                                                'stack' => 10,
                                                                'title' => 'File',
                                                                'type' => 'blobs',
                                                                'width' => true
                                                            ],
                                                            'options' => [
                                                                'flex' => false,
                                                                'lot' => $options,
                                                                'stack' => 20,
                                                                'title' => "",
                                                                'type' => 'items'
                                                            ]
                                                        ],
                                                        'stack' => 10,
                                                        'type' => 'fields'
                                                    ]
                                                ],
                                                'stack' => 10,
                                                'title' => 'Upload'
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
                                                                'name' => 'task',
                                                                'stack' => 10,
                                                                'title' => 'Upload',
                                                                'type' => 'submit',
                                                                'value' => 'set'
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
                            'kick' => $query['kick'] ?? null,
                            'token' => $token ?? null,
                            'type' => $type
                        ]
                    ]
                ]
            ]
        ],
        'status' => 200,
        'task' => $task,
        'type' => $type
    ], $_));
}

function data(array $_ = []) {
    $path = $_['path'] ?? null;
    $query = (array) ($_['query'] ?? []);
    $task = $_['task'] ?? 'set';
    $token = $_['token'] ?? null;
    $type = $_['type'] ?? 'data';
    return \x\panel\type(\array_replace_recursive([
        'lot' => [
            'bar' => [
                // `bar`
                'lot' => [
                    0 => [
                        // `links`
                        'lot' => [
                            'folder' => ['skip' => true],
                            'link' => [
                                'skip' => false,
                                'url' => [
                                    'part' => 1,
                                    'path' => 'get' === $task && $path ? \dirname($path) : $path,
                                    'query' => \x\panel\_query_set(['tab' => ['data']]),
                                    'task' => 'get'
                                ]
                            ],
                            'set' => [
                                'description' => ['New %s', 'Data'],
                                'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                'skip' => 'set' === $task,
                                'stack' => 10.5,
                                'title' => false,
                                'url' => [
                                    'part' => 0,
                                    'path' => $path ? \dirname($path) : $path,
                                    'query' => \x\panel\_query_set(['type' => $type]),
                                    'task' => 'set'
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
                                        'gap' => false,
                                        // `tabs`
                                        'lot' => [
                                            'data' => [
                                                // `tab`
                                                'lot' => [
                                                    'fields' => [
                                                        // `fields`
                                                        'lot' => [
                                                            'content' => [
                                                                'height' => true,
                                                                'name' => 'data[content]',
                                                                'stack' => 10,
                                                                'type' => 'source',
                                                                'value' => null,
                                                                'width' => true
                                                            ],
                                                            'name' => [
                                                                'focus' => true,
                                                                'name' => 'data[name]',
                                                                'stack' => 20,
                                                                'type' => 'name',
                                                                'unit' => '.data',
                                                                'value' => null,
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
                                        // `fields`
                                        'lot' => [
                                            0 => [
                                                // `field`
                                                'lot' => [
                                                    'tasks' => [
                                                        // `tasks/button`
                                                        'lot' => [
                                                            'set' => [
                                                                'name' => false,
                                                                'stack' => 10,
                                                                'title' => 'get' === $task ? 'Update' : 'Create',
                                                                'type' => 'submit'
                                                            ],
                                                            'let' => [
                                                                'name' => 'task',
                                                                'skip' => 'set' === $task,
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
                            'kick' => $query['kick'] ?? null,
                            'query' => ['tab' => 'get' === $task || 'set' === $task ? ['data'] : null],
                            'token' => $token,
                            'type' => $type
                        ]
                    ]
                ]
            ]
        ],
        'status' => 200,
        'task' => $task,
        'type' => $type
    ], $_));
}

function file(array $_ = []) {
    $path = $_['path'] ?? null;
    $query = (array) ($_['query'] ?? []);
    $task = $_['task'] ?? 'set';
    $token = $_['token'] ?? null;
    $type = $_['type'] ?? 'file';
    return \x\panel\type(\array_replace_recursive([
        'lot' => [
            'bar' => [
                // `bar`
                'lot' => [
                    0 => [
                        // `links`
                        'lot' => [
                            'folder' => ['skip' => true],
                            'link' => [
                                'skip' => false,
                                'url' => [
                                    'part' => 1,
                                    'path' => 'get' === $task && $path ? \dirname($path) : $path,
                                    'query' => \x\panel\_query_set(),
                                    'task' => 'get'
                                ]
                            ],
                            'set' => [
                                'description' => ['New %s', 'File'],
                                'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                'skip' => 'set' === $task,
                                'stack' => 10.5,
                                'title' => false,
                                'url' => [
                                    'part' => 0,
                                    'path' => 'get' === $task && $path ? \dirname($path) : $path,
                                    'query' => \x\panel\_query_set(['type' => $type]),
                                    'task' => 'set'
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
                                            'file' => [
                                                // `tab`
                                                'lot' => [
                                                    'fields' => [
                                                        // `fields`
                                                        'lot' => [
                                                            'content' => [
                                                                'height' => true,
                                                                'name' => 'file[content]',
                                                                'skip' => false,
                                                                'stack' => 10,
                                                                'type' => 'source',
                                                                'value' => null,
                                                                'width' => true
                                                            ],
                                                            'name' => [
                                                                'focus' => true,
                                                                'name' => 'file[name]',
                                                                'stack' => 20,
                                                                'type' => 'name',
                                                                'value' => null,
                                                                'width' => true
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
                                        // `fields`
                                        'lot' => [
                                            0 => [
                                                // `field`
                                                'lot' => [
                                                    'tasks' => [
                                                        // `tasks/button`
                                                        'lot' => [
                                                            'set' => [
                                                                'name' => 'task',
                                                                'stack' => 10,
                                                                'title' => 'get' === $task ? 'Update' : 'Create',
                                                                'type' => 'submit',
                                                                'value' => $task
                                                            ],
                                                            'let' => [
                                                                'name' => 'task',
                                                                'skip' => 'set' === $task,
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
                            'kick' => $query['kick'] ?? null,
                            'token' => $token,
                            'type' => $type
                        ]
                    ]
                ]
            ]
        ],
        'status' => 200,
        'task' => $task,
        'type' => $type
    ], $_));
}

function files(array $_ = []) {
    $chunk = $_['chunk'] ?? 20;
    $count = $_['count'] ?? 0;
    $part = $_['part'] ?? 1;
    $path = $_['path'] ?? null;
    $query = (array) ($_['query'] ?? []);
    $task = $_['task'] ?? 'get';
    $type = $_['type'] ?? 'files';
    return \x\panel\type(\array_replace_recursive([
        'chunk' => $chunk,
        'count' => $count,
        'lot' => [
            'desk' => [
                // `desk`
                'lot' => [
                    'form' => [
                        // `form/post`
                        'lot' => [
                            0 => [
                                // `section`
                                'lot' => [
                                    'tasks' => [
                                        // `tasks/button`
                                        'lot' => [
                                            'blob' => [
                                                'description' => 'Upload',
                                                'icon' => 'M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z',
                                                'stack' => 10,
                                                'title' => false,
                                                'type' => 'link',
                                                'url' => [
                                                    'part' => 0,
                                                    'query' => \x\panel\_query_set(['type' => 'blob']),
                                                    'task' => 'set'
                                                ]
                                            ],
                                            'file' => [
                                                'description' => ['New %s', 'File'],
                                                'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                                'stack' => 20,
                                                'type' => 'link',
                                                'url' => [
                                                    'part' => 0,
                                                    'query' => \x\panel\_query_set(['type' => 'file']),
                                                    'task' => 'set'
                                                ]
                                            ],
                                            'folder' => [
                                                'description' => ['New %s', 'Folder'],
                                                'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                                'stack' => 30,
                                                'type' => 'link',
                                                'url' => [
                                                    'part' => 0,
                                                    'query' => \x\panel\_query_set(['type' => 'folder']),
                                                    'task' => 'set'
                                                ]
                                            ]
                                        ],
                                        'stack' => 10,
                                        'type' => 'tasks/button'
                                    ]
                                ]
                            ],
                            1 => [
                                // `section`
                                'lot' => [
                                    'tabs' => [
                                        // `tabs`
                                        'lot' => [
                                            'files' => [
                                                // `tab`
                                                'lot' => [
                                                    'files' => [
                                                        // `files`
                                                        'lot' => [],
                                                        'stack' => 10,
                                                        'type' => 'files'
                                                    ],
                                                    'pager' => [
                                                        'chunk' => $chunk,
                                                        'count' => $count,
                                                        'current' => $part,
                                                        'stack' => 20,
                                                        'type' => 'pager'
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
                ]
            ]
        ],
        'part' => $part,
        'status' => 200,
        'task' => $task,
        'type' => $type
    ], $_));
}

function folder(array $_ = []) {
    $path = $_['path'] ?? null;
    $query = (array) ($_['query'] ?? []);
    $task = $_['task'] ?? 'set';
    $token = $_['token'] ?? null;
    $type = $_['type'] ?? 'folder';
    return \x\panel\type(\array_replace_recursive([
        'lot' => [
            'bar' => [
                // `bar`
                'lot' => [
                    0 => [
                        // `links`
                        'lot' => [
                            'folder' => ['skip' => true],
                            'link' => [
                                'skip' => false,
                                'url' => [
                                    'part' => 1,
                                    'path' => 'get' === $task && $path ? \dirname($path) : $path,
                                    'query' => \x\panel\_query_set(),
                                    'task' => 'get'
                                ]
                            ],
                            'set' => [
                                'description' => ['New %s', 'Folder'],
                                'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                'skip' => 'set' === $task,
                                'stack' => 10.5,
                                'title' => false,
                                'url' => [
                                    'part' => 0,
                                    'query' => \x\panel\_query_set(['type' => $type]),
                                    'task' => 'set'
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
                                            'folder' => [
                                                // `tab`
                                                'lot' => [
                                                    'fields' => [
                                                        // `fields`
                                                        'lot' => [
                                                            'name' => [
                                                                'focus' => true,
                                                                'name' => 'folder[name]',
                                                                'stack' => 10,
                                                                'type' => 'set' === $task ? 'path' : 'name',
                                                                'value' => null,
                                                                'width' => true,
                                                                'x' => 'get' === $task ? false : null
                                                            ],
                                                            'options' => [
                                                                'flex' => false,
                                                                'lot' => [
                                                                    'kick' => ['Redirect to %s', ['folder']]
                                                                ],
                                                                'stack' => 20,
                                                                'title' => "",
                                                                'type' => 'items',
                                                                'value' => 'set' === $task ? ['kick' => true] : []
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
                                        // `fields`
                                        'lot' => [
                                            0 => [
                                                // `field`
                                                'lot' => [
                                                    'tasks' => [
                                                        // `tasks/button`
                                                        'lot' => [
                                                            'set' => [
                                                                'name' => 'task',
                                                                'stack' => 10,
                                                                'title' => 'get' === $task ? 'Update' : 'Create',
                                                                'type' => 'submit',
                                                                'value' => $task
                                                            ],
                                                            'let' => [
                                                                'name' => 'task',
                                                                'skip' => 'set' === $task,
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
                                        'stack' => 10,
                                        'type' => 'fields'
                                    ]
                                ]
                            ]
                        ],
                        'values' => [
                            'kick' => $query['kick'] ?? null,
                            'token' => $token,
                            'type' => $type
                        ]
                    ]
                ]
            ]
        ],
        'status' => 200,
        'task' => $task,
        'type' => $type
    ], $_));
}

function folders(array $_ = []) {
    return \x\panel\type\files(\array_replace_recursive(['type' => 'folders'], $_));
}

function page(array $_ = []) {
    $path = $_['path'] ?? null;
    $query = (array) ($_['query'] ?? []);
    $task = $_['task'] ?? 'set';
    $token = $_['token'] ?? null;
    $type = $_['type'] ?? 'page';
    $x = $path ? \pathinfo($path, \PATHINFO_EXTENSION) : null;
    return \x\panel\type(\array_replace_recursive([
        'lot' => [
            'bar' => [
                // `bar`
                'lot' => [
                    0 => [
                        // `links`
                        'lot' => [
                            'folder' => ['skip' => true],
                            'link' => [
                                'skip' => false,
                                'url' => [
                                    'part' => 1,
                                    'path' => 'get' === $task && $path ? \dirname($path) : $path,
                                    'query' => \x\panel\_query_set(),
                                    'task' => 'get'
                                ]
                            ],
                            'set' => [
                                'description' => ['New %s', 'Page'],
                                'icon' => 'M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z',
                                'skip' => 'set' === $task,
                                'stack' => 10.5,
                                'title' => false,
                                'url' => [
                                    'path' => 'get' === $task && $path ? \dirname($path) : $path,
                                    'query' => \x\panel\_query_set(['type' => $type]),
                                    'task' => 'set'
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
                                        'gap' => false,
                                        // `tabs`
                                        'lot' => [
                                            'page' => [
                                                // `tab`
                                                'lot' => [
                                                    'fields' => [
                                                        // `fields`
                                                        'lot' => [
                                                            'title' => [
                                                                'focus' => true,
                                                                'name' => 'page[title]',
                                                                'stack' => 10,
                                                                'type' => 'title',
                                                                'value' => null,
                                                                'width' => true
                                                            ],
                                                            'name' => [
                                                                'name' => 'page[name]',
                                                                'skip' => 'set' === $task,
                                                                'stack' => 20,
                                                                'title' => 'Slug',
                                                                'type' => 'name',
                                                                'value' => null,
                                                                'width' => true,
                                                                'x' => false
                                                            ],
                                                            'content' => [
                                                                'height' => true,
                                                                'name' => 'page[content]',
                                                                'stack' => 30,
                                                                'type' => 'source',
                                                                'value' => null,
                                                                'width' => true
                                                            ],
                                                            'description' => [
                                                                'name' => 'page[description]',
                                                                'stack' => 40,
                                                                'type' => 'description',
                                                                'value' => null,
                                                                'width' => true
                                                            ],
                                                            'author' => [
                                                                'name' => 'page[author]',
                                                                'stack' => 50,
                                                                'type' => 'hidden',
                                                                'value' => null
                                                            ],
                                                            'type' => [
                                                                'lot' => ['HTML' => 'HTML'],
                                                                'name' => 'page[type]',
                                                                'stack' => 60,
                                                                'type' => 'item',
                                                                'value' => 'HTML'
                                                            ]
                                                        ],
                                                        'stack' => 10,
                                                        'type' => 'fields'
                                                    ]
                                                ],
                                                'stack' => 10
                                            ],
                                            'data' => [
                                                // `tab`
                                                'lot' => [
                                                    'fields' => [
                                                        // `fields`
                                                        'lot' => [
                                                            'link' => [
                                                                'name' => 'page[link]',
                                                                'stack' => 10,
                                                                'type' => 'link',
                                                                'value' => null,
                                                                'width' => true
                                                            ],
                                                            'time' => [
                                                                'name' => 'data[time]',
                                                                'skip' => 'set' === $task,
                                                                'stack' => 20,
                                                                'type' => 'date-time',
                                                                'value' => null
                                                            ],
                                                            'files' => [
                                                                // `field`
                                                                'lot' => [
                                                                    'files' => [
                                                                        // `files`
                                                                        'lot' => [],
                                                                        'stack' => 10,
                                                                        'type' => 'files'
                                                                    ],
                                                                    'tasks' => [
                                                                        '0' => 'p',
                                                                        // `tasks/link`
                                                                        'lot' => [
                                                                            'set' => [
                                                                                'description' => ['New %s', 'Data'],
                                                                                'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                                                                'stack' => 10,
                                                                                'title' => 'Data',
                                                                                'url' => [
                                                                                    'part' => 0,
                                                                                    'path' => $path ? \dirname($path) . '/' . \pathinfo($path, \PATHINFO_FILENAME) : $path,
                                                                                    'query' => \x\panel\_query_set(['type' => 'data']),
                                                                                    'task' => 'set'
                                                                                ]
                                                                            ]
                                                                        ],
                                                                        'stack' => 20,
                                                                        'type' => 'tasks/link'
                                                                    ]
                                                                ],
                                                                'skip' => 'set' === $task,
                                                                'stack' => 100,
                                                                'title' => "",
                                                                'type' => 'field'
                                                            ]
                                                        ],
                                                        'stack' => 10,
                                                        'type' => 'fields'
                                                    ]
                                                ],
                                                'stack' => 20
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
                                                                'description' => $x ? ['Update as %s', ucfirst($x)] : null,
                                                                'name' => 'page[x]',
                                                                'skip' => 'set' === $task,
                                                                'stack' => 10,
                                                                'title' => 'Update',
                                                                'type' => 'submit',
                                                                'value' => $x
                                                            ],
                                                            'page' => [
                                                                'name' => 'page[x]',
                                                                'skip' => 'page' === $x,
                                                                'stack' => 20,
                                                                'title' => 'Publish',
                                                                'type' => 'submit',
                                                                'value' => 'page'
                                                            ],
                                                            'draft' => [
                                                                'description' => $x ? ['Save as %s', 'Draft'] : null,
                                                                'name' => 'page[x]',
                                                                'skip' => 'draft' === $x,
                                                                'stack' => 30,
                                                                'title' => 'Save',
                                                                'type' => 'submit',
                                                                'value' => 'draft'
                                                            ],
                                                            'archive' => [
                                                                'description' => $x ? ['Save as %s', 'Archive'] : null,
                                                                'name' => 'page[x]',
                                                                'skip' => 'archive' === $x || 'set' === $task,
                                                                'stack' => 40,
                                                                'title' => 'Archive',
                                                                'type' => 'submit',
                                                                'value' => 'archive'
                                                            ],
                                                            'let' => [
                                                                'name' => 'task',
                                                                'skip' => 'set' === $task,
                                                                'stack' => 50,
                                                                'title' => 'Delete',
                                                                'value' => 'let',
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
                            'kick' => $query['kick'] ?? null,
                            'token' => $token,
                            'type' => $type
                        ]
                    ]
                ]
            ]
        ],
        'status' => 200,
        'task' => $task,
        'type' => $type
    ], $_));
}

function pages(array $_ = []) {
    $chunk = $_['chunk'] ?? 20;
    $count = $_['count'] ?? 0;
    $part = $_['part'] ?? 1;
    $path = $_['path'] ?? null;
    $query = (array) ($_['query'] ?? []);
    $task = $_['task'] ?? 'get';
    $type = $_['type'] ?? 'pages';
    return \x\panel\type(\array_replace_recursive([
        'chunk' => $chunk,
        'count' => $count,
        'lot' => [
            'desk' => [
                // `desk`
                'lot' => [
                    'form' => [
                        // `form/post`
                        'lot' => [
                            0 => [
                                // `section`
                                'lot' => [
                                    'tasks' => [
                                        // `tasks/button`
                                        'lot' => [
                                            'parent' => [
                                                'description' => ['Go to %s', 'Parent'],
                                                'icon' => 'M13,20H11V8L5.5,13.5L4.08,12.08L12,4.16L19.92,12.08L18.5,13.5L13,8V20Z',
                                                'skip' => !$path || false === \strpos($path, '/'),
                                                'stack' => 10,
                                                'title' => false,
                                                'type' => 'link',
                                                'url' => [
                                                    'part' => 1,
                                                    'path' => $path ? \dirname($path) : $path,
                                                    'query' => \x\panel\_query_set(),
                                                    'task' => 'get'
                                                ]
                                            ],
                                            'blob' => [
                                                'description' => 'Upload',
                                                'icon' => 'M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z',
                                                'skip' => true,
                                                'stack' => 20,
                                                'title' => false,
                                                'type' => 'link',
                                                'url' => [
                                                    'part' => 0,
                                                    'query' => \x\panel\_query_set(['type' => 'blob']),
                                                    'task' => 'set'
                                                ]
                                            ],
                                            'page' => [
                                                'description' => ['New %s', 'Page'],
                                                'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                                'stack' => 30,
                                                'type' => 'link',
                                                'url' => [
                                                    'part' => 0,
                                                    'query' => \x\panel\_query_set(['type' => 'page']),
                                                    'task' => 'set'
                                                ]
                                            ],
                                            'data' => [
                                                'description' => ['New %s', 'Data'],
                                                'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                                'skip' => !$path || false === \strpos($path, '/'),
                                                'stack' => 40,
                                                'type' => 'link',
                                                'url' => [
                                                    'part' => 0,
                                                    'query' => \x\panel\_query_set(['type' => 'data']),
                                                    'task' => 'set'
                                                ]
                                            ]
                                        ],
                                        'stack' => 10,
                                        'type' => 'tasks/button'
                                    ]
                                ]
                            ],
                            1 => [
                                // `section`
                                'lot' => [
                                    'tabs' => [
                                        'gap' => false,
                                        // `tabs`
                                        'lot' => [
                                            'pages' => [
                                                // `tab`
                                                'lot' => [
                                                    'pages' => [
                                                        // `pages`
                                                        'lot' => [],
                                                        'stack' => 10,
                                                        'type' => 'pages'
                                                    ],
                                                    'pager' => [
                                                        'chunk' => $chunk,
                                                        'count' => $count,
                                                        'current' => $part,
                                                        'stack' => 20,
                                                        'type' => 'pager'
                                                    ]
                                                ],
                                                'stack' => 10
                                            ],
                                            'data' => [
                                                // `tab`
                                                'lot' => [
                                                    'data' => [
                                                        // `files`
                                                        'lot' => [],
                                                        'stack' => 10,
                                                        'type' => 'files'
                                                    ]
                                                ],
                                                'skip' => !$path || false === \strpos($path, '/'),
                                                'stack' => 20
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
        'part' => $part,
        'status' => 200,
        'task' => $task,
        'type' => $type
    ], $_));
}

function state(array $_ = []) {
    $path = $_['path'] ?? null;
    $query = (array) ($_['query'] ?? []);
    $task = $_['task'] ?? 'get';
    $token = $_['token'] ?? null;
    $type = $_['type'] ?? 'state';
    return \x\panel\type(\array_replace_recursive([
        'lot' => [
            'bar' => [
                // `bar`
                'lot' => [
                    0 => [
                        // `links`
                        'lot' => [
                            'link' => [
                                'skip' => false,
                                'url' => [
                                    'part' => 1,
                                    'path' => 'get' === $task && $path ? \dirname($path) : $path,
                                    'query' => \x\panel\_query_set()
                                ]
                            ],
                            'folder' => ['skip' => true]
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
                                        'gap' => false,
                                        // `tabs`
                                        'lot' => [
                                            'file' => [
                                                // `tab`
                                                'lot' => [
                                                    'fields' => [
                                                        // `fields`
                                                        'lot' => [],
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
                                        // `fields`
                                        'lot' => [
                                            0 => [
                                                // `field`
                                                'lot' => [
                                                    'tasks' => [
                                                        // `tasks/button`
                                                        'lot' => [
                                                            'set' => [
                                                                'name' => false,
                                                                'stack' => 10,
                                                                'title' => 'get' === $task ? 'Update' : 'Create',
                                                                'type' => 'submit'
                                                            ],
                                                            'let' => ['skip' => true]
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
                            'file' => [
                                'name' => null,
                                'seal' => '0600'
                            ],
                            'kick' => $query['kick'] ?? null,
                            'token' => $token,
                            'type' => $type
                        ]
                    ]
                ]
            ]
        ],
        'status' => 200,
        'task' => $task,
        'type' => $type
    ], $_));
}

function void(array $_ = []) {
    return \x\panel\type(\array_replace_recursive([
        'lot' => [
            'bar' => ['skip' => true],
            'desk' => [
                'lot' => [
                    'alert' => [
                        '2' => ['role' => 'status'],
                        'icon' => 'M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M7,9.5C7,8.7 7.7,8 8.5,8C9.3,8 10,8.7 10,9.5C10,10.3 9.3,11 8.5,11C7.7,11 7,10.3 7,9.5M14.77,17.23C14.32,16.5 13.25,16 12,16C10.75,16 9.68,16.5 9.23,17.23L7.81,15.81C8.71,14.72 10.25,14 12,14C13.75,14 15.29,14.72 16.19,15.81L14.77,17.23M15.5,11C14.7,11 14,10.3 14,9.5C14,8.7 14.7,8 15.5,8C16.3,8 17,8.7 17,9.5C17,10.3 16.3,11 15.5,11Z',
                        'level' => 0,
                        'stack' => 10,
                        'type' => 'title'
                    ],
                    'form' => ['skip' => true]
                ]
            ]
        ],
        'status' => 200,
        'type' => 'void'
    ], $_));
}

require __DIR__ . \D . 'type' . \D . 'blob.php';
require __DIR__ . \D . 'type' . \D . 'data.php';
require __DIR__ . \D . 'type' . \D . 'file.php';
require __DIR__ . \D . 'type' . \D . 'files.php';
require __DIR__ . \D . 'type' . \D . 'folder.php';
require __DIR__ . \D . 'type' . \D . 'folders.php';
require __DIR__ . \D . 'type' . \D . 'page.php';
require __DIR__ . \D . 'type' . \D . 'pages.php';
require __DIR__ . \D . 'type' . \D . 'state.php';