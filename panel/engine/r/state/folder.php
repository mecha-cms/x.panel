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
                    'folder' => [
                        'icon' => 'M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z',
                        'url' => $url . $_['/'] . '::g::' . ($_['task'] === 'g' ? dirname($_['path']) : $_['path']) . '/1' . $url->query('&', ['content' => false, 'tab' => false]) . $url->hash,
                        'lot' => false // Disable sub-menu(s)
                    ],
                    's' => [
                        'hidden' => $_['task'] === 's',
                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                        'title' => false,
                        'description' => ['New %s', 'folder'],
                        'url' => str_replace('::g::', '::s::', dirname($url->clean)) . $url->query('&', ['content' => 'folder', 'tab' => false]) . $url->hash,
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
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => [
                                                    'token' => [
                                                        'type' => 'Hidden',
                                                        'value' => $_['token']
                                                    ],
                                                    'name' => [
                                                        'type' => 'Text',
                                                        'pattern' => "^[_.]?[a-z\\d]+([_.-][a-z\\d]+)*([\\\\/][_.]?[a-z\\d]+([_.-][a-z\\d]+)*)*$",
                                                        'focus' => true,
                                                        'name' => 'folder[name]',
                                                        'alt' => $_['task'] === 'g' ? ($name ?? "foo\\bar\\baz") : "foo\\bar\\baz",
                                                        'value' => $name,
                                                        'width' => true,
                                                        'stack' => 10
                                                    ],
                                                    'options' => [
                                                        'title' => "",
                                                        'type' => 'Items',
                                                        'block' => true,
                                                        'name' => 'folder',
                                                        'value' => $_['task'] === 's' ? ['kick' => 1] : [],
                                                        'lot' => [
                                                            'kick' => 'Redirect to folder'
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
                                        'title' => "",
                                        'lot' => [
                                            'tasks' => [
                                                'type' => 'Tasks.Button',
                                                'lot' => [
                                                    's' => [
                                                        'title' => $_['task'] === 'g' ? 'Update' : 'Create',
                                                        'description' => ['Create in %s', _\lot\x\panel\h\path($_['f'])],
                                                        'type' => 'Submit',
                                                        'name' => false,
                                                        'stack' => 10
                                                    ],
                                                    'l' => [
                                                        'title' => 'Delete',
                                                        'type' => 'Link',
                                                        'url' => str_replace('::g::', '::l::', $url->clean . $url->query('&', ['content' => 'folder', 'token' => $_['token']])),
                                                        'hidden' => $_['task'] === 's',
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