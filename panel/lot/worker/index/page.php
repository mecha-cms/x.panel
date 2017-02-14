<?php

$__ = explode('/+/', $__path . '/');
$__key = isset($__[1]) ? To::key(rtrim($__[1], '/')) : null;

$__step = $__step - 1;
$__sort = $__state->sort;
$__chunk = $__state->chunk;
$__is_get = Request::is('get');
$__is_post = Request::is('post');
$__is_r = count($__chops) === 1;
$__is_pages = $__is_r || is_numeric(Path::B($url->path)) ? '/1' : ""; // Force index view by appending page offset to the end of URL
$__is_data = substr($__path, -2) === '/+' || strpos($__path, '/+/') !== false;

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
    '__is_parent_has_step' => false,
    '__is_pages' => $__is_pages,
    '__is_data' => $__is_data
];

extract(Lot::set($__seeds)->get(null, []));

Hook::set('__page.url', function($content, $lot) use($__state) {
    $s = Path::F($lot['path'], LOT);
    return rtrim(__url__('url') . '/' . $__state->path . '/::g::/' . ltrim(To::url($s), '/'), '/');
});

function fn_tags_set($__path) {
    if (!Message::$x) {
        global $language;
        // Create `kind.data` file…
        if ($s = Request::post('tags')) {
            $s = explode(',', $s);
            $__kinds = [];
            if (count($s) > 12) {
                Request::save('post');
                Message::error('max', [$language->tags, '<strong>12</strong>']);
            } else {
                foreach ($s as $v) {
                    $v = To::slug($v);
                    if (($id = From::tag($v)) !== false) {
                        $__kinds[] = $id;
                    } else {
                        $__o = 0;
                        foreach (glob(TAG . DS . '*' . DS . 'id.data', GLOB_NOSORT) as $vv) {
                            $id = (int) file_get_contents($vv);
                            if ($id > $__o) $__o = $id;
                        }
                        ++$__o;
                        $__kinds[] = $__o;
                        $f = TAG . DS . $v . DS;
                        File::write(date(DATE_WISE))->saveTo($f . 'time.data', 0600);
                        File::write($__o)->saveTo($f . 'id.data', 0600);
                        Page::data(['title' => $v])->saveTo($f . '.page', 0600);
                        Message::info('create', $language->tag . ' <em>' . str_replace('-', ' ', $v) . '</em>');
                    }
                }
                $__kinds = array_unique($__kinds);
                sort($__kinds);
                File::write(To::json($__kinds))->saveTo(Path::F($__path) . DS . 'kind.data', 0600);
            }
        } else {
            File::open(Path::F($__path) . DS . 'kind.data')->delete();
        }
    }
}

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

