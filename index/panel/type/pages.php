<?php

$_ = x\panel\type\pages(array_replace_recursive($_, [
    'lot' => [
        'desk' => [
            // `desk`
            'lot' => [
                'form' => [
                    // `form/post`
                    'lot' => [
                        1 => [
                            // `section`
                            'lot' => [
                                'tabs' => [
                                    // `tabs`
                                    'lot' => [
                                        'data' => [
                                            // `tab`
                                            'lot' => [],
                                            'skip' => !$folder->exist || 0 === q(g($folder->path, 'data')) || false === strpos($_['path'], '/')
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
]));

if (!empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'])) {
    return $_;
}

if (!empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['skip'])) {
    return $_;
}

if (!isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type'])) {
    return $_;
}

if (0 !== strpos($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type'] . '/', 'pages/')) {
    return $_;
}

if (!is_dir($folder = $_['folder'] ?? P)) {
    return $_;
}

$author = $user->user;
$chunk = $_['chunk'] ?? 20;
$count = 0;
$deep = $_['deep'] ?? 0;
$part = $_['part'] ?? 1;
$query = strtolower(s($_['query']['query'] ?? ""));
$super = 1 === $user->status;
$token = $_['token'] ?? null;
$x = $_['x'] ?? 'archive,draft,page';

$page = is_file($f = exist([
    $folder . '.archive',
    $folder . '.draft',
    $folder . '.page'
], 1)) ? new Page($f) : new Page;
$pages = [];

$sort = array_replace("" !== $query ? [] : [1, 'path'], (array) ($_['sort'] ?? []));

foreach ($query ? k($folder, $x, $deep, preg_split('/\s+/', $query)) : g($folder, $x, $deep) as $k => $v) {
    if (false !== strpos(',.archive,.draft,.page,', basename($k)) || isset($pages[$k])) {
        continue; // Skip placeholder page(s)
    }
    $p = new Page($k);
    if (!$super && $author !== $p['author']) {
        continue;
    }
    $pages[$k] = [
        $sort[1] => strip_tags((string) ($p->{$sort[1]} ?? "")),
        'page' => $p
    ];
    ++$count;
}

$pages = new Anemone($pages);
$sort && $pages->sort($sort, true);
$pages = $pages->chunk($chunk, $part - 1, true)->get();
$trash = !empty($state->x->panel->trash) ? date('Y-m-d-H-i-s') : false;
foreach ($pages as $k => $v) {
    $path = strtr($k, [
        LOT . D => "",
        D => '/'
    ]);
    $has_folder = is_dir($d = dirname($k) . D . pathinfo($k, PATHINFO_FILENAME));
    $can_set = $has_folder && q(g($d, 'archive,draft,page')) > 0;
    $p = $v['page'];
    $description = To::description(x\panel\to\w($p->description ?? ""));
    $icon = $p->icon ?? null;
    $image = $icon ? null : ($p->image(72, 72, 100) ?? null);
    $time = $p->time ?? null;
    $title = x\panel\to\w($p->title ?? "");
    $type = $p->type ?? null;
    $x = $p->x ?? null;
    $pages[$k] = [
        'author' => $p['author'],
        'color' => $p->color ?? null,
        'current' => !empty($_SESSION['_']['files'][$k]),
        'description' => $description ? S . ("" !== $query ? preg_replace('/' . x($query) . '/i', '<mark>$0</mark>', strip_tags($description)) : $description) . S : null,
        'icon' => $icon,
        'image' => $image,
        'link' => 'draft' === $x ? null : $p->url . ($can_set ? '/1' : ""),
        'tags' => [
            'type:' . c2f($type) => !empty($type),
            'x:' . $x => true
        ],
        'tasks' => [
            'enter' => [
                'description' => 'Enter',
                'icon' => 'M15.5,2C13,2 11,4 11,6.5C11,9 13,11 15.5,11C16.4,11 17.2,10.7 17.9,10.3L21,13.4L22.4,12L19.3,8.9C19.7,8.2 20,7.4 20,6.5C20,4 18,2 15.5,2M4,4A2,2 0 0,0 2,6V20A2,2 0 0,0 4,22H18A2,2 0 0,0 20,20V15L18,13V20H4V6H9.03C9.09,5.3 9.26,4.65 9.5,4H4M15.5,4C16.9,4 18,5.1 18,6.5C18,7.9 16.9,9 15.5,9C14.1,9 13,7.9 13,6.5C13,5.1 14.1,4 15.5,4Z',
                'skip' => 'draft' === $x || !$can_set,
                'stack' => 10,
                'title' => 'Enter',
                'url' => [
                    'part' => 1,
                    'path' => dirname($path) . '/' . pathinfo($path, PATHINFO_FILENAME),
                    'query' => x\panel\_query_set(),
                    'task' => 'get'
                ]
            ],
            'set' => [
                'active' => $has_folder,
                'description' => $has_folder ? ['Add %s', 'Child'] : ['Missing folder %s', x\panel\from\path($d)],
                'icon' => 'M19,19V5H5V19H19M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5A2,2 0 0,1 3,19V5C3,3.89 3.9,3 5,3H19M11,7H13V11H17V13H13V17H11V13H7V11H11V7Z',
                'skip' => 'draft' === $x || $can_set,
                'stack' => 10,
                'title' => 'Add',
                'url' => $has_folder ? [
                    'part' => 0,
                    'path' => dirname($path) . '/' . pathinfo($path, PATHINFO_FILENAME),
                    'query' => x\panel\_query_set(['type' => 'page']),
                    'task' => 'set'
                ] : null
            ],
            'get' => [
                'description' => 'Edit',
                'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                'stack' => 20,
                'title' => 'Edit',
                'url' => [
                    'part' => 0,
                    'path' => $path,
                    'query' => x\panel\_query_set(),
                    'task' => 'get'
                ]
            ],
            'let' => [
                'description' => 'Delete',
                'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                'stack' => 30,
                'title' => 'Delete',
                'url' => [
                    'part' => 0,
                    'path' => $path,
                    'query' => x\panel\_query_set([
                        'token' => $_['token'],
                        'trash' => $trash
                    ]),
                    'task' => 'let'
                ]
            ]
        ],
        'time' => $time,
        'title' => $title ? S . ("" !== $query ? preg_replace('/' . x($query) . '/i', '<mark>$0</mark>', strip_tags($title)) : $title) . S : null,
    ];
    unset($p);
    if (isset($_SESSION['_']['files'][$k])) {
        unset($_SESSION['_']['files'][$k]);
    }
}

if (
    empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['data']['lot']) &&
    empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['data']['skip']) &&
    isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['data']['type']) &&
    0 === strpos($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['data']['type'] . '/', 'files/')
) {
    $files = [];
    foreach (g($folder, 'data') as $k => $v) {
        $path = strtr($k, [
            LOT . D => "",
            D => '/'
        ]);
        $files[$k] = [
            'current' => !empty($_SESSION['_']['files'][$k]),
            'description' => size(filesize($k)),
            'tags' => ['x:data' => true],
            'tasks' => [
                'get' => [
                    'description' => 'Edit',
                    'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                    'stack' => 10,
                    'title' => 'Edit',
                    'url' => [
                        'part' => 0,
                        'path' => $path,
                        'query' => x\panel\_query_set(),
                        'task' => 'get'
                    ]
                ],
                'let' => [
                    'description' => 'Delete',
                    'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                    'stack' => 20,
                    'title' => 'Delete',
                    'url' => [
                        'part' => 0,
                        'path' => $path,
                        'query' => x\panel\_query_set([
                            'tab' => ['data'],
                            'token' => $token,
                            'trash' => $trash
                        ]),
                        'task' => 'let'
                    ]
                ]
            ],
            'title' => S . basename($k) . S,
            'type' => 'file',
            'url' => [
                'part' => 0,
                'path' => $path,
                'query' => x\panel\_query_set(),
                'task' => 'get'
            ]
        ];
        if (isset($_SESSION['_']['files'][$k])) {
            unset($_SESSION['_']['files'][$k]);
        }
    }
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['data']['lot'] = $files;
}

$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pager']['chunk'] = $_['chunk'] = $chunk;
$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pager']['count'] = $_['count'] = $count;
$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] = $pages;
$_['sort'] = $sort;

return $_;