<?php

return [
    'desk' => [
        // type: Desk
        'lot' => [
            'form' => [
                // type: Form.Post
                'lot' => [
                    1 => [
                        // type: Section
                        'lot' => [
                            'tabs' => [
                                // type: Tabs
                                'lot' => [
                                    0 => [
                                        'title' => 'From Path',
                                        'lot' => [
                                            'files' => [
                                                'type' => 'Files',
                                                'from' => PAGE,
                                                'chunk' => 10,
                                                'current' => $url['i']
                                            ]
                                        ],
                                        'stack' => 10
                                    ],
                                    1 => [
                                        'title' => 'From Array 1',
                                        'lot' => [
                                            'files' => [
                                                'type' => 'Files',
                                                'lot' => [
                                                    0 => 'foo/bar/baz-1.txt',
                                                    1 => 'foo/bar/baz-2.txt',
                                                    2 => 'foo/bar/baz-3.txt'
                                                ]
                                            ]
                                        ],
                                        'stack' => 20
                                    ],
                                    2 => [
                                        'title' => 'From Array 2',
                                        'lot' => [
                                            'files' => [
                                                'type' => 'Files',
                                                'lot' => [
                                                    0 => [
                                                        'type' => 'Folder',
                                                        'title' => 'Test Folder',
                                                        'link' => '/'
                                                    ],
                                                    1 => [
                                                        'type' => 'File',
                                                        'title' => 'Test File',
                                                        'link' => '/'
                                                    ],
                                                    2 => [
                                                        'type' => 'Folder',
                                                        'active' => false,
                                                        'title' => 'Test Folder Disabled',
                                                        'link' => '/'
                                                    ],
                                                    3 => [
                                                        'type' => 'File',
                                                        'active' => false,
                                                        'title' => 'Test File Disabled',
                                                        'link' => '/'
                                                    ],
                                                    4 => [
                                                        'type' => 'Folder',
                                                        'current' => true,
                                                        'title' => 'Test Folder Active',
                                                        'link' => '/'
                                                    ],
                                                    5 => [
                                                        'type' => 'File',
                                                        'current' => true,
                                                        'title' => 'Test File Active',
                                                        'link' => '/'
                                                    ]
                                                ]
                                            ]
                                        ],
                                        'stack' => 30
                                    ],
                                    3 => [
                                        'title' => 'From Array Mixed',
                                        'lot' => [
                                            'files' => [
                                                'type' => 'Files',
                                                'lot' => [
                                                    0 => 'foo/bar/baz-1.txt',
                                                    1 => 'foo/bar/baz-2.txt',
                                                    2 => [
                                                        'type' => 'File',
                                                        'title' => 'Test File',
                                                        'link' => '/'
                                                    ]
                                                ]
                                            ]
                                        ],
                                        'stack' => 40
                                    ]
                                ]
                            ]
                        ],
                        'stack' => 20
                    ]
                ],
                'stack' => 10
            ]
        ],
        'stack' => 20
    ]
];