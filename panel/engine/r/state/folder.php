<?php

$name = $_['task'] === 'g' ? basename($_['f']) : "";

if ("" === $name) $name = null;

return [
    'bar' => [
        // type: Bar
        'lot' => [
            // type: List
            0 => [
                'lot' => [
                    's' => [
                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                        'title' => false,
                        'hidden' => $_['task'] === 's',
                        'description' => $language->doCreate . ' (' . $language->folder . ')',
                        'url' => str_replace('::g::', '::s::', dirname($url->clean)) . $url->query('&', ['content' => 'folder']) . $url->hash,
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
                    1 => [
                        // type: Section
                        'lot' => [
                            'tabs' => [
                                // type: Tabs
                                'lot' => [
                                    'folder' => [
                                        'title' => $language->folder,
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => [
                                                    'token' => [
                                                        'type' => 'Hidden',
                                                        'value' => $_['token']
                                                    ],
                                                    'c' => [
                                                        'type' => 'Hidden',
                                                        'value' => $_GET['content'] ?? 'folder'
                                                    ],
                                                    'name' => [
                                                        'title' => $language->name,
                                                        'type' => 'Text',
                                                        'alt' => $_['task'] === 'g' ? ($name ?? $language->fieldAltFolder) : $language->fieldAltFolder,
                                                        'pattern' => "^[_.]?[a-z\\d]+([_.-][a-z\\d]+)*([\\\\/][_.]?[a-z\\d]+([_.-][a-z\\d]+)*)*$",
                                                        'name' => 'folder[name]',
                                                        'value' => $name,
                                                        'width' => true,
                                                        'stack' => 10
                                                    ],
                                                    'options' => [
                                                        'title' => false,
                                                        'type' => 'Items',
                                                        'block' => true,
                                                        'name' => 'folder',
                                                        'value' => ['kick'],
                                                        'lot' => [
                                                            'kick' => $language->fieldDescriptionFolderKick
                                                        ],
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
                                        'type' => 'Field',
                                        'title' => false,
                                        'lot' => [
                                            'tasks' => [
                                                'type' => 'Tasks.Button',
                                                'lot' => [
                                                    's' => [
                                                        'type' => 'Submit',
                                                        'title' => $language->{$_['task'] === 'g' ? 'doUpdate' : 'doCreate'},
                                                        'name' => false,
                                                        'stack' => 10
                                                    ],
                                                    'l' => [
                                                        'type' => 'Link',
                                                        'hidden' => $_['task'] === 's',
                                                        'title' => $language->doDelete,
                                                        'url' => str_replace('::g::', '::l::', $url->clean . $url->query('&', ['content' => 'folder', 'token' => $_['token']])),
                                                        'stack' => 20
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