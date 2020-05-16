<?php

if (is_dir($f = $_['f']) && 'g' === $_['task']) {
    Alert::error('Path %s is not a %s.', ['<code>' . _\lot\x\panel\h\path($f) . '</code>', 'file']);
    Guard::kick($url . $_['/'] . '/::g::/' . $_['path'] . $url->query('&', [
        'layout' => false
    ]) . $url->hash);
}

$options = [];
if (extension_loaded('zip')) {
    $options['extract'] = 'Extract package immediately after uploaded.';
    $options['let'] = 'Delete package immediately after extracted.';
}

return [
    'bar' => [
        // type: bar
        'lot' => [
            // type: bar/menu
            0 => [
                'lot' => [
                    'folder' => ['hidden' => true],
                    'link' => [
                        'url' => $url . $_['/'] . '/::g::/' . ('g' === $_['task'] ? dirname($_['path']) : $_['path']) . '/1' . $url->query('&', ['layout' => false, 'tab' => false]) . $url->hash,
                        'hidden' => false
                    ],
                    's' => [
                        'hidden' => 's' === $_['task'],
                        'icon' => 'M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z',
                        'title' => false,
                        'description' => ['New %s', 'File'],
                        'url' => str_replace('::g::', '::s::', dirname($url->clean)) . $url->query('&', ['layout' => 'blob', 'tab' => false]) . $url->hash,
                        'stack' => 10.5
                    ]
                ]
            ]
        ]
    ],
    'desk' => [
        // type: desk
        'lot' => [
            'form' => [
                // type: form/post
                'lot' => [
                    'fields' => [
                        'type' => 'fields',
                        'lot' => [ // Hidden field(s)
                            'token' => [
                                'type' => 'hidden',
                                'value' => $_['token']
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
                                                        'lot' => $options,
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
                                                        'description' => ['Upload to %s', _\lot\x\panel\h\path($_['f'])],
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
    ]
];
