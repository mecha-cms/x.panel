<?php

return [
    'desk' => [
        // type: desk
        'lot' => [
            'form' => [
                // type: form/post
                'lot' => [
                    1 => [
                        // type: section
                        'lot' => [
                            'tabs' => [
                                // type: tabs
                                'lot' => [
                                    0 => [
                                        'title' => 'From Array',
                                        'lot' => [
                                            'files' => [
                                                'type' => 'files',
                                                'lot' => [
                                                    0 => [
                                                        'title' => 'Test Folder',
                                                        'type' => 'folder',
                                                        'link' => '/'
                                                    ],
                                                    1 => [
                                                        'title' => 'Test File',
                                                        'type' => 'file',
                                                        'link' => '/'
                                                    ],
                                                    2 => [
                                                        'title' => 'Test Folder Disabled',
                                                        'type' => 'folder',
                                                        'link' => '/',
                                                        'active' => false
                                                    ],
                                                    3 => [
                                                        'title' => 'Test File Disabled',
                                                        'type' => 'file',
                                                        'link' => '/',
                                                        'active' => false
                                                    ],
                                                    4 => [
                                                        'title' => 'Test Folder Active',
                                                        'type' => 'folder',
                                                        'link' => '/',
                                                        'current' => true
                                                    ],
                                                    5 => [
                                                        'title' => 'Test File Active',
                                                        'type' => 'file',
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
