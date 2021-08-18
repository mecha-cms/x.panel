<?php

Hook::set('_', function($_) {
    if (
        empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['skip']) &&
        empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot']) &&
        isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type']) &&
        'pages' === $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type']
    ) {
        if (!is_dir($folder = LOT . DS . 'comment')) {
            return $_;
        }
        extract($GLOBALS, EXTR_SKIP);
        // `dechex(crc32('comments.info'))`
        if (is_file($f = LOT . DS . 'cache' . DS . '8bead58f.php')) {
            $info = (array) require $f;
            unlink($f);
        } else {
            $info = [0];
        }
        $d = strtr($_['f'], [
            $folder . DS => LOT . DS . 'page' . DS
        ]);
        $file = File::exist([
            $d . '.archive',
            $d . '.draft',
            $d . '.page'
        ]);
        $page = $file ? new Page($file) : new Page;
        $is_root = false === strpos($_['path'], '/');
        if (!$is_root && $file) {
            $_['lot']['desk']['lot']['form']['lot'][0]['title'] = i('Comment to %s', ['<a href="' . $_['/'] . '/::g::/' . strtr($file, [
                LOT . DS => "",
                DS => '/'
            ]) . '">' . $page->title . '</a>']);
            $_['lot']['desk']['lot']['form']['lot'][0]['description'] = $page->time->{r('-', '_', $state->language)};
            if (!$excerpt = $page->excerpt) {
                if ($excerpt = $page->description) {
                    $excerpt = '<p>' . $excerpt . '</p>';
                }
            }
            $_['lot']['desk']['lot']['form']['lot'][0]['content'] = $excerpt;
        }
        $files = $comments = [];
        $count = 0;
        $trash = $_['trash'] ? date('Y-m-d-H-i-s') : false;
        $status = $user->status;
        $author = $user->user;
        $anchor = $state->x->comment->anchor[2] ?? 'comment:%s';
        // `dechex(crc32('comments'))`
        if ($is_root && is_file($f = LOT . DS . 'cache' . DS . '5f9e962a.php')) {
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
            foreach ($files as $k => $v) {
                $kk = strtr(dirname($k), [
                    $folder . DS => LOT . DS . 'page' . DS
                ]);
                $skip = false;
                if ($page = File::exist([
                    $kk . '.archive',
                    $kk . '.draft',
                    $kk . '.page'
                ])) {
                    $test = (new Page($page))['author'];
                    $skip = $test && $test !== $author && $status !== 1;
                }
                if ($skip) {
                    continue;
                }
                $p = new Comment($k);
                $comments[$k] = [
                    'comment' => $p,
                    'time' => (string) ($p->time ?? "")
                ];
                ++$count;
            }
            $comments = new Anemon($comments);
            $comments->sort([-1, 'time'], true);
            $comments = $comments->chunk($_['chunk'] ?? 20, ($_['i'] ?? 1) - 1, true)->get();
            $before = $_['/'] . '/::';
            if ($comments) {
                foreach ($comments as $k => $v) {
                    $after = '::' . strtr($k, [
                        LOT => "",
                        DS => '/'
                    ]);
                    $p = $v['comment'];
                    $title = x\panel\to\w($p->author ?? "");
                    $description = To::excerpt(x\panel\to\w($p->content ?? ""));
                    $avatar = $p->avatar(72) ?? null;
                    $type = $p->type ?? null;
                    $x = $p->x ?? null;
                    $parent_count = 0;
                    $parent_max = $state->x->comment->page->deep ?? 0;
                    $pp = new Comment($p->path);
                    while ($parent = $pp['parent']) {
                        ++$parent_count;
                        if (!is_file($ff = dirname($pp->path) . DS . $parent . '.page')) {
                            break;
                        }
                        $pp = new Comment($ff);
                    }
                    $comments[$k] = [
                        'path' => $k,
                        'current' => !empty($_SESSION['_']['file'][$k]) || $info[0] > 0,
                        'title' => $title ? S . $title . S : null,
                        'description' => $description ? S . $description . S : null,
                        'image' => $avatar,
                        'time' => $v['time'],
                        'link' => 'draft' === $x ? null : $p->url . '#' . sprintf($anchor, $p->id),
                        'author' => $p['author'],
                        'tags' => [
                            'type:' . c2f($type) => !empty($type),
                            'x:' . $x => true
                        ],
                        'tasks' => [
                            'reply' => [
                                'active' => $can_reply = $parent_count < $parent_max,
                                'title' => 'Reply',
                                'description' => $can_reply ? ['Reply to %s', $title] : null,
                                'icon' => 'M10,9V5L3,12L10,19V14.9C15,14.9 18.5,16.5 21,20C20,15 17,10 10,9Z',
                                'url' => $can_reply ? $before . 's' . dirname($after) . $url->query('&', [
                                    'parent' => $p->name,
                                    'tab' => false,
                                    'type' => 'page/comment'
                                ]) . $url->hash : null,
                                'stack' => 9.9
                            ],
                            'g' => [
                                'title' => 'Edit',
                                'description' => 'Edit',
                                'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                                'url' => $before . 'g' . $after . $url->query('&', [
                                    'tab' => false
                                ]) . $url->hash,
                                'stack' => 10
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
                                'stack' => 20
                            ]
                        ]
                    ];
                    unset($ff, $p, $pp);
                    if (isset($_SESSION['_']['file'][$k])) {
                        unset($_SESSION['_']['file'][$k]);
                    }
                    --$info[0];
                }
                $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] = $comments;
                $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pager'] = [
                    'type' => 'pager',
                    'chunk' => $_['chunk'] ?? 20,
                    'count' => $count,
                    'current' => $_['i'] ?? 1,
                    'stack' => 20
                ];
            }
        }
    }
    return $_;
}, 10);

// `http://127.0.0.1/panel/::g::/comment/1`
$_['type'] = 'pages';

$_ = require __DIR__ . DS . '..' . DS . 'index.php';

$_['lot']['desk']['lot']['form']['lot'][1]['title'] = ($r = false === strpos($_['path'], '/')) ? ['Recent %s', 'Comments'] : null;
$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['title'] = 'Comments';

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url'] = $_['/'] . '/::s::/' . $_['path'] . $url->query('&', [
    'q' => false,
    'tab' => false,
    'type' => 'page/comment'
]) . $url->hash;

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['parent']['skip'] = true;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['skip'] = true;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['data']['skip'] = true;

return $_;
