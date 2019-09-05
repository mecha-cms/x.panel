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
                                    'file' => [
                                        'title' => $language->file,
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => [
                                                    'file[content]' => [
                                                        'title' => $language->content,
                                                        'type' => 'Source',
                                                        'value' => "",
                                                        'width' => true,
                                                        'height' => true,
                                                        'stack' => 10
                                                    ],
                                                    'file[name]' => [
                                                        'title' => $language->name,
                                                        'type' => 'Text',
                                                        'value' => "",
                                                        'width' => true,
                                                        'stack' => 20
                                                    ]
                                                ],
                                                'stack' => 10
                                            ]
                                        ],
                                        'stack' => 10
                                    ],
                                    'folder' => [
                                        'title' => $language->folder,
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => [
                                                    'file[folder]' => [
                                                        'title' => $language->folder,
                                                        'type' => 'Text',
                                                        'value' => "",
                                                        'width' => true,
                                                        'stack' => 10
                                                    ]
                                                ],
                                                'stack' => 10
                                            ]
                                        ],
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
];