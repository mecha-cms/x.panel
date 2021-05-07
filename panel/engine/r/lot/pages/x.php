<?php

// `http://127.0.0.1/panel/::g::/x/foo-bar/1`
$i = count($_['chop']);
$uses = [
    'alert' => 1,
    'asset' => 1,
    'layout' => 1,
    'page' => 1,
    'panel' => 1,
    'user' => 1,
    'y-a-m-l' => 1
];

$trash = $_['trash'] ? date('Y-m-d-H-i-s') : false;

if ($i > 1) {
    $_ = require __DIR__ . DS . '..' . DS . 'index.php';
    if (2 === $i) {
        $_['lot']['bar']['lot'][0]['lot']['folder']['skip'] = true;
        $_['lot']['bar']['lot'][0]['lot']['link']['icon'] = 'M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z';
        $_['lot']['bar']['lot'][0]['lot']['link']['url'] = $_['/'] . '/::g::/' . dirname($_['path']) . '/1' . $url->query('&', [
            'tab' => false,
            'type' => false
        ]) . $url->hash;
        $_['lot']['bar']['lot'][0]['lot']['link']['skip'] = false;
        Hook::set('_', function($_) use($uses) {
            extract($GLOBALS, EXTR_SKIP);
            if (isset($uses[$_['chop'][1]])) {
                // Disable delete button where possible
                $index = $index = $_['f'] . DS . 'index.php';
                $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$index]['tasks']['l']['active'] = false;
                unset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$index]['tasks']['l']['url']);
            }
            if (is_file($f = ($d = $_['f']) . DS . 'about.page')) {
                $page = new Page($f);
                $content = $page->content;
                // Make URL example(s) in content become usable
                $content = strtr($content, [
                    '://127.0.0.1/panel/' => '://' . explode(':', $_['/'], 2)[1] . '/',
                    '://127.0.0.1' => '://' . explode(':', $url . "", 2)[1]
                ]);
                $use = "";
                if ($uses = $page->use) {
                    $use .= '<details class="p"><summary><strong>' . i('Dependency') . '</strong> (' . count($uses) . ')</summary><ul>';
                    foreach ((array) $uses as $k => $v) {
                        if (is_file($kk = strtr($k, [
                            ".\\" => ROOT . DS,
                            "\\" => DS
                        ]) . DS . 'index.php') && $v) {
                            $use .= '<li><a href="' . $_['/'] . '/::g::/' . dirname(Path::R($kk, LOT, '/')) . '/1?tab[0]=info">' . $k . '</a></li>';
                        } else {
                            $use .= '<li>' . $k . (0 === $v ? ' <span class="description">(' . i('optional') . ')</span>' : "") . '</li>';
                        }
                    }
                    $use .= '</ul></details>';
                }
                // Hide some file(s) from the list
                foreach ([
                    // Parent folder
                    $d,
                    // About file
                    $d . DS . 'about.page',
                    // License file
                    $d . DS . 'LICENSE',
                    // Custom stack data
                    $d . DS . basename($d)
                ] as $p) {
                    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$p]['skip'] = true;
                }
                $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['info'] = [
                    'lot' => [
                        0 => [
                            'title' => $page->title . ' <sup>' . $page->version . '</sup>',
                            'description' => $page->description,
                            'type' => 'section',
                            'content' => $content . $use,
                            'stack' => 10
                        ]
                    ],
                    'stack' => 20
                ];
            }
            if (is_file($f = $d . DS . 'LICENSE')) {
                $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['license'] = [
                    'icon' => false === strpos(file_get_contents($f), '://fsf.org') ? 'M9 10A3.04 3.04 0 0 1 12 7A3.04 3.04 0 0 1 15 10A3.04 3.04 0 0 1 12 13A3.04 3.04 0 0 1 9 10M12 19L16 20V16.92A7.54 7.54 0 0 1 12 18A7.54 7.54 0 0 1 8 16.92V20M12 4A5.78 5.78 0 0 0 7.76 5.74A5.78 5.78 0 0 0 6 10A5.78 5.78 0 0 0 7.76 14.23A5.78 5.78 0 0 0 12 16A5.78 5.78 0 0 0 16.24 14.23A5.78 5.78 0 0 0 18 10A5.78 5.78 0 0 0 16.24 5.74A5.78 5.78 0 0 0 12 4M20 10A8.04 8.04 0 0 1 19.43 12.8A7.84 7.84 0 0 1 18 15.28V23L12 21L6 23V15.28A7.9 7.9 0 0 1 4 10A7.68 7.68 0 0 1 6.33 4.36A7.73 7.73 0 0 1 12 2A7.73 7.73 0 0 1 17.67 4.36A7.68 7.68 0 0 1 20 10Z' : null,
                    'lot' => [
                        0 => [
                            'type' => 'section',
                            'content' => '<pre class="is:text"><code class="txt">' . htmlspecialchars(file_get_contents($f)) . '</code></pre>',
                            'stack' => 10
                        ]
                    ],
                    'stack' => 30
                ];
            }
            return $_;
        }, 10.1);
    }
    return $_;
}

// `http://127.0.0.1/panel/::g::/x/1`
$_['type'] = 'pages';

$_ = require __DIR__ . DS . '..' . DS . 'index.php';

