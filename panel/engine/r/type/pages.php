<?php

Hook::set('_', function($_) {
    if (
        empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['skip']) &&
        empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot']) &&
        isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type']) &&
        'pages' === $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type']
    ) {
        extract($GLOBALS, EXTR_SKIP);
        $count = 0;
        $search = static function($folder, $x, $r) {
            $q = strtolower($_GET['q'] ?? "");
            return $q ? k($folder, $x, $r, preg_split('/\s+/', $q)) : g($folder, $x, $r);
        };
        $d = $_['f'];
        $page = is_file($f = File::exist([
            $d . '.archive',
            $d . '.draft',
            $d . '.page'
        ])) ? new Page($f) : new Page;
        $pages = [];
        $trash = $_['trash'] ? date('Y-m-d-H-i-s') : false;
        $author = $user->user;
        $super = 1 === $user->status;
        if (is_dir($folder = LOT . DS . strtr($_['path'], '/', DS))) {
            foreach ($search($folder, 'archive,draft,page', 0) as $k => $v) {
                if (false !== strpos(',.archive,.draft,.page,', basename($k))) {
                    continue; // Skip placeholder page(s)
                }
                $p = new Page($k);
                if (!$super && $author !== $p['author']) {
                    continue;
                }
                $sort = $_['sort'][1] ?? 'time';
                $pages[$k] = [
                    $sort => (string) ($p->{$sort} ?? ""),
                    'page' => $p
                ];
                ++$count;
            }
            $pages = new Anemon($pages);
            $pages->sort(array_replace([1, 'path'], $_['sort'], (array) ($page->sort ?? [])), true);
            $pages = $pages->chunk($_['chunk'] ?? 20, ($_['i'] ?? 1) - 1, true)->get();
            $before = $_['/'] . '/::';
            foreach ($pages as $k => $v) {
                $after = '::' . strtr($k, [
                    LOT => "",
                    DS => '/'
                ]);
                $can_insert = is_dir($d = Path::F($k));
                $can_set = $can_insert && q(g($d, 'archive,draft,page')) > 0;
                $p = $v['page'];
                $title = x\panel\to\w($p->title ?? "");
                $description = To::excerpt(x\panel\to\w($p->description ?? ""));
                $image = $p->image(72, 72, 50) ?? null;
                $type = $p->type ?? null;
                $time = $p->time ?? null;
                $x = $p->x ?? null;
                $pages[$k] = [
                    'path' => $k,
                    'current' => !empty($_SESSION['_']['file'][$k]),
                    'title' => $title ? S . $title . S : null,
                    'description' => $description ? S . $description . S : null,
                    'image' => $image,
                    'time' => $time,
                    'link' => 'draft' === $x ? null : $p->url . ($can_set ? '/1' : ""),
                    'author' => $p['author'],
                    'tags' => [
                        'type:' . c2f($type) => !empty($type),
                        'x:' . $x => true
                    ],
                    'tasks' => [
                        'enter' => [
                            'title' => 'Enter',
                            'description' => 'Enter',
                            'icon' => 'M15.5,2C13,2 11,4 11,6.5C11,9 13,11 15.5,11C16.4,11 17.2,10.7 17.9,10.3L21,13.4L22.4,12L19.3,8.9C19.7,8.2 20,7.4 20,6.5C20,4 18,2 15.5,2M4,4A2,2 0 0,0 2,6V20A2,2 0 0,0 4,22H18A2,2 0 0,0 20,20V15L18,13V20H4V6H9.03C9.09,5.3 9.26,4.65 9.5,4H4M15.5,4C16.9,4 18,5.1 18,6.5C18,7.9 16.9,9 15.5,9C14.1,9 13,7.9 13,6.5C13,5.1 14.1,4 15.5,4Z',
                            'url' => $before . 'g' . Path::F($after, '/') . '/1' . $url->query('&', [
                                'q' => false,
                                'tab' => false
                            ]) . $url->hash,
                            'skip' => 'draft' === $x || !$can_set,
                            'stack' => 10
                        ],
                        's' => [
                            'active' => $can_insert,
                            'title' => 'Add',
                            'description' => $can_insert ? ['Add %s', 'Child'] : ['Missing folder %s', x\panel\from\path($d)],
                            'icon' => 'M19,19V5H5V19H19M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5A2,2 0 0,1 3,19V5C3,3.89 3.9,3 5,3H19M11,7H13V11H17V13H13V17H11V13H7V11H11V7Z',
                            'url' => $can_insert ? $before . 's' . Path::F($after, '/') . $url->query('&', [
                                'q' => false,
                                'tab' => false,
                                'type' => 'page'
                            ]) . $url->hash : null,
                            'skip' => 'draft' === $x || $can_set,
                            'stack' => 10
                        ],
                        'g' => [
                            'title' => 'Edit',
                            'description' => 'Edit',
                            'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                            'url' => $before . 'g' . $after . $url->query('&', [
                                'q' => false,
                                'tab' => false
                            ]) . $url->hash,
                            'stack' => 20
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
                            'stack' => 30
                        ]
                    ]
                ];
                unset($p);
                if (isset($_SESSION['_']['file'][$k])) {
                    unset($_SESSION['_']['file'][$k]);
                }
            }
            if (
                empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['data']['lot']) &&
                empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['data']['lot']) &&
                isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['data']['type']) &&
                'files' === $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['data']['type']
            ) {
                $files = [];
                foreach (g($folder, 'data') as $k => $v) {
                    $after = '::' . strtr($k, [
                        LOT => "",
                        DS => '/'
                    ]);
                    $files[$k] = [
                        'path' => $k,
                        'current' => !empty($_SESSION['_']['file'][$k]),
                        'title' => basename($k),
                        'description' => (new File($k))->size,
                        'type' => 'file',
                        'tags' => [
                            'x:data' => true
                        ],
                        'url' => $before . 'g' . $after . $url->query('&', [
                            'q' => false,
                            'tab' => false
                        ]) . $url->hash,
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
                    if (isset($_SESSION['_']['file'][$k])) {
                        unset($_SESSION['_']['file'][$k]);
                    }
                }
                $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['data']['lot'] = $files;
            }
        }
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] = $pages;
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pager'] = [
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
                                'parent' => [
                                    'title' => false,
                                    'description' => ['Go to %s', 'Parent'],
                                    'type' => 'link',
                                    'url' => $_['/'] . '/::g::/' . dirname($_['path']) . '/1' . $url->query('&', [
                                        'q' => false,
                                        'tab' => false
                                    ]) . $url->hash,
                                    'icon' => 'M13,20H11V8L5.5,13.5L4.08,12.08L12,4.16L19.92,12.08L18.5,13.5L13,8V20Z',
                                    'skip' => count($_['chop']) <= 1,
                                    'stack' => 10
                                ],
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
                                    'skip' => true,
                                    'stack' => 20
                                ],
                                'page' => [
                                    'type' => 'link',
                                    'description' => ['New %s', 'Page'],
                                    'url' => $_['/'] . '/::s::/' . $_['path'] . $url->query('&', [
                                        'q' => false,
                                        'tab' => false,
                                        'type' => 'page'
                                    ]) . $url->hash,
                                    'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                    'stack' => 30
                                ],
                                'data' => [
                                    'type' => 'link',
                                    'description' => ['New %s', 'Data'],
                                    'url' => $_['/'] . '/::s::/' . $_['path'] . $url->query('&', [
                                        'q' => false,
                                        'tab' => false,
                                        'type' => 'data'
                                    ]) . $url->hash,
                                    'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                    'skip' => count($_['chop']) <= 1,
                                    'stack' => 40
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
                                'pages' => [
                                    'lot' => [
                                        'pages' => [
                                            'type' => 'pages',
                                            'lot' => [],
                                            'stack' => 10
                                        ]
                                    ],
                                    'stack' => 10
                                ],
                                'data' => [
                                    'lot' => [
                                        'data' => [
                                            'type' => 'files',
                                            'lot' => [],
                                            'stack' => 10
                                        ]
                                    ],
                                    'skip' => count($_['chop']) <= 1 || 0 === q(g($_['f'], 'data')),
                                    'stack' => 20
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