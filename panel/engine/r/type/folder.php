<?php

if (is_file($f = $_['f']) && 'g' === $_['task']) {
    $_['alert']['error'][] = ['Path %s is not a %s.', ['<code>' . _\lot\x\panel\from\path($f) . '</code>', 'folder']];
    $_['kick'] = $url . $_['/'] . '/::g::/' . $_['path'] . $url->query('&', [
        'type' => false
    ]) . $url->hash;
    return $_;
}

$name = 'g' === $_['task'] ? basename($_['f']) : "";

if ("" === $name) $name = null;

$trash = $_['trash'] ? date('Y-m-d-H-i-s') : false;

$bar = [
    // type: bar
    'lot' => [
        // type: bar/menu
        0 => [
            'lot' => [
                'folder' => ['skip' => true],
                'link' => [
                    'url' => $url . $_['/'] . '/::g::/' . ('g' === $_['task'] ? dirname($_['path']) : $_['path']) . '/1' . $url->query('&', [
                        'tab' => false,
                        'type' => false
                    ]) . $url->hash,
                    'skip' => false
                ],
                's' => [
                    'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                    'title' => false,
                    'description' => ['New %s', 'Folder'],
                    'url' => strtr(dirname($url->clean), ['::g::' => '::s::']) . $url->query('&', [
                        'tab' => false,
                        'type' => 'folder'
                    ]) . $url->hash,
                    'skip' => 's' === $_['task'],
                    'stack' => 10.5
                ]
            ]
        ]
    ]
];

$desk = [
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
                                                    'type' => 'path',
                                                    'focus' => true,
                                                    'name' => 'folder[name]',
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
                                                    'description' => ['Create in %s', _\lot\x\panel\from\path($_['f'])],
                                                    'type' => 'submit',
                                                    'name' => false,
                                                    'stack' => 10
                                                ],
                                                'l' => [
                                                    'title' => 'Delete',
                                                    'type' => 'link',
                                                    'url' => strtr($url->clean . $url->query('&', [
                                                        'token' => $_['token'],
                                                        'trash' => $trash,
                                                        'type' => 'folder'
                                                    ]), ['::g::' => '::l::']),
                                                    'skip' => 's' === $_['task'],
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
];

return ($_ = array_replace_recursive($_, [
    'lot' => [
        'bar' => $bar,
        'desk' => $desk
    ]
]));
