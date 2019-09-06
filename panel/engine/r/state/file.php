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
                                                    'token' => [
                                                        'type' => 'Hidden',
                                                        'value' => $PANEL['token'],
                                                    ],
                                                    'view' => [
                                                        'type' => 'Hidden',
                                                        'value' => $_GET['view'] ?? null
                                                    ],
                                                    'file[content]' => [
                                                        'title' => $language->content,
                                                        'type' => 'Source',
                                                        'placeholder' => $PANEL['data']['file']['content'] ?? $language->fieldDescriptionContent,
                                                        'value' => $PANEL['data']['file']['content'] ?? null,
                                                        'width' => true,
                                                        'height' => true,
                                                        'stack' => 10
                                                    ],
                                                    'file[name]' => [
                                                        'title' => $language->name,
                                                        'type' => 'Text',
                                                        'placeholder' => $PANEL['data']['file']['name'] ?? $language->fieldDescriptionName,
                                                        'value' => $PANEL['data']['file']['name'] ?? null,
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
                                                        'placeholder' => $PANEL['data']['file']['folder'] ?? $language->fieldDescriptionFolder,
                                                        'value' => $PANEL['data']['file']['folder'] ?? null,
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
                    ],
                    2 => [
                        // type: Section
                        'lot' => [
                            'fields' => [
                                'type' => 'Fields',
                                'lot' => [
                                    0 => [
                                        'type' => 'Field',
                                        'title' => "",
                                        'lot' => [
                                            'tasks' => [
                                                'type' => 'Tasks.Button',
                                                'lot' => [
                                                    0 => [
                                                        'type' => 'Submit',
                                                        'title' => $language->doSave
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