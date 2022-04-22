<?php

Hook::set('_', function($_) use($state, $url, $user) {
    if (
        empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['skip']) &&
        empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot']) &&
        isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type']) &&
        'pages' === $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type']
    ) {
        $bounds = [];
        $count = 0;
        $search = static function($folder, $x, $r) {
            $q = strtolower($_GET['query'] ?? "");
            return $q ? k($folder, $x, $r, preg_split('/\s+/', $q)) : g($folder, $x, $r);
        };
        $pages = [];
        $trash = !empty($state->x->panel->guard->trash) ? date('Y-m-d-H-i-s') : false;
        $author = $user->user;
        $super = 1 === $user->status;
        if (is_dir($folder = $_['folder'] ?? P)) {
            foreach ($search($folder, 'page', 1) as $k => $v) {
                if ('about.page' !== basename($k)) {
                    continue;
                }
                $p = new Page($k);
                $sort = $_['sort'][1] ?? 'time';
                $title = strip_tags((string) ($p->title ?? ""));
                $key = strtr(x\panel\from\path(dirname($k)), [
                    "\\" => '/'
                ]);
                $pages[$k] = [
                    'page' => $p,
                    'title' => $title
                ];
                foreach ((array) $p['use'] as $kk => $vv) {
                    $bounds[strtr($kk, [
                        "\\" => '/'
                    ])][$key] = $title;
                }
                ++$count;
            }
            $pages = new Anemone($pages);
            $pages->sort([1, 'title'], true);
            $pages = $pages->chunk($_GET['chunk'] ?? $_['chunk'] ?? 20, ($_['part'] ?? 1) - 1, true)->get();
            foreach ($pages as $k => $v) {
                $path = strtr($d = dirname($k), [
                    LOT . D => "",
                    D => '/'
                ]);
                $is_active = is_file($d . D . 'index.php');
                $p = $v['page'];
                $description = To::description(x\panel\to\w($p->description ?? ""));
                $icon = $p->icon ?? null;
                $image = $p->image(72, 72) ?? null;
                $time = $p->time ?? null;
                $title = x\panel\to\w($p->title ?? "");
                $type = $p->type ?? null;
                $x = $p->x ?? null;
                $bound = $bounds[strtr(x\panel\from\path(dirname($k)), [
                    "\\" => '/'
                ])] ?? [];
                asort($bound);
                $pages[$k] = [
                    'author' => $p['author'],
                    'current' => !empty($_SESSION['_']['folder'][$d]),
                    'description' => $description ? S . $description . S : null,
                    'icon' => $icon,
                    'image' => $image,
                    'tags' => [
                        'type:' . c2f($type) => !empty($type),
                        'x:' . $x => true
                    ],
                    'tasks' => [
                        'get' => [
                            'description' => 'Edit',
                            'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                            'stack' => 20,
                            'title' => 'Edit',
                            'url' => [
                                'part' => 1,
                                'path' => $path,
                                'query' => [
                                    'chunk' => null,
                                    'deep' => null,
                                    'query' => null,
                                    'stack' => null,
                                    'tab' => ['files'],
                                    'type' => null,
                                    'x' => null
                                ],
                                'task' => 'get'
                            ]
                        ],
                        'toggle' => [
                            'description' => !empty($bound) ? ['Required by %s', implode(', ', $bound)] : ($is_active ? 'Detach' : 'Attach'),
                            'icon' => $is_active ? 'M13,9.86V11.18L15,13.18V9.86C17.14,9.31 18.43,7.13 17.87,5C17.32,2.85 15.14,1.56 13,2.11C10.86,2.67 9.57,4.85 10.13,7C10.5,8.4 11.59,9.5 13,9.86M14,4A2,2 0 0,1 16,6A2,2 0 0,1 14,8A2,2 0 0,1 12,6A2,2 0 0,1 14,4M18.73,22L14.86,18.13C14.21,20.81 11.5,22.46 8.83,21.82C6.6,21.28 5,19.29 5,17V12L10,17H7A3,3 0 0,0 10,20A3,3 0 0,0 13,17V16.27L2,5.27L3.28,4L13,13.72L15,15.72L20,20.72L18.73,22Z' : 'M18,6C18,7.82 16.76,9.41 15,9.86V17A5,5 0 0,1 10,22A5,5 0 0,1 5,17V12L10,17H7A3,3 0 0,0 10,20A3,3 0 0,0 13,17V9.86C11.23,9.4 10,7.8 10,5.97C10,3.76 11.8,2 14,2C16.22,2 18,3.79 18,6M14,8A2,2 0 0,0 16,6A2,2 0 0,0 14,4A2,2 0 0,0 12,6A2,2 0 0,0 14,8Z',
                            'skip' => !$is_active && !is_file($d . D . 'index.x'),
                            'stack' => 20.1,
                            'title' => $is_active ? 'Detach' : 'Attach',
                            'url' => !empty($bound) ? null : [
                                'path' => $path,
                                'query' => [
                                    'chunk' => null,
                                    'deep' => null,
                                    'query' => null,
                                    'radio' => $is_active ? null : 1,
                                    'stack' => null,
                                    'tab' => null,
                                    'token' => $_['token'],
                                    'trash' => null,
                                    'x' => null
                                ],
                                'task' => 'fire/' . (is_file($d . D . 'index.x') ? 'attach' : 'detach')
                            ]
                        ],
                        'let' => [
                            'description' => !empty($bound) ? ['Required by %s', implode(', ', $bound)] : 'Delete',
                            'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                            'stack' => 30,
                            'title' => 'Delete',
                            'url' => !empty($bound) ? null : [
                                'part' => 0,
                                'path' => $path,
                                'query' => [
                                    'chunk' => null,
                                    'deep' => null,
                                    'query' => null,
                                    'stack' => null,
                                    'tab' => null,
                                    'token' => $_['token'],
                                    'trash' => $trash,
                                    'x' => null
                                ],
                                'task' => 'let'
                            ]
                        ]
                    ],
                    'time' => $time,
                    'title' => $title ? S . $title . S : null,
                    'url' => [
                        'part' => 1,
                        'path' => $path,
                        'query' => [
                            'chunk' => null,
                            'deep' => null,
                            'query' => null,
                            'stack' => null,
                            'tab' => ['info'],
                            'type' => null,
                            'x' => null
                        ],
                        'task' => 'get'
                    ]
                ];
                unset($p);
                if (isset($_SESSION['_']['folder'][$d])) {
                    unset($_SESSION['_']['folder'][$d]);
                }
            }
        }
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] = $pages;
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pager'] = [
            'chunk' => $_GET['chunk'] ?? $_['chunk'] ?? 20,
            'count' => $count,
            'current' => $_['part'] ?? 1,
            'stack' => 20,
            'type' => 'pager',
        ];
    }
    return $_;
}, 10.1);

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['description'] = false;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['icon'] = 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z';
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['skip'] = false;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['title'] = 'Add';
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['skip'] = true;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['url'] = [
    'part' => 0,
    'query' => [
        'chunk' => null,
        'deep' => null,
        'query' => null,
        'stack' => null,
        'tab' => null,
        'type' => 'blob/y',
        'x' => null
    ],
    'task' => 'set'
];

$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['title'] = 'Layouts';

return $_;