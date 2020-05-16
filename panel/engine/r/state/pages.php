<?php

$pages = [];
$count = 0;

$search = function($folder, $x, $r) {
    $q = strtolower($_GET['q'] ?? "");
    return $q ? k($folder, $x, $r, preg_split('/\s+/', $q)) : g($folder, $x, $r);
};

$trash = $_['trash'] ? date('Y-m-d-H-i-s') : false;

if (is_dir($folder = LOT . DS . strtr($_['path'], '/', DS))) {
    $before = $url . $_['/'] . '/::';
    foreach ($search($folder, 'archive,draft,page', 0) as $k => $v) {
        if (false !== strpos(',.archive,.draft,.page,', basename($k))) {
            continue; // Skip placeholder page(s)
        }
        $after = '::' . strtr($k, [
            LOT => "",
            DS => '/'
        ]);
        $page = new Page($k);
        $add = is_dir($folder = Path::F($k));
        $create = $add && q(g($folder, 'archive,draft,page')) > 0;
        $pages[$k] = [
            'path' => $k,
            'title' => function() use($page) {
                // Load title asynchronously for best performance
                return S . _\lot\x\panel\h\w($page->title) . S;
            },
            'description' => function() use($page) {
                // Load description asynchronously for best performance
                return S . _\lot\x\panel\h\w($page->description) . S;
            },
            'image' => function() use($page) {
                // Load image asynchronously for best performance
                return $page->image(72, 72, 50);
            },
            'author' => $page['author'],
            'type' => 'page',
            'link' => 'draft' === ($x = $page->x) ? null : $page->url,
            'time' => $page->time . "",
            'tags' => [
                'is:' . $x,
                'type:' . c2f($page->type ?? '0')
            ],
            'tasks' => [
                'enter' => [
                    'hidden' => 'draft' === $x || !$create,
                    'title' => 'Enter',
                    'description' => 'Enter',
                    'icon' => 'M15.5,2C13,2 11,4 11,6.5C11,9 13,11 15.5,11C16.4,11 17.2,10.7 17.9,10.3L21,13.4L22.4,12L19.3,8.9C19.7,8.2 20,7.4 20,6.5C20,4 18,2 15.5,2M4,4A2,2 0 0,0 2,6V20A2,2 0 0,0 4,22H18A2,2 0 0,0 20,20V15L18,13V20H4V6H9.03C9.09,5.3 9.26,4.65 9.5,4H4M15.5,4C16.9,4 18,5.1 18,6.5C18,7.9 16.9,9 15.5,9C14.1,9 13,7.9 13,6.5C13,5.1 14.1,4 15.5,4Z',
                    'url' => $before . 'g' . Path::F($after, '/') . '/1' . $url->query('&', ['tab' => false]) . $url->hash,
                    'stack' => 10
                ],
                's' => [
                    'hidden' => 'draft' === $x || $create,
                    'title' => 'Add',
                    'description' => $add ? ['Add %s', 'Child'] : ['Missing folder %s', _\lot\x\panel\h\path($folder)],
                    'icon' => 'M19,19V5H5V19H19M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5A2,2 0 0,1 3,19V5C3,3.89 3.9,3 5,3H19M11,7H13V11H17V13H13V17H11V13H7V11H11V7Z',
                    'url' => $add ? $before . 's' . Path::F($after, '/') . $url->query('&', ['layout' => 'page', 'tab' => false]) . $url->hash : null,
                    'active' => $add,
                    'stack' => 10
                ],
                'g' => [
                    'title' => 'Edit',
                    'description' => 'Edit',
                    'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                    'url' => $before . 'g' . $after . $url->query('&', ['tab' => false]) . $url->hash,
                    'stack' => 20
                ],
                'l' => [
                    'title' => 'Delete',
                    'description' => 'Delete',
                    'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                    'url' => $before . 'l' . $after . $url->query('&', [
                        'tab' => false,
                        'token' => $_['token'],
                        'trash' => $trash
                    ]),
                    'stack' => 30
                ]
            ]
        ];
        ++$count;
    }
    $pages = (new Anemon($pages))->sort($_['sort'], true)->get();
}

return [
    'desk' => [
        // type: desk
        'lot' => [
            'form' => [
                // type: form/post
                '0' => false, // Remove `<form>` wrapper by setting the node name to `false`
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
                                        'url' => $url . $_['/'] . '/::g::/' . dirname($_['path']) . '/1' . $url->query('&', ['tab' => false]) . $url->hash,
                                        'icon' => 'M13,20H11V8L5.5,13.5L4.08,12.08L12,4.16L19.92,12.08L18.5,13.5L13,8V20Z',
                                        'hidden' => count($_['chops']) <= 1,
                                        'stack' => 10
                                    ],
                                    'blob' => [
                                        'title' => false,
                                        'description' => 'Upload',
                                        'type' => 'link',
                                        'url' => $url . $_['/'] . '/::s::/' . $_['path'] . $url->query('&', ['layout' => 'blob', 'tab' => false]) . $url->hash,
                                        'icon' => 'M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z',
                                        'hidden' => true,
                                        'stack' => 20
                                    ],
                                    'page' => [
                                        'type' => 'link',
                                        'description' => ['New %s', 'Page'],
                                        'url' => $url . $_['/'] . '/::s::/' . $_['path'] . $url->query('&', ['layout' => 'page', 'tab' => false]) . $url->hash,
                                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                        'stack' => 30
                                    ],
                                    'data' => [
                                        'type' => 'link',
                                        'description' => ['New %s', 'Data'],
                                        'url' => $url . $_['/'] . '/::s::/' . $_['path'] . $url->query('&', ['layout' => 'data', 'tab' => false]) . $url->hash,
                                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                        'hidden' => count($_['chops']) <= 1,
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
                                                'lot' => $pages,
                                                'chunk' => $_['chunk'],
                                                'current' => $_['i'],
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
                            'pager' => [
                                'type' => 'pager',
                                'chunk' => $_['chunk'],
                                'count' => $count,
                                'current' => $_['i'],
                                'peek' => $_['peek'],
                                'stack' => 10
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
