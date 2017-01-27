<?php

$__step = $__step - 1;
$__sort = $__state->sort;
$__chunk = $__state->chunk;
$__is_get = Request::is('get');
$__is_post = Request::is('post');
$__is_r = count($__chops) === 1;
$__is_pages = $__is_r || is_numeric(Path::B($url->path)); // Force index view by appending page offset to the end of URL

Panel::set('f.types.HTML', 'HTML');

Hook::set('__page.url', function($content, $lot) use($__state) {
    $s = Path::F($lot['path'], LOT);
    return rtrim(__url__('url') . '/' . $__state->path . '/::g::/' . ltrim(To::url($s), '/'), '/');
});

// `panel/::s::/page` → new page in `lot\page`
// `panel/::g::/page` → page(s) view
// `panel/::s::/page/blog` → new child page for `lot\page\blog`
// `panel/::g::/page/blog` → edit page of `lot\page\blog`

$__folder = LOT . DS . $__path;
$__file = File::exist([
    $__folder . '.draft',
    $__folder . '.page',
    $__folder . '.archive'
], $__folder);

$__seeds = [
    '__child' => [[], []],
    '__data' => [[], []],
    '__kin' => [[], []],
    '__page' => [[], []],
    '__parent' => [[], []],
    // Why “child(s)” and “data(s)”? Please open `lot\language\en-us.page` for more info
    '__childs' => [[], []],
    '__datas' => [[], []],
    '__kins' => [[], []],
    '__pages' => [[], []],
    '__parents' => [[], []],
    '__pager' => [[], []],
    '__is_child_has_step' => false,
    '__is_data_has_step' => false,
    '__is_kin_has_step' => false,
    '__is_page_has_step' => false,
    '__is_parent_has_step' => false
];

extract(Lot::set($__seeds)->get(null, []));