Hook::set('_', function($_) use($uses) {
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
        $pages = [];
        $trash = $_['trash'] ? date('Y-m-d-H-i-s') : false;
        $author = $user->user;
        $super = 1 === $user->status;
        if (is_dir($folder = LOT . DS . strtr($_['path'], '/', DS))) {
            foreach ($search($folder, 'page', 1) as $k => $v) {
                if ('about.page' !== basename($k)) {
                    continue;
                }
                $p = new Page($k);
                $sort = $_['sort'][1] ?? 'time';
                $pages[$k] = [
                    'page' => $p,
                    'title' => (string) ($p->title ?? "")
                ];
                ++$count;
            }
            $pages = new Anemon($pages);
            $pages->sort([1, 'title'], true);
            $pages = $pages->chunk($_['chunk'] ?? 20, ($_['i'] ?? 1) - 1, true)->get();
            $before = $_['/'] . '/::';
            foreach ($pages as $k => $v) {
                $after = '::' . strtr($d = dirname($k), [
                    LOT => "",
                    DS => '/'
                ]);
                $is_active = is_file($d . DS . 'index.php');
                $p = $v['page'];
                $title = x\panel\to\w($p->title ?? "");
                $description = To::excerpt(x\panel\to\w($p->description ?? ""));
                $image = $p->image(72, 72, 50) ?? null;
                $type = $p->type ?? null;
                $time = $p->time ?? null;
                $n = basename($d);
                $x = $p->x ?? null;
                $pages[$k] = [
                    'path' => $k,
                    'current' => !empty($_SESSION['_']['folder'][$d]),
                    'title' => $title ? S . $title . S : null,
                    'description' => $description ? S . $description . S : null,
                    'image' => $image,
                    'time' => $time,
                    'url' => $before . 'g' . $after . '/1' . $url->query('&', [
                        'q' => false,
                        'tab' => ['info']
                    ]) . $url->hash,
                    'author' => $p['author'],
                    'tags' => [
                        'is:' . $x => true,
                        'type:' . c2f($type) => !empty($type)
                    ],
                    'tasks' => [
                        'g' => [
                            'title' => 'Edit',
                            'description' => 'Edit',
                            'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                            'url' => $before . 'g' . $after . '/1' . $url->query('&', [
                                'q' => false,
                                'tab' => ['files']
                            ]) . $url->hash,
                            'stack' => 20
                        ],
                        'toggle' => [
                            'title' => $is_active ? 'Disable' : 'Enable',
                            'description' => $is_active ? 'Disable' : 'Enable',
                            'icon' => $is_active ? 'M13,9.86V11.18L15,13.18V9.86C17.14,9.31 18.43,7.13 17.87,5C17.32,2.85 15.14,1.56 13,2.11C10.86,2.67 9.57,4.85 10.13,7C10.5,8.4 11.59,9.5 13,9.86M14,4A2,2 0 0,1 16,6A2,2 0 0,1 14,8A2,2 0 0,1 12,6A2,2 0 0,1 14,4M18.73,22L14.86,18.13C14.21,20.81 11.5,22.46 8.83,21.82C6.6,21.28 5,19.29 5,17V12L10,17H7A3,3 0 0,0 10,20A3,3 0 0,0 13,17V16.27L2,5.27L3.28,4L13,13.72L15,15.72L20,20.72L18.73,22Z' : 'M18,6C18,7.82 16.76,9.41 15,9.86V17A5,5 0 0,1 10,22A5,5 0 0,1 5,17V12L10,17H7A3,3 0 0,0 10,20A3,3 0 0,0 13,17V9.86C11.23,9.4 10,7.8 10,5.97C10,3.76 11.8,2 14,2C16.22,2 18,3.79 18,6M14,8A2,2 0 0,0 16,6A2,2 0 0,0 14,4A2,2 0 0,0 12,6A2,2 0 0,0 14,8Z',
                            'url' => !isset($uses[$n]) ? $before . 'f' . strtr($after, [
                                '::/' => '::/113d1ba5/'
                            ]) . $url->query('&', [
                                'kick' => URL::short($url->current, false),
                                'q' => false,
                                'tab' => false,
                                'token' => $_['token'],
                                'trash' => false
                            ]) : null,
                            'skip' => !$is_active && !is_file($d . DS . 'index.x'),
                            'stack' => 20.1
                        ],
                        'l' => [
                            'title' => 'Delete',
                            'description' => !isset($uses[$n]) ? 'Delete' : ['Required by %s', 'Panel'],
                            'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                            'url' => !isset($uses[$n]) ? $before . 'l' . $after . $url->query('&', [
                                'q' => false,
                                'tab' => false,
                                'token' => $_['token'],
                                'trash' => $trash
                            ]) : null,
                            'stack' => 30
                        ]
                    ]
                ];
                unset($p);
                if (isset($_SESSION['_']['folder'][$d])) {
                    unset($_SESSION['_']['folder'][$d]);
                }
            }
        }
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] = $pages;
        $_['lot']['desk']['lot']['form']['lot'][2]['lot']['pager'] = [
            'type' => 'pager',
            'chunk' => $_['chunk'] ?? 20,
            'count' => $count,
            'current' => $_['i'] ?? 1,
            'stack' => 10
        ];
    }
    return $_;
}, 10.1);

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['skip'] = true;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['skip'] = false;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['title'] = 'Add';
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['description'] = false;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['icon'] = 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z';
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['url'] = $_['/'] . '/::s::/' . $_['path'] . $url->query('&', [
    'q' => false,
    'tab' => false,
    'type' => 'blob/x'
]) . $url->hash;

$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['title'] = 'Extensions';

return $_;
