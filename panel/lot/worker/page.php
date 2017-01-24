<?php

$step = $step - 1;
$sort = $state['sort'];
$chunk = $state['chunk'];
$is_get = Request::is('get');
$is_post = Request::is('post');
$is_r = count($chops) === 1;
$is_pages = $is_r || is_numeric(Path::B($url->path)); // Force index view by appending page offset to the end of URL

Panel::set('page.types.HTML', 'HTML');

Hook::set('::' . $sgr . '::page.url', function($content, $lot) use($state) {
    $s = Path::F($lot['path'], LOT);
    return rtrim(__url__('url') . '/' . $state['path'] . '/::g::/' . ltrim(To::url($s), '/'), '/');
});


// `panel/::s::/page` → new page in `lot\page`
// `panel/::g::/page` → page(s) view
// `panel/::s::/page/blog` → new child page for `lot\page\blog`
// `panel/::g::/page/blog` → edit page in `lot\page\blog`

$folder = LOT . DS . $path;
$file = File::exist([
    $folder . '.draft',
    $folder . '.page',
    $folder . '.archive'
], $folder);

$seeds = [
    'child' => [[], []],
    'data' => [[], []],
    'kin' => [[], []],
    'page' => [[], []],
    'parent' => [[], []],
    // Why “child(s)” and “data(s)”? Please open `lot\language\en-us.page` for more info
    'childs' => [[], []],
    'datas' => [[], []],
    'kins' => [[], []],
    'pages' => [[], []],
    'parents' => [[], []],
    'pager' => [[], []],
    'is_child_has_step' => false,
    'is_data_has_step' => false,
    'is_kin_has_step' => false,
    'is_page_has_step' => false,
    'is_parent_has_step' => false
];

Lot::set($seeds);

extract($seeds);

