<?php

// `http://127.0.0.1/panel/::g::/x/foo-bar/1`
$i = count($_['chops']);
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
    $_ = require __DIR__ . DS . '..' . DS . 'pages.php';
    if (isset($uses[$_['chops'][1]])) {
        // Disable delete button where possible
        $index = $index = $_['f'] . DS . 'index.php';
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$index]['tasks']['l']['active'] = false;
        unset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$index]['tasks']['l']['url']);

    }
    if (2 === $i) {
        $_['lot']['bar']['lot'][0]['lot']['folder']['skip'] = true;
        $_['lot']['bar']['lot'][0]['lot']['link']['icon'] = 'M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z';
        $_['lot']['bar']['lot'][0]['lot']['link']['url'] = $url . $_['/'] . '/::g::/' . dirname($_['path']) . '/1' . $url->query('&', [
            'tab' => false,
            'type' => false
        ]) . $url->hash;
        $_['lot']['bar']['lot'][0]['lot']['link']['skip'] = false;
        if (is_file($f = ($d = $_['f']) . DS . 'about.page')) {
            $page = new Page($f);
            $content = $page->content;
            // Make URL example(s) in content become usable
            $content = strtr($content, [
                '://127.0.0.1/panel/' => '://' . $url->host . $url->d . $_['/'] . '/',
                '://127.0.0.1' => '://' . $url->host . $url->d
            ]);
            $use = "";
            if ($uses = $page->use) {
                $use .= '<details class="p"><summary><strong>' . i('Dependency') . '</strong> (' . count($uses) . ')</summary><ul>';
                foreach ((array) $uses as $k => $v) {
                    if (is_file($kk = strtr($k, [
                        ".\\" => ROOT . DS,
                        "\\" => DS
                    ]) . DS . 'index.php') && $v) {
                        $use .= '<li><a href="' . $url . $_['/'] . '/::g::/' . dirname(Path::R($kk, LOT, '/')) . '/1?tab[0]=info">' . $k . '</a></li>';
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
                        'description' => _\lot\x\panel\to\w($page->description, 'a'),
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
    }
    return $_;
}

// `http://127.0.0.1/panel/::g::/x/1`
$_['type'] = 'page';

$_ = require __DIR__ . DS . '..' . DS . 'pages.php';

// Hide search form
$_['lot']['bar']['lot'][0]['lot']['search']['skip'] = true;

$pages = [];
$count = 0;

if (is_dir($folder = LOT . DS . strtr($_['path'], '/', DS))) {
    $before = $url . $_['/'] . '/::';
    foreach (g($folder, 'page', 1) as $k => $v) {
        if ('about.page' !== basename($k)) {
            continue;
        }
        $after = '::' . strtr($kk = dirname($k), [
            LOT => "",
            DS => '/'
        ]);
        $n = basename($kk);
        $pages[$k] = [
            // Load data asynchronously for best performance
            'invoke' => function($path) {
                $page = new Page($path);
                return [
                    'title' => S . _\lot\x\panel\to\w($page->title) . S,
                    'description' => S . _\lot\x\panel\to\w($page->description) . S,
                    'author' => $page['author'],
                    'image' => $page->image(72, 72, 50),
                    'tags' => [
                        'is:page' => true,
                        'type:' . c2f($page->type ?? '0') => true
                    ]
                ];
            },
            'path' => $k,
            'type' => 'page',
            'url' => $before . 'g' . $after . '/1' . $url->query('&', [
                'tab' => ['info']
            ]) . $url->hash,
            'tasks' => [
                'g' => [
                    'title' => 'Edit',
                    'description' => 'Edit',
                    'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                    'url' => $before . 'g' . $after . '/1' . $url->query('&', [
                        'tab' => ['files']
                    ]) . $url->hash,
                    'stack' => 20
                ],
                'l' => [
                    'title' => 'Delete',
                    'description' => 'Delete',
                    'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                    'url' => !isset($uses[$n]) ? $before . 'l' . $after . $url->query('&', [
                        'tab' => false,
                        'token' => $_['token'],
                        'trash' => $trash
                    ]) : null,
                    'active' => !isset($uses[$n]),
                    'stack' => 30
                ]
            ],
            '#title' => From::page(file_get_contents($k))['title'] ?? null
        ];
        ++$count;
    }
    $pages = (new Anemon($pages))->sort([1, '#title'], true)->chunk($_['chunk'], ($_['i'] ?? 1) - 1, true)->get();
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] = $pages;
    $_['lot']['desk']['lot']['form']['lot'][2]['lot']['pager']['count'] = $count;
}

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['skip'] = true;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['skip'] = false;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['title'] = 'Add';
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['description'] = false;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['icon'] = 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z';
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['url'] = $url . $_['/'] . '/::s::/' . $_['path'] . $url->query('&', [
    'tab' => false,
    'type' => 'blob/x'
]) . $url->hash;

return $_;
