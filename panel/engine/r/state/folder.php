<?php

if (is_file($f = $_['f']) && 'g' === $_['task']) {
    Alert::error('Path %s is not a %s.', ['<code>' . _\lot\x\panel\h\path($f) . '</code>', 'folder']);
    Guard::kick($url . $_['/'] . '/::g::/' . $_['path'] . $url->query('&', [
        'layout' => false
    ]) . $url->hash);
}

$name = 'g' === $_['task'] ? basename($_['f']) : "";

if ("" === $name) $name = null;

$trash = $_['trash'] ? date('Y-m-d-H-i-s') : false;

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
                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                        'title' => false,
                        'description' => ['New %s', 'Folder'],
                        'url' => str_replace('::g::', '::s::', dirname($url->clean)) . $url->query('&', ['layout' => 'folder', 'tab' => false]) . $url->hash,
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
                            ],
                            'seal' => [
                                'type' => 'hidden',
                                'name' => 'file[seal]',
                                'value' => '0600'
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
                                    'folder' => [
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'fields',
                                                'lot' => [
                                                    'token' => [
                                                        'type' => 'hidden',
                                                        'value' => $_['token']
                                                    ],
                                                    'name' => [
                                                        'type' => 'text',
                                                        'pattern' => "^[_.]?[a-z\\d]+([_.-][a-z\\d]+)*([\\\\/][_.]?[a-z\\d]+([_.-][a-z\\d]+)*)*$",
                                                        'focus' => true,
                                                        'name' => 'folder[name]',
                                                        'alt' => 'g' === $_['task'] ? ($name ?? ('s' === $_['task'] ? "foo\\bar\\baz" : 'foo-bar-baz')) : ('s' === $_['task'] ? "foo\\bar\\baz" : 'foo-bar-baz'),
                                                        'value' => $name,
                                                        'width' => true,
                                                        'stack' => 10
                                                    ],
                                                    'o' => [
                                                        'title' => "",
                                                        'type' => 'items',
                                                        'block' => true,
                                                        'value' => 's' === $_['task'] ? ['kick' => 1] : [],
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
                        // type: section
                        'lot' => [
                            'fields' => [
                                'type' => 'fields',
                                'lot' => [
                                    0 => [
                                        'type' => 'field',
                                        'title' => "",
                                        'lot' => [
                                            'tasks' => [
                                                'type' => 'tasks/button',
                                                'lot' => [
                                                    's' => [
                                                        'title' => 'g' === $_['task'] ? 'Update' : 'Create',
                                                        'description' => ['Create in %s', _\lot\x\panel\h\path($_['f'])],
                                                        'type' => 'submit',
                                                        'name' => false,
                                                        'stack' => 10
                                                    ],
                                                    'l' => [
                                                        'title' => 'Delete',
                                                        'type' => 'link',
                                                        'url' => str_replace('::g::', '::l::', $url->clean . $url->query('&', [
                                                            'layout' => 'folder',
                                                            'token' => $_['token'],
                                                            'trash' => $trash
                                                        ])),
                                                        'hidden' => 's' === $_['task'],
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