if (substr($path, -3) === '/d+' || strpos($path, '/d:') !== false) {
    
} else {
    if ($sgr === 's') {
        if ($is_post && !Message::$x) {
            $headers = [
                'title' => false,
                'description' => false,
                'author' => false,
                'type' => false,
                'link' => false,
                'content' => false
            ];
            foreach ($headers as $k => $v) {
                $headers[$k] = Request::post($k, $v);
            }
            $x = Request::post('x');
            $f = Request::post('slug');
            $ff = $folder . DS . $f;
            $fff = $ff . '.' . $x;
            File::write(date(DATE_WISE))->saveTo($ff . DS . 'time.data');
            Page::data($headers)->saveTo($fff, 0600);
            Message::success($language->{($x === 'draft' ? 'save' : 'create') . 'ed'} . '.' . ($x === 'draft' ? "" : ' ' . HTML::a($language->view, Page::open($fff)->get('url'), true, ['classes' => ['right']])));
            Guardian::kick(str_replace('::s::', '::g::', $url->current) . '/' . $f);
        }
        $title = (new Date())->{str_replace('-', '_', $site->language)};
        Lot::set('page', [
            new Page(null, [
                'title' => $title
            ], '::' . $sgr . '::page'),
            new Page(null, [
                'title' => $title,
                'slug' => end($chops)
            ])
        ]);
        if (!$is_r) {
            if ($files = Get::pages($folder, 'draft,page,archive', $sort, 'path')) {
                foreach (Anemon::eat($files)->chunk($chunk, 0) as $k => $v) {
                    $childs[0][] = new Page($v, [], '::' . $sgr . '::page');
                    $childs[1][] = new Page($v);
                }
            }
            $is_child_has_step = count($files) > $chunk;
            $folder = Path::D($folder);
        }
        if ($files = Get::pages($folder, 'draft,page,archive', $sort, 'path')) {
            $s = Path::N($file);
            $files = array_filter($files, function($v) use($s) {
                return Path::N($v) !== $s;
            });
            foreach (Anemon::eat($files)->chunk($chunk, 0) as $k => $v) {
                $kins[0][] = new Page($v, [], '::' . $sgr . '::page');
                $kins[1][] = new Page($v);
            }
        }
        $is_kin_has_step = count($files) > $chunk;
        Lot::set([
            'childs' => $childs,
            'kins' => $kins,
            'is_child_has_step' => $is_child_has_step,
            'is_kin_has_step' => $is_kin_has_step
        ]);
    } else if ($sgr === 'g') {
        if ($is_post && !Message::$x) {
            if (Request::post('x') === 'trash') {
                Guardian::kick(str_replace('::g::', '::r::', $url->current) . HTTP::query(['token' => Request::post('token')]));
            }
            $headers = [
                'title' => false,
                'description' => false,
                'author' => false,
                'type' => false,
                'link' => false,
                'content' => false
            ];
            foreach ($headers as $k => $v) {
                $headers[$k] = Request::post($k, $v);
            }
            $x = Path::X($file);
            $xx = Request::post('x');
            $f = Path::F($file);
            $ff = $f . '.' . $xx;
            Page::open($file)->data($headers)->saveTo($ff, 0600);
            if ($x !== $xx) File::open($f . '.' . $x)->delete();
            Message::success($language->updateed . '.' . ($xx === 'draft' ? "" : ' ' . HTML::a($language->view, Page::open($ff)->get('url'), true, ['classes' => ['right']])));
            Guardian::kick();
        }
        if ($files = Get::pages(Path::D($folder), 'draft,page,archive', $sort, 'path')) {
            $s = Path::N($file);
            $files = array_filter($files, function($v) use($s) {
                return Path::N($v) !== $s;
            });
            foreach (Anemon::eat($files)->chunk($chunk, 0) as $k => $v) {
                $kins[0][] = new Page($v, [], '::' . $sgr . '::page');
                $kins[1][] = new Page($v);
            }
        }
        $is_kin_has_step = count($files) > $chunk;
        $folder_parent = Path::D($file);
        if ($file_parent = File::exist([
            $folder_parent . '.draft',
            $folder_parent . '.page',
            $folder_parent . '.archive'
        ])) {
            $parents[0][0] = new Page($file_parent, [], '::' . $sgr . '::page');
            $parents[1][0] = new Page($file_parent);
        }
        Lot::set([
            'kins' => $kins,
            'parents' => $parents,
            'is_kin_has_step' => $is_kin_has_step
        ]);
        if ($is_r || $is_pages) {
            $site->type = 'pages';
            if ($files = Get::pages($folder, 'draft,page,archive', $sort, 'path')) {
                if ($queries = l(Request::get('q', ""))) {
                    $files = array_filter($files, function($v) use($queries) {
                        $v = Path::N($v);
                        foreach (explode(' ', $queries) as $query) {
                            if (strpos($v, $query) !== false) {
                                return true;
                            }
                        }
                        return false;
                    });
                    Message::info('Search results for <em>' . $queries . '</em>. ' . HTML::a($language->doed, $url->current, false, ['classes' => ['right']]));
                }
                foreach (Anemon::eat($files)->chunk($chunk, 0) as $k => $v) {
                    $pages[0][] = new Page($v, [], '::' . $sgr . '::page');
                    $pages[1][] = new Page($v);
                }
            }
            Lot::set([
                'pages' => $pages,
                'pager' => [new Elevator($files ?: [], $chunk, $step, $url . '/' . $state['path'] . '/::' . $sgr . '::/' . $path, [
                    'direction' => [
                       '-1' => 'previous',
                        '0' => false,
                        '1' => 'next'
                    ],
                    'union' => [
                       '-2' => [
                            2 => ['rel' => null, 'classes' => ['button', 'x']]
                        ],
                       '-1' => [
                            1 => '&#x276E;',
                            2 => ['rel' => 'prev', 'classes' => ['button']]
                        ],
                        '1' => [
                            1 => '&#x276F;',
                            2 => ['rel' => 'next', 'classes' => ['button']]
                        ]
                    ]
                ], '::' . $sgr . '::pages')],
                'is_page_has_step' => count($files) > $chunk
            ]);
        } else {
            Lot::set('page', [
                new Page($file, [], '::' . $sgr . '::page'),
                new Page($file)
            ]);
            if ($files = Get::pages($folder, 'draft,page,archive', $sort, 'path')) {
                foreach (Anemon::eat($files)->chunk($chunk, 0) as $k => $v) {
                    $childs[0][] = new Page($v, [], '::' . $sgr . '::page');
                    $childs[1][] = new Page($v);
                }
            }
            if ($files = g($folder, 'data')) {
                foreach (Anemon::eat($files)->chunk($chunk, 0) as $k => $v) {
                    $s = Path::N($v);
                    $s = [
                        'title' => $s,
                        'key' => $s
                    ];
                    $datas[0][] = new Page(null, $s, '::' . $sgr . '::data');
                    $datas[1][] = new Page(null, $s, 'data');
                }
            }
            Lot::set([
                'childs' => $childs,
                'datas' => $datas,
                'is_child_has_step' => count($files) > $chunk,
                'is_data_has_step' => count($files) > $chunk
            ]);
        }
    } else if ($sgr === 'r') {
        if (!$token = Request::get('token')) {
            Shield::abort();
        }
        if (!$file = File::exist([
            $folder . '.draft',
            $folder . '.page',
            $folder . '.archive',
            $folder . '.trash'
        ])) {
            Shield::abort();
        }
        $kick = str_replace('::r::', '::g::', $url->path);
        $name = Path::B($folder);
        if (Message::$x) {
            Guardian::kick($kick);
        }
        if (Request::get('abort')) {
            File::open($folder . '.trash')->renameTo($name . '.draft');
            Message::success('Restored.');
        } else {
            File::open($file)->renameTo($name . '.trash');
            Message::success($language->deleteed . '. ' . HTML::a($language->restore, $url->path . HTTP::query(['abort' => 1]), false, ['classes' => ['right']]));
        }
        Guardian::kick(Path::D($kick) . '/1');
    } else {
        Shield::abort();
    }
}