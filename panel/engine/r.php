<?php

// Default panel data
return (static function($icons) {
    extract($GLOBALS, EXTR_SKIP);
    if (isset($_['content'])) {
        return $_; // Skip!
    }
    $id = explode('/', $_['path'], 2)[0];
    $folders = [];
    foreach (g(LOT, 0) as $k => $v) {
        $n = basename($k);
        if (false !== strpos('._', $n[0])) {
            continue; // Skip hidden folder(s)
        }
        $folders[$n] = [
            'current' => 0 === strpos($_['path'] . '/', $n . '/'),
            'icon' => $icons[$n] ?? 'M10,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V8C22,6.89 21.1,6 20,6H12L10,4Z',
            'title' => 'x' === $n ? 'Extension' : To::title($n),
            'url' => $_['/'] . '/::g::/' . $n . '/1' . $url->hash,
            'skip' => !q(g($k)) // Hide menu if folder is empty
        ];
    }
    $i = 10;
    $list = [];
    foreach ((new Anemon($folders))->sort([1, 'title'], true) as $k => $v) {
        $v['stack'] = $i;
        $i += 10;
        $list[$k] = $v;
    }
    $lot = [
        'bar' => [
            'type' => 'bar',
            'lot' => [
                0 => [
                    'lot' => [
                        'folder' => [
                            'icon' => 'M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z',
                            'caret' => false,
                            'title' => false,
                            'url' => (string) $url,
                            'lot' => $list,
                            'stack' => 10
                        ],
                        'link' => [
                            'icon' => 'M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z',
                            'title' => false,
                            'description' => 'Back',
                            'skip' => true,
                            'stack' => 10
                        ],
                        'search' => [
                            'type' => 'form/get',
                            'url' => (is_file($_['f']) ? dirname($url->clean) : $url->clean) . '/1' . $url->query('&', [
                                'q' => false
                            ]) . $url->hash,
                            'name' => 'get',
                            'lot' => [
                                'fields' => [
                                    'type' => 'fields',
                                    'lot' => [
                                        'q' => [
                                            'title' => 'Search',
                                            'type' => 'text',
                                            'hint' => 'Search',
                                            'value' => $_GET['q'] ?? null,
                                            '2' => ['title' => i('Search in %s', ".\\lot\\" . strtr(is_file($_['f']) ? dirname($_['path']) : $_['path'], '/', "\\"))]
                                        ]
                                    ]
                                ]
                            ],
                            'stack' => 20
                        ]
                    ],
                    'stack' => 10
                ],
                1 => [
                    'lot' => [
                        'license' => [
                            'current' => 'g' === $_['task'] && '.license' === $_['path'],
                            'description' => 'Please read the terms and conditions before using this application.',
                            'icon' => 'M9 10A3.04 3.04 0 0 1 12 7A3.04 3.04 0 0 1 15 10A3.04 3.04 0 0 1 12 13A3.04 3.04 0 0 1 9 10M12 19L16 20V16.92A7.54 7.54 0 0 1 12 18A7.54 7.54 0 0 1 8 16.92V20M12 4A5.78 5.78 0 0 0 7.76 5.74A5.78 5.78 0 0 0 6 10A5.78 5.78 0 0 0 7.76 14.23A5.78 5.78 0 0 0 12 16A5.78 5.78 0 0 0 16.24 14.23A5.78 5.78 0 0 0 18 10A5.78 5.78 0 0 0 16.24 5.74A5.78 5.78 0 0 0 12 4M20 10A8.04 8.04 0 0 1 19.43 12.8A7.84 7.84 0 0 1 18 15.28V23L12 21L6 23V15.28A7.9 7.9 0 0 1 4 10A7.68 7.68 0 0 1 6.33 4.36A7.73 7.73 0 0 1 12 2A7.73 7.73 0 0 1 17.67 4.36A7.68 7.68 0 0 1 20 10Z',
                            'url' => $_['/'] . '/::g::/.license',
                            'skip' => is_file(ENGINE . DS . 'log' . DS . dechex(crc32(ROOT))),
                            'stack' => 0
                        ],
                        'site' => [
                            'current' => false,
                            'url' => (string) $url,
                            'lot' => [
                                'state' => [
                                    'current' => $url . $url->path === $_['/'] . '/::g::/.state',
                                    'icon' => 'M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.97C19.47,12.65 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.21,8.95 2.27,9.22 2.46,9.37L4.57,11C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.65 4.57,12.97L2.46,14.63C2.27,14.78 2.21,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.67 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.97Z',
                                    'url' => $_['/'] . '/::g::/.state' . $url->hash,
                                    'stack' => 10
                                ],
                                'view' => [
                                    'current' => false,
                                    'icon' => 'M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z',
                                    'title' => 'View',
                                    'link' => (string) $url,
                                    'stack' => 30
                                ]
                            ],
                            'stack' => 10
                        ]
                    ],
                    'stack' => 20
                ],
                2 => [
                    'lot' => [
                        'user' => [
                            '2' => [
                                'target' => '_top' // Needed to disable the AJAX link
                            ],
                            'icon' => 'M19,21V19H15V17H19V15L22,18L19,21M10,4A4,4 0 0,1 14,8A4,4 0 0,1 10,12A4,4 0 0,1 6,8A4,4 0 0,1 10,4M10,14C11.15,14 12.25,14.12 13.24,14.34C12.46,15.35 12,16.62 12,18C12,18.7 12.12,19.37 12.34,20H2V18C2,15.79 5.58,14 10,14Z',
                            'caret' => false,
                            'title' => false,
                            'description' => i('Exit') . ' ' . $user->user,
                            'url' => $url . $_['user']['path'] . '/' . $user->name . '?exit=' . $_['token'] . $url->hash,
                            'stack' => 10
                        ]
                    ],
                    'stack' => 30
                ]
            ],
            'stack' => 10
        ],
        'desk' => [
            'type' => 'desk',
            'lot' => [
                'form' => [
                    'type' => 'form/post',
                    'url' => $url->current,
                    'name' => 'set',
                    'lot' => [
                        0 => [
                            'type' => 'section',
                            'lot' => [],
                            'stack' => 10
                        ],
                        'alert' => isset($alert) ? [
                            'type' => 'section',
                            'content' => $alert,
                            'skip' => !count($alert),
                            'stack' => 15
                        ] : [],
                        1 => [
                            'type' => 'section',
                            'lot' => [
                                'tabs' => [
                                    'type' => 'tabs',
                                    'name' => 0,
                                    'lot' => []
                                ]
                            ],
                            'stack' => 20
                        ],
                        2 => [
                            'type' => 'section',
                            'lot' => [],
                            'stack' => 30
                        ]
                    ],
                    'stack' => 10
                ]
            ],
            'stack' => 20
        ]
    ];
    return \array_replace_recursive([
        'has' => ['parent' => \count($_['chop']) > 1],
        'is' => [
            'error' => false,
            'page' => !isset($_['i']),
            'pages' => isset($_['i'])
        ],
        'lot' => $lot
    ], $_);
})([
    'asset' => 'M11,13.5V21.5H3V13.5H11M12,2L17.5,11H6.5L12,2M17.5,13C20,13 22,15 22,17.5C22,20 20,22 17.5,22C15,22 13,20 13,17.5C13,15 15,13 17.5,13Z',
    'cache' => 'M13,2.05V5.08C16.39,5.57 19,8.47 19,12C19,12.9 18.82,13.75 18.5,14.54L21.12,16.07C21.68,14.83 22,13.45 22,12C22,6.82 18.05,2.55 13,2.05M12,19A7,7 0 0,1 5,12C5,8.47 7.61,5.57 11,5.08V2.05C5.94,2.55 2,6.81 2,12A10,10 0 0,0 12,22C15.3,22 18.23,20.39 20.05,17.91L17.45,16.38C16.17,18 14.21,19 12,19Z',
    'comment' => 'M17,12V3A1,1 0 0,0 16,2H3A1,1 0 0,0 2,3V17L6,13H16A1,1 0 0,0 17,12M21,6H19V15H6V17A1,1 0 0,0 7,18H18L22,22V7A1,1 0 0,0 21,6Z',
    'layout' => 'M13,3V9H21V3M13,21H21V11H13M3,21H11V15H3M3,13H11V3H3V13Z',
    'page' => 'M19,2L14,6.5V17.5L19,13V2M6.5,5C4.55,5 2.45,5.4 1,6.5V21.16C1,21.41 1.25,21.66 1.5,21.66C1.6,21.66 1.65,21.59 1.75,21.59C3.1,20.94 5.05,20.5 6.5,20.5C8.45,20.5 10.55,20.9 12,22C13.35,21.15 15.8,20.5 17.5,20.5C19.15,20.5 20.85,20.81 22.25,21.56C22.35,21.61 22.4,21.59 22.5,21.59C22.75,21.59 23,21.34 23,21.09V6.5C22.4,6.05 21.75,5.75 21,5.5V7.5L21,13V19C19.9,18.65 18.7,18.5 17.5,18.5C15.8,18.5 13.35,19.15 12,20V13L12,8.5V6.5C10.55,5.4 8.45,5 6.5,5V5Z',
    'tag' => 'M5.5,9A1.5,1.5 0 0,0 7,7.5A1.5,1.5 0 0,0 5.5,6A1.5,1.5 0 0,0 4,7.5A1.5,1.5 0 0,0 5.5,9M17.41,11.58C17.77,11.94 18,12.44 18,13C18,13.55 17.78,14.05 17.41,14.41L12.41,19.41C12.05,19.77 11.55,20 11,20C10.45,20 9.95,19.78 9.58,19.41L2.59,12.42C2.22,12.05 2,11.55 2,11V6C2,4.89 2.89,4 4,4H9C9.55,4 10.05,4.22 10.41,4.58L17.41,11.58M13.54,5.71L14.54,4.71L21.41,11.58C21.78,11.94 22,12.45 22,13C22,13.55 21.78,14.05 21.42,14.41L16.04,19.79L15.04,18.79L20.75,13L13.54,5.71Z',
    'trash' => 'M21.82,15.42L19.32,19.75C18.83,20.61 17.92,21.06 17,21H15V23L12.5,18.5L15,14V16H17.82L15.6,12.15L19.93,9.65L21.73,12.77C22.25,13.54 22.32,14.57 21.82,15.42M9.21,3.06H14.21C15.19,3.06 16.04,3.63 16.45,4.45L17.45,6.19L19.18,5.19L16.54,9.6L11.39,9.69L13.12,8.69L11.71,6.24L9.5,10.09L5.16,7.59L6.96,4.47C7.37,3.64 8.22,3.06 9.21,3.06M5.05,19.76L2.55,15.43C2.06,14.58 2.13,13.56 2.64,12.79L3.64,11.06L1.91,10.06L7.05,10.14L9.7,14.56L7.97,13.56L6.56,16H11V21H7.4C6.47,21.07 5.55,20.61 5.05,19.76Z',
    'user' => 'M16,13C15.71,13 15.38,13 15.03,13.05C16.19,13.89 17,15 17,16.5V19H23V16.5C23,14.17 18.33,13 16,13M8,13C5.67,13 1,14.17 1,16.5V19H15V16.5C15,14.17 10.33,13 8,13M8,11A3,3 0 0,0 11,8A3,3 0 0,0 8,5A3,3 0 0,0 5,8A3,3 0 0,0 8,11M16,11A3,3 0 0,0 19,8A3,3 0 0,0 16,5A3,3 0 0,0 13,8A3,3 0 0,0 16,11Z',
    'x' => 'M20.5,11H19V7C19,5.89 18.1,5 17,5H13V3.5A2.5,2.5 0 0,0 10.5,1A2.5,2.5 0 0,0 8,3.5V5H4A2,2 0 0,0 2,7V10.8H3.5C5,10.8 6.2,12 6.2,13.5C6.2,15 5,16.2 3.5,16.2H2V20A2,2 0 0,0 4,22H7.8V20.5C7.8,19 9,17.8 10.5,17.8C12,17.8 13.2,19 13.2,20.5V22H17A2,2 0 0,0 19,20V16H20.5A2.5,2.5 0 0,0 23,13.5A2.5,2.5 0 0,0 20.5,11Z'
]);
