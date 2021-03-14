<?php

if (is_dir($f = $_['f']) && 'g' === $_['task']) {
    $_['alert']['error'][] = ['Path %s is not a %s.', ['<code>' . x\panel\from\path($f) . '</code>', 'file']];
    $_['kick'] = $_['/'] . '/::g::/' . $_['path'] . $url->query('&', [
        'type' => false
    ]) . $url->hash;
    return $_;
}

$name = is_file($f) ? pathinfo($f, PATHINFO_FILENAME) : null;
$content = $name ? file_get_contents($f) : null;

$path = 'g' === $_['task'] ? dirname($f) : $f;
$x = glob($path . '.{archive,draft,page}', GLOB_BRACE | GLOB_NOSORT);
$x = $x ? '.' . pathinfo($x[0], PATHINFO_EXTENSION) : '/1';

$trash = $_['trash'] ? date('Y-m-d-H-i-s') : false;

$bar = [
    // type: bar
    'lot' => [
        // type: bar/menu
        0 => [
            'lot' => [
                'folder' => ['skip' => true],
                'link' => [
                    'url' => $_['/'] . '/::g::/' . ('g' === $_['task'] ? dirname($_['path']) : $_['path']) . $x . $url->query('&', [
                        'tab' => ['data'],
                        'type' => false
                    ]) . $url->hash,
                    'skip' => false
                ],
                's' => [
                    'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                    'title' => false,
                    'description' => ['New %s', 'Data'],
                    'url' => strtr(dirname($url->clean), ['::g::' => '::s::']) . $url->query('&', [
                        'tab' => false,
                        'type' => 'data'
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
                    'lot' => [
                        'seal' => [
                            'type' => 'hidden',
                            'name' => 'file[seal]',
                            'value' => '0600'
                        ],
                        'token' => [
                            'type' => 'hidden',
                            'value' => $_['token']
                        ],
                        'type' => [
                            'type' => 'hidden',
                            'value' => $_['type']
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
                                'data' => [
                                    'lot' => [
                                        'fields' => [
                                            'type' => 'fields',
                                            'lot' => [
                                                'content' => [
                                                    'type' => 'source',
                                                    'name' => 'data[content]',
                                                    'value' => $content,
                                                    'width' => true,
                                                    'height' => true,
                                                    'stack' => 10
                                                ],
                                                'name' => [
                                                    'type' => 'name',
                                                    'x' => false,
                                                    'after' => '.data',
                                                    'focus' => true,
                                                    'name' => 'data[name]',
                                                    'value' => $name,
                                                    'width' => true,
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
                                    'title' => "",
                                    'type' => 'field',
                                    'lot' => [
                                        'tasks' => [
                                            'type' => 'tasks/button',
                                            'lot' => [
                                                's' => [
                                                    'title' => 'g' === $_['task'] ? 'Update' : 'Create',
                                                    'description' => ['Create in %s', x\panel\from\path($_['f'])],
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
                                                        'type' => 'data'
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
