<?php

$options = [];
if (extension_loaded('zip')) {
    $options['extract'] = 'Extract package immediately after uploaded.';
    $options['let'] = 'Delete package immediately after extracted.';
}

return [
    'bar' => [
        // type: Bar
        'lot' => [
            // type: List
            0 => [
                'lot' => [
                    'folder' => ['hidden' => true],
                    'link' => [
                        'url' => $url . $_['/'] . '::g::' . ('g' === $_['task'] ? dirname($_['path']) : $_['path']) . '/1' . $url->query('&', ['layout' => false, 'tab' => false]) . $url->hash,
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
        // type: Desk
        'lot' => [
            'form' => [
                // type: Form.Post
                'lot' => [
                    'fields' => [
                        'type' => 'Fields',
                        'lot' => [ // Hidden field(s)
                            'token' => [
                                'type' => 'Hidden',
                                'value' => $_['token']
                            ]
                        ],
                        'stack' => -1
                    ],
                    1 => [
                        // type: Section
                        'lot' => [
                            'tabs' => [
                                // type: Tabs
                                'lot' => [
                                    'blob' => [
                                        'title' => 'Upload',
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => [
                                                    'blob' => [
                                                        'title' => 'File',
                                                        'description' => ['Maximum file size allowed to upload is %s.', File::sizer(File::$state['size'][1])],
                                                        'type' => 'Blob',
                                                        'focus' => true,
                                                        'stack' => 10
                                                    ],
                                                    'o' => [
                                                        'title' => "",
                                                        'type' => 'Items',
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
                        // type: Section
                        'lot' => [
                            'fields' => [
                                'type' => 'Fields',
                                'lot' => [
                                    0 => [
                                        'title' => "",
                                        'type' => 'Field',
                                        'lot' => [
                                            'tasks' => [
                                                'type' => 'Tasks.Button',
                                                'lot' => [
                                                    's' => [
                                                        'title' => 'Upload',
                                                        'description' => ['Upload to %s', _\lot\x\panel\h\path($_['f'])],
                                                        'type' => 'Submit',
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