if ($__is_data) {
    $__folder_d = LOT . DS . $__[0];
    $__file_d = File::exist($__folder_d . DS . $__key . '.data');
    Lot::set('__page', [
        new Page(null, [], '__data'),
        new Page(null, [], 'data')
    ]);
    if ($__file = File::exist([
        $__folder_d . '.draft',
        $__folder_d . '.page',
        $__folder_d . '.archive'
    ])) {
        Lot::set('__source', [
            new Page($__file, [], '__page'),
            new Page($__file)
        ]);
    } else {
        Shield::abort(PANEL_404);
    }
    if ($__files = g($__folder_d, 'data')) {
        $__files = array_filter($__files, function($v) use($__key) {
            return Path::N($v) !== $__key;
        });
        foreach ($__files as $v) {
            $s = Path::N($v);
            $s = [
                'title' => $s,
                'key' => $s,
                'url' => $__state->path . '/::g::/' . $__[0] . '/+/' . $s
            ];
            $__datas[0][] = new Page(null, $s, '__data');
            $__datas[1][] = new Page(null, $s, 'data');
        }
        Lot::set('__datas', $__datas);
    }
    if ($__is_post) {
        if (Request::post('x') === 'trash') {
            Guardian::kick(str_replace('::g::', '::r::', $url->current . HTTP::query(['token' => Request::post('token')])));
        }
        $k = Request::post('key', "", false);
        $f = $__folder_d . DS . $k . '.data';
        if ($k !== $__key && file_exists($f)) {
            Request::save('post');
            Message::error('exist', [$language->key, '<em>' . $k . '</em>']);
        }
        Hook::NS('on.data.set', [$__file_d]);
        if (!Message::$x) {
            $__content = Request::post('content', "", false);
            if (is_array($__content)) {
                $__content = json_encode($__content);
            }
            File::write($__content)->saveTo($f, 0600);
            if ($k !== $__key) {
                File::open($__folder_d . DS . $__key . '.data')->delete();
            }
            Message::success(To::sentence($language->{($__sgr === 's' ? 'create' : 'update') . 'ed'}));
            Guardian::kick($__state->path . '/::g::/' . $__[0] . '/+/' . $k);
        }
    } else {
        if ($__sgr === 'r') {
            if (!Request::get('token')) {
                Shield::abort(PANEL_404);
            }
            Hook::NS('on.data.reset', [$__file_d]);
            if (Message::$x) {
                Guardian::kick(str_repace('::r::', '::g::', $url->current));
            }
            if (Request::get('abort')) {
                File::open($__folder_d . DS . $__key . '.trash')->renameTo($__key . '.data');
                Message::success(To::sentence($language->restoreed));
            } else {
                File::open($__file_d)->renameTo($__key . '.trash');
                Message::success(To::sentence($language->deleteed) . ' ' . HTML::a($language->restore, $url->path . HTTP::query(['abort' => 1]), false, ['classes' => ['right']]));
            }
            Guardian::kick($__state->path . '/::g::/' . $__[0]);
        } else if ($__file_d) {
            if ($__sgr === 'g') {
                $s = [
                    'key' => $__key,
                    'content' => file_get_contents($__folder_d . DS . $__key . '.data')
                ];
                Lot::set('__page', [
                    new Page(null, $s, '__data'),
                    new Page(null, $s, 'data')
                ]);
            } else if ($__sgr === 's') {
                Guardian::kick(str_replace('::s::', '::g::', $url->current));
            }
        } else if ($__sgr === 'g') {
            Shield::abort(PANEL_404);
        }
    }
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
            $x = Request::post('x', 'page');
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
            Hook::fire('on.page.set', [$fff]);
            fn_tags_set($fff);
            if (!Message::$x) {
                // Create `time.data` file…
                File::write(date(DATE_WISE))->saveTo($ff . DS . 'time.data', 0600);
                // Create `sort.data` file…
                if ($s = Request::post('sort')) {
                    File::write(To::json($s))->saveTo($ff . DS . 'sort.data', 0600);
                }
                // Create `chunk.data` file…
                if ($s = Request::post('chunk')) {
                    File::write($s)->saveTo($ff . DS . 'chunk.data', 0600);
                }
                Page::data($headers)->saveTo($fff, 0600);
                Message::success(To::sentence($language->{($x === 'draft' ? 'save' : 'create') . 'ed'}) . ($x === 'draft' ? "" : ' ' . HTML::a($language->view, Page::open($fff)->get('url'), true, ['classes' => ['right']])));
                Guardian::kick(str_replace('::s::', '::g::', $url->current) . '/' . $f);
            }
        }
        Lot::set('__page', [
            new Page(null, [], '__page'),
            new Page
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
            $s = Path::N($__file);
            $ss = Request::post('slug');
            $x = Path::X($__file);
            $xx = Request::post('x', $x);
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
            $f = Path::D($__file) . DS . $ss . '.' . $xx;
            Hook::fire('on.page.set', [$f]);
            fn_tags_set($f);
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
                File::write($s)->saveTo($dd . DS . 'time.data', 0600);
                // Create `sort.data` file…
                if ($s = Request::post('sort')) {
                    File::write(To::json($s))->saveTo($dd . DS . 'sort.data', 0600);
                }
                // Create `chunk.data` file…
                if ($s = Request::post('chunk')) {
                    File::write($s)->saveTo($dd . DS . 'chunk.data', 0600);
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
                    Message::info('search', '<em>' . $__queries . '</em>');
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
                        'key' => $s,
                        'url' => $__state->path . '/::g::/' . $__path . '/+/' . $s
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
        Hook::fire('on.page.reset', [$__file]);
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


/**
 * Field(s)
 * --------
 */

// [+] &#x2795;
// [-] &#x2796;
// [:] &#x2797;
// [x] &#x2716;