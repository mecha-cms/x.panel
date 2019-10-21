<?php

$lot = require __DIR__ . DS . '..' . DS . 'files.php';

if (count($_['chops']) === 1) {
    $lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['hidden'] = true;
    $lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['file']['hidden'] = true;
    $lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['folder']['hidden'] = true;
    $pages = $files = [];
    $author = $user->user;
    foreach (g($_['f'], 'archive,draft,page', true) as $k => $v) {
        $files[$k] = basename($k);
    }
    asort($files);
    $before = $url . $_['/'] . '::';
    foreach (array_slice(array_keys($files), 0, $_['chunk']) as $k) {
        $page = new Comment($k);
        $after = '::' . strtr($k, [
            LOT => "",
            DS => '/'
        ]);
        $hidden = false;
        $kk = strtr(dirname($k), [COMMENT . DS => PAGE . DS]);
        if ($parent = File::exist([
            $kk . '.draft',
            $kk . '.page',
            $kk . '.archive'
        ])) {
            $test = (new Page($parent))['author'];
            $hidden = $test && $test !== $author;
        }
        $pages[$k] = [
            'path' => $k,
            'title' => _\lot\x\panel\h\w($page->author),
            'description' => _\lot\x\panel\h\w(To::excerpt($page->content)),
            'image' => $page->avatar(72),
            'author' => $page['author'],
            'type' => 'Page',
            'link' => ($x = $page->x) === 'draft' ? null : $page->url,
            'time' => $page->time . "",
            'tags' => [
                'is:' . $x,
                'type:comment'
            ],
            'tasks' => [
                's' => [
                    'title' => 'Reply',
                    'description' => 'Reply to ' . $page->author,
                    'icon' => 'M10,9V5L3,12L10,19V14.9C15,14.9 18.5,16.5 21,20C20,15 17,10 10,9Z',
                    'url' => $before . 's' . dirname($after) . $url->query('&', ['content' => 'page.comment', 'parent' => $page->name, 'tab' => false]) . $url->hash,
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
                    'url' => $before . 'l' . $after . $url->query('&', ['tab' => false, 'token' => $_['token']]),
                    'stack' => 30
                ]
            ],
            // Hide comment(s) that is not related to the page that is written by the current user
            'hidden' => $hidden
        ];
    }
    $lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['recent'] = [
        'lot' => [
            'comments' => [
                'type' => 'Pages',
                'lot' => $pages
            ]
        ],
        'stack' => 9.9
    ];
}

$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['title'] = 'All';

if (isset($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'])) {
    foreach ($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'] as $k => &$v) {
        // $v['tasks']['g']['hidden'] = true;
        $v['tasks']['l']['hidden'] = true;
    }
}

return $lot;