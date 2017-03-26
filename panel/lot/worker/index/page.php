<?php

Hook::set('__page.url', function($content, $lot) use($__state) {
    $s = Path::F($lot['path'], LOT);
    return rtrim(__url__('url') . '/' . $__state->path . '/::g::/' . ltrim(To::url($s), '/'), '/');
});

// `panel/::s::/page` → new page in `lot\page`
// `panel/::g::/page` → page(s) view
// `panel/::s::/page/blog` → new child page for `lot\page\blog`
// `panel/::g::/page/blog` → edit page of `lot\page\blog`

// `.data`
if ($__is_data) {
    $__folder = LOT . DS . $__[0];
    $__file = File::exist($__folder . DS . $__key . '.data');
    if (substr($__path, -2) === '/+') {
        $s = [];
    } else {
        $s = [
            'key' => $__key,
            'content' => File::open($__folder . DS . $__key . '.data')->read()
        ];
    }
    Lot::set('__page', [
        new Page(null, $s, '__data'),
        new Page(null, $s, 'data')
    ]);
    if ($__s = File::exist([
        $__folder . '.draft',
        $__folder . '.page',
        $__folder . '.archive'
    ])) {
        Lot::set('__source', [
            new Page($__s, [], '__page'),
            new Page($__s, [], 'page')
        ]);
    } else {
        Shield::abort(PANEL_404);
    }
    if ($__files = g($__folder, 'data')) {
        foreach (/* Anemon::eat($__files)->chunk($__chunk, 0) */ $__files as $k => $v) {
            $s = Path::N($v);
            if ($s === $__key) continue;
            $s = [
                'title' => $s,
                'key' => $s,
                'url' => $__state->path . '/::g::/' . rtrim(explode('/+/', $__path . '/')[0], '/') . '/+/' . $s
            ];
            $__datas[0][] = new Page(null, $s, '__data');
            $__datas[1][] = new Page(null, $s, 'data');
        }
        $__is_data_has_step = /* count($__files) > $__chunk */ false;
        Lot::set([
            '__datas' => $__datas,
            '__is_data_has_step' => $__is_data_has_step
        ]);
    }
    if ($__is_post) {
        if (Request::post('x') === 'trash') {
            Guardian::kick(str_replace('::g::', '::r::', $url->current . HTTP::query(['token' => Request::post('token')])));
        }
        $k = Request::post('key', "", false);
        $f = $__folder . DS . $k . '.data';
        if ($k !== $__key && file_exists($f)) {
            Request::save('post');
            Message::error('exist', [$language->key, '<em>' . $k . '</em>']);
        }
        Hook::NS('on.data.set', [$__file]);
        if (!Message::$x) {
            $__content = Request::post('content', "", false);
            if (is_array($__content)) {
                $__content = json_encode($__content);
            }
            File::write($__content)->saveTo($f, 0600);
            if ($k !== $__key) {
                File::open($__folder . DS . $__key . '.data')->delete();
            }
            Message::success(To::sentence($language->{($__sgr === 's' ? 'create' : 'update') . 'ed'}));
            Guardian::kick($__state->path . '/::g::/' . $__[0] . '/+/' . $k);
        }
    } else {
        if ($__sgr === 'r') {
            if (!Request::get('token')) {
                Shield::abort(PANEL_404);
            }
            Hook::NS('on.data.reset', [$__file]);
            if (Message::$x) {
                Guardian::kick(str_repace('::r::', '::g::', $url->current));
            }
            if (Request::get('abort')) {
                File::open($__folder . DS . $__key . '.trash')->renameTo($__key . '.data');
                Message::success(To::sentence($language->restoreed));
            } else {
                File::open($__file)->renameTo($__key . '.trash');
                Message::success(To::sentence($language->deleteed) . ' ' . HTML::a($language->restore, $url->path . HTTP::query(['abort' => 1]), false, ['classes' => ['right']]));
            }
            Guardian::kick($__state->path . '/::g::/' . $__[0]);
        } else if ($__file) {
            if ($__sgr === 'g') {
                $__folder = LOT . DS . $__[0];
                $__file = File::exist($__folder . DS . $__key . '.data');
                if (substr($__path, -2) === '/+') {
                    $s = [];
                } else {
                    $s = [
                        'key' => $__key,
                        'content' => file_get_contents($__folder . DS . $__key . '.data')
                    ];
                }
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
// `.{draft,page,archive}`
} else {
    if ($__sgr === 's') {
        if ($__is_post && !Message::$x) {
            $x = Request::post('x', 'page');
            $f = Request::post('slug');
            $ff = $__folder . DS . $f;
            $fff = $ff . '.' . $x;
            $headers = [
                'title' => false,
                'description' => false,
                'author' => false,
                'type' => false,
                'link' => false,
                'content' => false
            ];
            foreach ($headers as $k => $v) {
                if (file_exists($ff . DS . $k . '.data')) continue;
                $headers[$k] = Request::post($k, $v);
            }
            if (File::exist([
                $ff . '.draft',
                $ff . '.page',
                $ff . '.archive'
            ])) {
                Request::save('post');
                Message::error('exist', [$language->slug, '<em>' . $f . '</em>']);
            }
            Hook::fire('on.page.set', [$fff]);
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
            new Page()
        ]);
        if (!$__is_r) {
            if ($__files = Get::pages($__folder, 'draft,page,archive', $__sort, 'path')) {
                foreach (Anemon::eat($__files)->chunk($__chunk, 0) as $v) {
                    $__childs[0][] = new Page($v, [], '__page');
                    $__childs[1][] = new Page($v, [], 'page');
                }
                $__is_child_has_step = count($__files) > $__chunk;
                Lot::set([
                    '__childs' => $__childs,
                    '__is_child_has_step' => $__is_child_has_step
                ]);
            }
            $__folder = Path::D($__folder);
        }
        if ($__files = Get::pages(Path::D($__folder), 'draft,page,archive', $__sort, 'path')) {
            $__name = Path::B($__path);
            $__files = array_filter($__files, function($v) use($__name) {
                return Path::N($v) !== $__name;
            });
            foreach (Anemon::eat($__files)->chunk($__chunk, 0) as $v) {
                $__kins[0][] = new Page($v, [], '__page');
                $__kins[1][] = new Page($v, [], 'page');
            }
            $__is_kin_has_step = count($__files) > $__chunk;
            Lot::set([
                '__kins' => $__kins,
                '__is_kin_has_step' => $__is_kin_has_step
            ]);
        }
    } else if ($__sgr === 'g') {
        if ($__is_post && !Message::$x) {
            if (Request::post('x') === 'trash') {
                Guardian::kick(str_replace('::g::', '::r::', $url->current) . HTTP::query(['token' => Request::post('token')]));
            }
            $s = Path::N($__file);
            $ss = Request::post('slug');
            $x = Path::X($__file);
            $xx = Request::post('x', $x);
            $d = Path::D($__file);
            $dd = $d . DS . $ss;
            $ddd = $dd . '.' . $xx;
            $headers = [
                'title' => false,
                'description' => false,
                'author' => false,
                'type' => false,
                'link' => false,
                'content' => false
            ];
            foreach ($headers as $k => $v) {
                if (file_exists($dd . DS . $k . '.data')) continue;
                $headers[$k] = Request::post($k, $v);
            }
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
                    $s = DateTime::createFromFormat('Y/m/d H:i:s', $s)->format();
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
            $__name = Path::B($__path);
            $__files = array_filter($__files, function($v) use($__name) {
                return Path::N($v) !== $__name;
            });
            foreach (Anemon::eat($__files)->chunk($__chunk, 0) as $v) {
                $__kins[0][] = new Page($v, [], '__page');
                $__kins[1][] = new Page($v, [], 'page');
            }
            $__is_kin_has_step = count($__files) > $__chunk;
            Lot::set([
                '__kins' => $__kins,
                '__is_kin_has_step' => $__is_kin_has_step
            ]);
        }
        $__folder_parent = Path::D($__file);
        if ($__file_parent = File::exist([
            $__folder_parent . '.draft',
            $__folder_parent . '.page',
            $__folder_parent . '.archive'
        ])) {
            $__parent = [
                new Page($__file_parent, [], '__page'),
                new Page($__file_parent, [], 'page')
            ];
            Lot::set('__parent', $__parent);
        }
        if ($__is_pages) {
            $site->is = 'pages';
            if ($__files = Get::pages($__folder, 'draft,page,archive', $__sort, 'path')) {
                if ($__query = l(Request::get('q', ""))) {
                    Message::info('search', '<em>' . $__query . '</em>');
                    $__query = explode(' ', $__query);
                    $__files = array_filter($__files, function($v) use($__query) {
                        $v = Path::N($v);
                        foreach ($__query as $__q) {
                            if (strpos($v, $__q) !== false) {
                                return true;
                            }
                        }
                        return false;
                    });
                }
                foreach (Anemon::eat($__files)->chunk($__chunk, $__step) as $v) {
                    $__pages[0][] = new Page($v, [], '__page');
                    $__pages[1][] = new Page($v, [], 'page');
                }
                $__is_page_has_step = count($__files) > $__chunk;
                Lot::set([
                    '__pages' => $__pages,
                    '__is_page_has_step' => $__is_page_has_step
                ]);
            }
            Lot::set([
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
                ], '__pages')]
            ]);
        } else {
            if ($__file === $__folder) {
                Shield::abort(PANEL_404);
            }
            if ($__file = File::exist([
                $__folder . '.draft',
                $__folder . '.page',
                $__folder . '.archive'
            ])) {
                $__page = [
                    new Page($__file, [], '__page'),
                    new Page($__file, [], 'page')
                ];
            }

            Lot::set('__page', $__page);
            if ($__files = Get::pages($__folder, 'draft,page,archive', $__sort, 'path')) {
                foreach (Anemon::eat($__files)->chunk($__chunk, 0) as $v) {
                    $__childs[0][] = new Page($v, [], '__page');
                    $__childs[1][] = new Page($v, [], 'page');
                }
                $__is_child_has_step = count($__files) > $__chunk;
                Lot::set([
                    '__childs' => $__childs,
                    '__is_child_has_step' => $__is_child_has_step
                ]);
            }
            if ($__files = g($__folder, 'data')) {
                foreach (/* Anemon::eat($__files)->chunk($__chunk, 0) */ $__files as $k => $v) {
                    $s = Path::N($v);
                    if ($s === $__key) continue;
                    $s = [
                        'title' => $s,
                        'key' => $s,
                        'url' => $__state->path . '/::g::/' . rtrim(explode('/+/', $__path . '/')[0], '/') . '/+/' . $s
                    ];
                    $__datas[0][] = new Page(null, $s, '__data');
                    $__datas[1][] = new Page(null, $s, 'data');
                }
                $__is_data_has_step = /* count($__files) > $__chunk */ false;
                Lot::set([
                    '__datas' => $__datas,
                    '__is_data_has_step' => $__is_data_has_step
                ]);
            }
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