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
                                        'title' => 'From Array',
                                        'lot' => [
                                            'files' => [
                                                'type' => 'Files',
                                                'lot' => [
                                                    0 => [
                                                        'title' => 'Test Folder',
                                                        'type' => 'Folder',
                                                        'link' => '/'
                                                    ],
                                                    1 => [
                                                        'title' => 'Test File',
                                                        'type' => 'File',
                                                        'link' => '/'
                                                    ],
                                                    2 => [
                                                        'title' => 'Test Folder Disabled',
                                                        'type' => 'Folder',
                                                        'link' => '/',
                                                        'active' => false
                                                    ],
                                                    3 => [
                                                        'title' => 'Test File Disabled',
                                                        'type' => 'File',
                                                        'link' => '/',
                                                        'active' => false
                                                    ],
                                                    4 => [
                                                        'title' => 'Test Folder Active',
                                                        'type' => 'Folder',
                                                        'link' => '/',
                                                        'current' => true
                                                    ],
                                                    5 => [
                                                        'title' => 'Test File Active',
                                                        'type' => 'File',
                                                        'link' => '/',
                                                        'current' => true
                                                    ]
                                                ]
                                            ]
                                        ],
                                        'stack' => 10
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