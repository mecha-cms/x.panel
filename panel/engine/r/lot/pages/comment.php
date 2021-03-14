<?php

// `http://127.0.0.1/panel/::g::/comment/1`
$_['type'] = 'pages';

$_ = require __DIR__ . DS . '..' . DS . 'index.php';

$_['lot']['desk']['lot']['form']['lot'][1]['title'] = ($r = false === strpos($_['path'], '/')) ? ['Recent %s', 'Comments'] : null;
$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['title'] = 'Comments';

$d = strtr($_['f'], [LOT . DS . 'comment' . DS => LOT . DS . 'page' . DS]);
if (!$r && $f = File::exist([
    $d . '.archive',
    $d . '.draft',
    $d . '.page'
])) {
    $page = new Page($f);
    $_['lot']['desk']['lot']['form']['lot'][1]['title'] = i('Page') . ': <a href="' . $_['/'] . '/::g::/' . strtr($f, [LOT . DS => "", DS => '/']) . '">' . $page->title . '</a>';
}

$files = $pages = [];
$count = 0;

$trash = $_['trash'] ? date('Y-m-d-H-i-s') : false;

// `dechex(crc32('comments'))`
if ($r && is_file($f = LOT . DS . 'cache' . DS . '5f9e962a.php')) {
    foreach ((array) require $f as $v) {
        if (!is_file($v = LOT . DS . $v)) {
            continue;
        }
        $files[$v] = 1;
    }
} else {
    $files = g($_['f'], 'archive,draft,page');
}

if ($files) {
    $before = $_['/'] . '/::';
    $author = $user->user;
    foreach ($files as $k => $v) {
        $after = '::' . strtr($k, [
            LOT => "",
            DS => '/'
        ]);
        $skip = false;
        $kk = strtr(dirname($k), [$folder . DS => LOT . DS . 'page' . DS]);
        if ($parent = File::exist([
            $kk . '.draft',
            $kk . '.page',
            $kk . '.archive'
        ])) {
            $test = (new Page($parent))['author'];
            $skip = $test && $test !== $author;
        }
        $a = \State::get('x.comment.anchor.0');
        $x = pathinfo($k, PATHINFO_EXTENSION);
        $pages[$k] = [
            // Load data asynchronously for best performance
            'invoke' => function($path) use($a, $x) {
                $page = new Comment($path);
                return [
                    'title' => S . x\panel\to\w($page->author) . S,
                    'description' => S . x\panel\to\w($page->content) . S,
                    'link' => 'draft' === $x ? null : $page->url . '#' . sprintf($a, $page->id),
                    'author' => $page['author'],
                    'image' => $page->avatar(72),
                    'tags' => [
                        'is:' . $x => true,
                        'type:comment' => true
                    ]
                ];
            },
            'path' => $k,
            'type' => 'page',
            'tags' => [
                'is:' . $x,
                'type:comment'
            ],
            'tasks' => [
                'g' => [
                    'title' => 'Edit',
                    'description' => 'Edit',
                    'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                    'url' => $before . 'g' . $after . $url->query('&', [
                        'tab' => false
                    ]) . $url->hash,
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
            ],
            // Hide comment(s) that is not related to the page that is written by the current user
            'skip' => $skip
        ];
        if (!isset($pages[$k][$_['sort'][1]])) {
            $pages[$k][$_['sort'][1]] = (string) (new Comment($k))->{$_['sort'][1]};
        }
        ++$count;
    }
    $p = new Anemon($pages);
    $pages = $p->sort($_['sort'], true)->chunk($_['chunk'], ($_['i'] ?? 1) - 1)->get();
    unset($p);
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] = $pages;
    $_['lot']['desk']['lot']['form']['lot'][2]['lot']['pager']['count'] = $count;
}

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url'] = $_['/'] . '/::s::/' . $_['path'] . $url->query('&', [
    'tab' => false,
    'type' => 'page/comment'
]) . $url->hash;

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['parent']['skip'] = true;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['skip'] = true;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['data']['skip'] = true;

return $_;
