<?php

$items = [];
if (extension_loaded('zip')) {
    $items['extract'] = 'Extract package immediately after uploaded.';
    $items['let'] = 'Delete package immediately after extracted.';
}

$bar = [
    // type: bar
    'lot' => [
        // type: bar/menu
        0 => [
            'lot' => [
                'folder' => ['skip' => true],
                'link' => [
                    'url' => $url . $_['/'] . '/::g::/' . ('g' === $_['task'] ? dirname($_['path']) : $_['path']) . '/1' . $url->query('&', [
                        'tab' => false,
                        'type' => false
                    ]) . $url->hash,
                    'skip' => false
                ],
                's' => [
                    'icon' => 'M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z',
                    'title' => false,
                    'description' => ['New %s', 'File'],
                    'url' => strtr(dirname($url->clean), ['::g::' => '::s::']) . $url->query('&', [
                        'tab' => false,
                        'type' => 'blob'
                    ]) . $url->hash,
                    'skip' => 's' === $_['task'],
                    'stack' => 10.5
                ]
            ]
        ]
    ]
];

$desk = [
    // type: desk
    'lot' => [
        'form' => [
            // type: form/post
            'lot' => [
                'fields' => [
                    'type' => 'fields',
                    'lot' => [
                        'token' => [
                            'type' => 'hidden',
                            'value' => $_['token']
                        ],
                        'type' => [
                            'type' => 'hidden',
                            'value' => $_['type']
                        ]
                    ],
                    'stack' => -1
                ],
                1 => [
                    // type: section
                    'lot' => [
                        'tabs' => [
                            // type: tabs
                            'lot' => [
                                'blob' => [
                                    'title' => 'Upload',
                                    'lot' => [
                                        'fields' => [
                                            'type' => 'fields',
                                            'lot' => [
                                                'blob' => [
                                                    'title' => 'File',
                                                    'description' => ['Maximum file size allowed to upload is %s.', File::sizer(File::$state['size'][1])],
                                                    'type' => 'blobs',
                                                    'focus' => true,
                                                    'stack' => 10
                                                ],
                                                'o' => [
                                                    'title' => "",
                                                    'type' => 'items',
                                                    'lot' => $items,
                                                    'block' => true,
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
                    // type: section
                    'lot' => [
                        'fields' => [
                            'type' => 'fields',
                            'lot' => [
                                0 => [
                                    'title' => "",
                                    'type' => 'field',
                                    'lot' => [
                                        'tasks' => [
                                            'type' => 'tasks/button',
                                            'lot' => [
                                                's' => [
                                                    'title' => 'Upload',
                                                    'description' => ['Upload to %s', _\lot\x\panel\from\path($_['f'])],
                                                    'type' => 'submit',
                                                    'name' => false,
                                                    'stack' => 10
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
];

return ($_ = array_replace_recursive($_, [
    'lot' => [
        'bar' => $bar,
        'desk' => $desk
    ]
]));