if (substr($__path, -3) === '/d+' || strpos($__path, '/d:') !== false) {
    
} else {
    if ($__sgr === 's') {
        if ($__is_post && !Message::$x) {
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
            if (is_string($headers['description']) && strpos($headers['description'], "\n") !== false) {
                $headers['description'] = To::json($headers['description']);
            }
            $x = Request::post('x');
            $f = Request::post('slug');
            $ff = $__folder . DS . $f;
            $fff = $ff . '.' . $x;
            if (File::exist([
                $ff . '.draft',
                $ff . '.page',
                $ff . '.archive'
            ])) {
                Request::save('post');
                Message::error('exist', [$language->slug, '<em>' . $f . '</em>']);
            }
            if (!Message::$x) {
                // Create `time.data` file…
                File::write(date(DATE_WISE))->saveTo($ff . DS . 'time.data');
                // Create `sort.data` file…
                if ($s = Request::post('sort')) {
                    File::write(To::json($s))->saveTo($ff . DS . 'sort.data');
                }
                // Create `chunk.data` file…
                if ($s = Request::post('chunk')) {
                    File::write($s)->saveTo($ff . DS . 'chunk.data');
                }
                Page::data($headers)->saveTo($fff, 0600);
                Message::success(To::sentence($language->{($x === 'draft' ? 'save' : 'create') . 'ed'}) . ($x === 'draft' ? "" : ' ' . HTML::a($language->view, Page::open($fff)->get('url'), true, ['classes' => ['right']])));
                Guardian::kick(str_replace('::s::', '::g::', $url->current) . '/' . $f);
            }
        }
        $title = (new Date())->{str_replace('-', '_', $site->language)};
        Lot::set('__page', [
            new Page(null, [
                'title' => $title
            ], '__page'),
            new Page(null, [
                'title' => $title,
                'slug' => end($__chops)
            ])
        ]);
        if (!$__is_r) {
            if ($__files = Get::pages($__folder, 'draft,page,archive', $__sort, 'path')) {
                foreach (Anemon::eat($__files)->chunk($__chunk, 0) as $k => $v) {
                    $__childs[0][] = new Page($v, [], '__page');
                    $__childs[1][] = new Page($v);
                }
            }
            $__is_child_has_step = count($__files) > $__chunk;
            $__folder = Path::D($__folder);
        }
        if ($__files = Get::pages($__folder, 'draft,page,archive', $__sort, 'path')) {
            $s = Path::N($__file);
            $__files = array_filter($__files, function($v) use($s) {
                return Path::N($v) !== $s;
            });
            foreach (Anemon::eat($__files)->chunk($__chunk, 0) as $k => $v) {
                $__kins[0][] = new Page($v, [], '__page');
                $__kins[1][] = new Page($v);
            }
        }
        $__is_kin_has_step = count($__files) > $__chunk;
        Lot::set([
            '__childs' => $__childs,
            '__kins' => $__kins,
            '__is_child_has_step' => $__is_child_has_step,
            '__is_kin_has_step' => $__is_kin_has_step
        ]);
    } else if ($__sgr === 'g') {
        if ($__is_post && !Message::$x) {
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
            if (is_string($headers['description']) && strpos($headers['description'], "\n") !== false) {
                $headers['description'] = To::json($headers['description']);
            }
            $s = Path::N($__file);
            $ss = Request::post('slug');
            $x = Path::X($__file);
            $xx = Request::post('x');
            $d = Path::D($__file);
            $dd = $d . DS . $ss;
            $ddd = $dd . '.' . $xx;
            if ($s !== $ss && File::exist([
                $dd . '.draft',
                $dd . '.page',
                $dd . '.archive'
            ])) {
                Request::save('post');
                Message::error('exist', [$language->slug, '<em>' . $ss . '</em>']);
            }
            if (!Message::$x) {
                Page::open($__file)->data($headers)->save(0600);
                if ($s !== $ss || $x !== $xx) {
                    // Rename folder…
                    if ($s !== $ss) {
                        File::open(Path::F($__file))->renameTo($ss);
                    }
                    // Rename file…
                    File::open($__file)->renameTo($ss . '.' . $xx);
                }
                // Create `time.data` file…
                if (!$s = Request::post('time')) {
                    $s = date(DATE_WISE);
                } else {
                    $s = DateTime::createFromFormat('Y/m/d H:i:s', $s)->format(DATE_WISE);
                }
                File::write($s)->saveTo($dd . DS . 'time.data');
                // Create `sort.data` file…
                if ($s = Request::post('sort')) {
                    File::write(To::json($s))->saveTo($dd . DS . 'sort.data');
                }
                // Create `chunk.data` file…
                if ($s = Request::post('chunk')) {
                    File::write($s)->saveTo($dd . DS . 'chunk.data');
                }
                Message::success(To::sentence($language->updateed) . ($xx === 'draft' ? "" : ' ' . HTML::a($language->view, Page::open($ddd)->get('url'), true, ['classes' => ['right']])));
                Guardian::kick(Path::D($url->current) . '/' . $ss);
            }
        }
        if ($__files = Get::pages(Path::D($__folder), 'draft,page,archive', $__sort, 'path')) {
            $s = Path::N($__file);
            $__files = array_filter($__files, function($v) use($s) {
                return Path::N($v) !== $s;
            });
            foreach (Anemon::eat($__files)->chunk($__chunk, 0) as $k => $v) {
                $__kins[0][] = new Page($v, [], '__page');
                $__kins[1][] = new Page($v);
            }
        }
        $__is_kin_has_step = count($__files) > $__chunk;
        $__folder_parent = Path::D($__file);
        if ($__file_parent = File::exist([
            $__folder_parent . '.draft',
            $__folder_parent . '.page',
            $__folder_parent . '.archive'
        ])) {
            $__parents[0][0] = new Page($__file_parent, [], '__page');
            $__parents[1][0] = new Page($__file_parent);
        }
        Lot::set([
            '__kins' => $__kins,
            '__parents' => $__parents,
            '__is_kin_has_step' => $__is_kin_has_step,
            // '__is_parent_has_step' => $__is_parent_has_step
        ]);
        if ($__is_r || $__is_pages) {
            $site->type = 'pages';
            if ($__files = Get::pages($__folder, 'draft,page,archive', $__sort, 'path')) {
                if ($__queries = l(Request::get('q', ""))) {
                    $__files = array_filter($__files, function($v) use($__queries) {
                        $v = Path::N($v);
                        foreach (explode(' ', $__queries) as $__query) {
                            if (strpos($v, $__query) !== false) {
                                return true;
                            }
                        }
                        return false;
                    });
                    Message::info('search', $__queries);
                }
                foreach (Anemon::eat($__files)->chunk($__chunk, 0) as $k => $v) {
                    $__pages[0][] = new Page($v, [], '__page');
                    $__pages[1][] = new Page($v);
                }
            }
            Lot::set([
                '__pages' => $__pages,
                '__pager' => [new Elevator($__files ?: [], $__chunk, $__step, $url . '/' . $__state->path . '/::g::/' . $__path, [
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
                ], '__pages')],
                '__is_page_has_step' => count($__files) > $__chunk
            ]);
        } else {
            if ($__file === $__folder) {
                Shield::abort(PANEL_404);
            }
            Lot::set('__page', [
                new Page($__file, [], '__page'),
                new Page($__file)
            ]);
            if ($__files = Get::pages($__folder, 'draft,page,archive', $__sort, 'path')) {
                foreach (Anemon::eat($__files)->chunk($__chunk, 0) as $k => $v) {
                    $__childs[0][] = new Page($v, [], '__page');
                    $__childs[1][] = new Page($v);
                }
            }
            $__is_child_has_step = count($__files) > $__chunk;
            if ($__files = g($__folder, 'data')) {
                foreach (/* Anemon::eat($__files)->chunk($__chunk, 0) */ $__files as $k => $v) {
                    $s = Path::N($v);
                    $s = [
                        'title' => $s,
                        'key' => $s
                    ];
                    $__datas[0][] = new Page(null, $s, '__data');
                    $__datas[1][] = new Page(null, $s, 'data');
                }
            }
            $__is_data_has_step = /* count($__files) > $__chunk */ false;
            Lot::set([
                '__childs' => $__childs,
                '__datas' => $__datas,
                '__parents' => $__parents,
                '__is_child_has_step' => $__is_child_has_step,
                '__is_data_has_step' => $__is_data_has_step
            ]);
        }
    } else if ($__sgr === 'r') {
        if (!Request::get('token')) {
            Shield::abort(PANEL_404);
        }
        if (!$__file = File::exist([
            $__folder . '.draft',
            $__folder . '.page',
            $__folder . '.archive',
            $__folder . '.trash'
        ])) {
            Shield::abort(PANEL_404);
        }
        $__kick = str_replace('::r::', '::g::', $url->path);
        $__name = Path::B($__folder);
        if (Message::$x) {
            Guardian::kick($__kick);
        }
        if (Request::get('abort')) {
            File::open($__folder . '.trash')->renameTo($__name . '.draft');
            Message::success(To::sentence($language->restoreed));
        } else {
            File::open($__file)->renameTo($__name . '.trash');
            Message::success(To::sentence($language->deleteed) . ' ' . HTML::a($language->restore, $url->path . HTTP::query(['abort' => 1]), false, ['classes' => ['right']]));
        }
        Guardian::kick(Path::D($__kick) . '/1');
    } else {
        Shield::abort(PANEL_404);
    }
}