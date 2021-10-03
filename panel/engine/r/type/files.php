<?php

Hook::set('_', function($_) {
    if (
        empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['skip']) &&
        empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot']) &&
        isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['type']) &&
        'files' === $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['type']
    ) {
        extract($GLOBALS, EXTR_SKIP);
        $files = [[], []];
        $count = 0;
        $search = static function($folder, $x, $r) {
            $q = strtolower($_GET['q'] ?? "");
            return $q ? k($folder, $x, $r, preg_split('/\s+/', $q)) : g($folder, $x, $r);
        };
        $trash = $_['trash'] ? date('Y-m-d-H-i-s') : false;
        $before = $_['/'] . '/::';
        $super = 1 === $user->status;
        if (is_dir($folder = LOT . DS . strtr($_['path'], '/', DS))) {
            foreach ($search($folder, null, 0) as $k => $v) {
                if (false !== strpos('_.', $n[0]) && !$super) {
                    continue; // User(s) with status other than `1` cannot see hidden file(s)
                }
                $files[$v][$k] = $v;
                ++$count;
            }
            uksort($files[0], 'strnatcmp');
            uksort($files[1], 'strnatcmp');
            $files = array_merge($files[0], $files[1]);
        }
        $files = array_chunk($files, $_['chunk'] ?? 20, true)[($_['i'] ?? 1) - 1] ?? [];
        foreach ($files as $k => $v) {
            $after = '::' . strtr($k, [
                LOT => "",
                DS => '/'
            ]);
            $n = basename($k);
            $files[$k] = [
                'path' => $k,
                'current' => !empty($_SESSION['_'][0 === $v ? 'folder' : 'file'][$k]),
                'title' => S . $n . S,
                'description' => 0 === $v ? ['Open %s', 'Folder'] : S . (new File($k))->size . S,
                'type' => 0 === $v ? 'folder' : 'file',
                'tags' => [
                    'x:' . pathinfo($n, PATHINFO_EXTENSION) => 1 === $v
                ],
                'url' => 0 === $v ? $before . 'g' . $after . '/1' . $url->query('&', [
                    'q' => false,
                    'tab' => false
                ]) . $url->hash : null,
                'link' => 1 === $v ? To::URL($k) : null,
                'tasks' => [
                    'g' => [
                        'title' => 'Edit',
                        'description' => 'Edit',
                        'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                        'url' => $before . 'g' . $after . $url->query('&', [
                            'q' => false,
                            'tab' => false
                        ]) . $url->hash,
                        'stack' => 10
                    ],
                    'l' => [
                        'title' => 'Delete',
                        'description' => 'Delete',
                        'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                        'url' => $before . 'l' . $after . $url->query('&', [
                            'q' => false,
                            'tab' => false,
                            'token' => $_['token'],
                            'trash' => $trash
                        ]),
                        'stack' => 20
                    ]
                ]
            ];
            if (isset($_SESSION['_'][0 === $v ? 'folder' : 'file'][$k])) {
                unset($_SESSION['_'][0 === $v ? 'folder' : 'file'][$k]);
            }
        }
        if (count($_['chop']) > 1 && $_['i'] <= 1) {
            $after = '::/' . $_['path'];
            $files = [$folder => [
                'title' => S . '..' . S,
                'description' => ['Go to %s', 'Parent'],
                'type' => 'folder',
                'url' => $_['/'] . '/::g::/' . dirname($_['path']) . '/1' . $url->query('&', [
                    'q' => false,
                    'tab' => false
                ]) . $url->hash,
                'tasks' => [
                    'g' => [
                        'title' => 'Edit',
                        'description' => 'Edit Parent Folder',
                        'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                        'url' => $before . 'g' . $after . $url->query('&', [
                            'q' => false,
                            'tab' => false
                        ]) . $url->hash,
                        'stack' => 10
                    ],
                    'l' => [
                        'title' => 'Delete',
                        'description' => 'Delete Parent Folder',
                        'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                        'url' => $before . 'l' . $after . $url->query('&', [
                            'q' => false,
                            'tab' => false,
                            'token' => $_['token'],
                            'trash' => $trash
                        ]),
                        'stack' => 20
                    ]
                ]
            ]] + $files;
        }
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'] = $files;
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['pager'] = [
            'type' => 'pager',
            'chunk' => $_['chunk'] ?? 20,
            'count' => $count,
            'current' => $_['i'] ?? 1,
            'stack' => 20
        ];
    }
    return $_;
}, 10);

$desk = [
    // type: desk
    'lot' => [
        'form' => [
            // type: form/post
            'lot' => [
                0 => [
                    // type: section
                    'lot' => [
                        'tasks' => [
                            'type' => 'tasks/button',
                            'lot' => [
                                'blob' => [
                                    'title' => false,
                                    'description' => 'Upload',
                                    'type' => 'link',
                                    'url' => $_['/'] . '/::s::/' . $_['path'] . $url->query('&', [
                                        'q' => false,
                                        'tab' => false,
                                        'type' => 'blob'
                                    ]) . $url->hash,
                                    'icon' => 'M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z',
                                    'stack' => 10
                                ],
                                'file' => [
                                    'type' => 'link',
                                    'description' => ['New %s', 'File'],
                                    'url' => $_['/'] . '/::s::/' . $_['path'] . $url->query('&', [
                                        'q' => false,
                                        'tab' => false,
                                        'type' => 'file'
                                    ]) . $url->hash,
                                    'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                    'stack' => 20
                                ],
                                'folder' => [
                                    'type' => 'link',
                                    'description' => ['New %s', 'Folder'],
                                    'url' => $_['/'] . '/::s::/' . $_['path'] . $url->query('&', [
                                        'q' => false,
                                        'tab' => false,
                                        'type' => 'folder'
                                    ]) . $url->hash,
                                    'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                    'stack' => 30
                                ]
                            ],
                            'stack' => 10
                        ]
                    ]
                ],
                1 => [
                    // type: section
                    'lot' => [
                        'tabs' => [
                            // type: tabs
                            'lot' => [
                                'files' => [
                                    'lot' => [
                                        'files' => [
                                            'type' => 'files',
                                            'lot' => [],
                                            'stack' => 10
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
    ]
];

return ($_ = array_replace_recursive($_, [
    'lot' => [
        'desk' => $desk
    ]
]));