<?php

$options = [];
if (extension_loaded('zip')) {
    $options['extract'] = 'Extract package immediately after uploaded.';
    $options['let'] = 'Delete package immediately after extracted.';
}

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
                    'description' => ['New %s', 'File'],
                    'icon' => 'M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z',
                    'skip' => 'set' === $_['task'],
                    'stack' => 10.5,
                    'title' => false,
                    'url' => x\panel\to\link([
                        'part' => 0,
                        'query' => [
                            'tab' => false,
                            'type' => 'blob'
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
                'type' => $_['type']
            ],
            'lot' => [
                1 => [
                    // `section`
                    'lot' => [
                        'tabs' => [
                            // `tabs`
                            'lot' => [
                                'blob' => [
                                    'lot' => [
                                        'fields' => [
                                            'lot' => [
                                                'blob' => [
                                                    'description' => ['Maximum file size allowed to upload is %s.', size((float) ($state->x->panel->guard->file->size[1] ?? ini_get('upload_max_filesize')))],
                                                    'focus' => true,
                                                    'stack' => 10,
                                                    'title' => 'File',
                                                    'type' => 'blobs',
                                                    'width' => true
                                                ],
                                                'options' => [
                                                    'block' => true,
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
                            'lot' => [
                                0 => [
                                    'lot' => [
                                        'tasks' => [
                                            'lot' => [
                                                'set' => [
                                                    'description' => ['Upload to %s', x\panel\from\path($_['folder'])],
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