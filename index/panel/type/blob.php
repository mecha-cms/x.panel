<?php

$options = [];
if (extension_loaded('zip')) {
    $options['extract'] = [
        'name' => 'options[zip][extract]',
        'title' => 'Extract package automatically after upload.'
    ];
    $options['keep'] = [
        'name' => 'options[zip][keep]',
        'title' => 'Keep package after extract.'
    ];
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
                    'url' => [
                        'part' => 1,
                        'path' => 'get' === $_['task'] ? dirname($_['path']) : $_['path'],
                        'query' => x\panel\_query_set(),
                        'task' => 'get'
                    ]
                ],
                'set' => [
                    'description' => ['New %s', 'File'],
                    'icon' => 'M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z',
                    'skip' => 'set' === $_['task'],
                    'stack' => 10.5,
                    'title' => false,
                    'url' => [
                        'part' => 0,
                        'query' => x\panel\_query_set(['type' => 'blob']),
                        'task' => 'set'
                    ]
                ]
            ]
        ]
    ]
];

// <https://www.php.net/manual/en/function.ini-get.php>
$bytes = static function (string $v) {
    $i = intval($v = trim($v));
    switch (strtolower(substr($v, -1))) {
        case 'g':
            $i *= 1024;
        case 'm':
            $i *= 1024;
        case 'k':
            $i *= 1024;
    }
    return $i;
};

if (is_string($upload_max_size = ini_get('upload_max_filesize'))) {
    $upload_max_size = $bytes($upload_max_size);
}

// Compare with value from `.\lot\x\panel\state\file\size.php` and prefers the smaller one!
$upload_max_size = min($upload_max_size, $state->x->panel->guard->file->size[1]);

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
                            'lot' => [
                                'blob' => [
                                    'lot' => [
                                        'fields' => [
                                            'lot' => [
                                                'blob' => [
                                                    'description' => ['Maximum file size allowed to upload is %s.', size((float) $upload_max_size)],
                                                    'focus' => true,
                                                    'name' => 'blobs',
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
            ],
            'values' => [
                'token' => $_['token'],
                'type' => $_['type']
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