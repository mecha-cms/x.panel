<?php

$__step = $__step - 1;
$__sort = $__state->sort;
$__chunk = $__state->chunk;
$__is_get = Request::is('get');
$__is_post = Request::is('post');
$__is_r = count($__chops) === 1;
$__is_pages = $__is_r || is_numeric(Path::B($url->path)) ? '/1' : ""; // Force index view by appending page offset to the end of URL

Panel::set('f.types.HTML', 'HTML');

Panel::set('f.sorts', [
    'time' => '<em>time</em>',
    'slug' => '<em>slug</em>',
    'update' => '<em>update</em>'
]);

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
    '__is_parent_has_step' => false,
    '__is_pages' => $__is_pages
];

extract(Lot::set($__seeds)->get(null, []));

if (substr($__path, -3) === '/d+' || strpos($__path, '/d:') !== false) {
    $__key = explode(':', end($__chops) . ':')[1];
    $__folder_d = LOT . DS . Path::F(Path::D($__path));
    if ($__file = File::exist([
        $__folder_d . '.draft',
        $__folder_d . '.page',
        $__folder_d . '.archive'
    ])) {
        $__page = [
            new Page($__file, [], '__page'),
            new Page($__file)
        ];
        Lot::set('__page', $__page);
    } else {
        Shield::abort(PANEL_404);
    }
    $__file_d = File::exist($__folder_d . DS . $__key . '.data');
    if ($__is_post) {
        if (Request::post('x') === 'trash') {
            Guardian::kick(str_replace('::g::', '::r::', $url->current . HTTP::query(['token' => Request::post('token')])));
        }
        $k = Request::post('key');
        if ($k !== $__key && file_exists($__folder_d . DS . $k . '.data')) {
            Request::save('post');
            Message::error('exist', [$language->key, '<em>' . $k . '</em>']);
        }
        Hook::NS('on.data.set', [$__file_d]);
        if (!Message::$x) {
            File::write(Request::post('content', "", false))->saveTo($__folder_d . DS . $k . '.data', 0600);
            if ($k !== $__key) {
                File::open($__folder_d . DS . $__key . '.data')->delete();
            }
            Message::success(To::sentence($language->{($__sgr === 's' ? 'create' : 'update') . 'ed'}));
            Guardian::kick($__state->path . '/::g::/' . Path::D($__path) . '/d:' . $k);
        }
    } else if ($__sgr === 'r') {
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
        Guardian::kick($__state->path . '/::g::/' . Path::D($__path));
    }
    if ($__sgr === 'g' && $__file_d) {
        $__ = [
            'key' => $__key,
            'content' => file_get_contents($__file_d)
        ];
        $__data = [
            new Page(null, $__, '__data'),
            new Page(null, $__, 'data')
        ];
        Lot::set('__data', $__data);
    } else if ($__sgr === 's') {
        if ($__file_d) {
            Guardian::kick(str_replace('::s::', '::g::', $url->current));
        }
        $__ = ['key' => $__key, 'content'];
        $__data = [
            new Page(null, $__, '__data'),
            new Page(null, $__, 'data')
        ];
        Lot::set('__data', $__data);
    } else {
        Shield::abort(PANEL_404);
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
            Hook::fire('on.page.set', [Path::D($__file) . DS . $ss . '.' . $xx]);
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

function panel_m_page() {
    extract(Lot::get(null, []));
    echo '<fieldset>';
    echo '<legend>' . $language->editor . '</legend>';
    Hook::fire('panel.m.editor');
    echo '</fieldset>';
    panel_f_state();
}

function panel_m_pages() {
    extract(Lot::get(null, []));
    echo '<section class="main-buttons">';
    echo '<p>';
    if (Request::get('q')) {
        $__links = [HTML::a('&#x2716; ' . $language->doed, $__state->path . '/::g::/' . $__path . $__is_pages, false, ['classes' => ['button', 'reset']])];
    } else {
        $__links = [HTML::a('&#x2795; ' . $language->page, $__state->path . '/::s::/' . $__path, false, ['classes' => ['button', 'set']])];
    }
    echo implode(' ', Hook::fire('panel.a.pages', [$__links]));
    echo '</p>';
    echo '</section>';
    echo '<section class="main-pages">';
    if ($__pages[0]) {
        $p = strpos($__path, '/') !== false ? substr($__path, strpos($__path, '/')) : "";
        foreach ($__pages[1] as $k => $v) {
            $s = $__pages[0][$k]->url;
            $__is_parent = !!g(LOT . explode('::' . $__sgr . '::', $s, 2)[1], 'draft,page,archive', "", false);
            $g = $__pages[0][$k]->path;
            $gg = Path::X($g);
            $ggg = Path::D($g);
            $gggg = Path::N($g) === Path::N($ggg) && file_exists($ggg . '.' . $gg); // fade the placeholder page
            echo '<article class="page on-' . $v->state . ($__is_parent ? ' is-parent' : "") . ($gggg ? ' as-placeholder' : "") . ($site->path === ltrim($p . '/' . $v->slug, '/') ? ' as-home' : "") . '" id="page-' . $v->id . '">';
            echo '<header>';
            if ($__pages[0][$k]->state === 'draft') {
                echo '<h3>' . $v->title . '</h3>';
            } else {
                echo '<h3>' . HTML::a($v->title, $v->url, true) . '</h3>';
            }
            echo '</header>';
            echo '<section>';
            echo '<p>' . To::snippet($v->description, true, $__state->snippet) . '</p>';
            echo '</section>';
            echo '<footer>';
            echo '<p>';

            $__links = [
                HTML::a($language->edit, $s),
                HTML::a($language->delete, str_replace('::g::', '::r::', $s) . HTTP::query(['token' => $__token]))
            ];

            if ($__is_parent) {
                $__links[] = HTML::a($language->open, $s . '/1');
            }

            if ($v->link) {
                $__links[] = HTML::a($language->link, $v->link, true);
            }

            echo implode(' &#x00B7; ', Hook::fire('panel.a.page', [$__links, $v]));
            echo '</p>';
            echo '</footer>';
            echo '</article>';
        }
    } else if (!Request::get('q')) {
        echo '<p>' . $language->message_info_void($language->pages) . '</p>';
    }
    echo '</section>';
}

Hook::set('panel.m', 'panel_m_' . $site->type, 10);

function panel_f_title() {
    extract(Lot::get(null, []));
    echo '<p class="f expand">';
    echo '<label for="f-title">' . $language->title . '</label>';
    echo ' <span>';
    echo Form::text('title', $__page[0]->title, $__page[1]->title, [
        'classes' => ['input', 'block'],
        'id' => 'f-title',
        'data' => ['slug-i' => 'title']
    ]);
    echo '</span>';
    echo '</p>';
}

function panel_f_slug() {
    extract(Lot::get(null, []));
    echo '<p class="f expand">';
    echo '<label for="f-slug">' . $language->slug . '</label>';
    echo ' <span>';
    echo Form::text('slug', $__page[0]->slug, $__page[1]->slug, [
        'classes' => ['input', 'block'],
        'id' => 'f-slug',
        'pattern' => '^[a-z\\d-]+$',
        'data' => ['slug-o' => 'title']
    ]);
    echo '</span>';
    echo '</p>';
}

function panel_f_content() {
    extract(Lot::get(null, []));
    echo '<div class="f expand p">';
    echo '<label for="f-content">' . $language->content . '</label>';
    echo '<div>';
    echo Form::textarea('content', $__page[0]->content, $language->f_content, [
        'classes' => ['textarea', 'block', 'expand', 'code', 'editor'],
        'id' => 'f-content',
        'data' => ['type' => $__page[0]->type]
    ]);
    echo '</div>';
    echo '</div>';
}

function panel_f_type() {
    extract(Lot::get(null, []));
    echo '<p class="f">';
    echo '<label for="f-type">' . $language->type . '</label>';
    echo ' <span>';
    $__types = a(Panel::get('f.types', []));
    asort($__types);
    echo Form::select('type', $__types, $__page[0]->type, [
        'classes' => ['select'],
        'id' => 'f-type'
    ]);
    echo '</span>';
    echo '</p>';
}

function panel_f_description() {
    extract(Lot::get(null, []));
    echo '<div class="f p">';
    echo '<label for="f-description">' . $language->description . '</label>';
    echo '<div>';
    echo Form::textarea('description', $__page[0]->description, $language->f_description($language->page), [
        'classes' => ['textarea', 'block'],
        'id' => 'f-description'
    ]);
    echo '</div>';
    echo '</div>';
}

function panel_f_link() {
    extract(Lot::get(null, []));
    echo '<p class="f">';
    echo '<label for="f-link">' . $language->link . '</label>';
    echo ' <span>';
    echo Form::url('link', $__page[0]->link, $url->protocol, [
        'classes' => ['input', 'block'],
        'id' => 'f-link'
    ]);
    echo '</span>';
    echo '</p>';
}

function panel_f_time() {
    extract(Lot::get(null, []));
    if ($__sgr !== 's') {
        $__time = (new Date($__page[0]->time))->format('Y/m/d H:i:s');
        echo '<p class="f">';
        echo '<label for="f-time">' . $language->time . '</label>';
        echo ' <span>';
        echo Form::text('time', $__time, $__time, [
            'classes' => ['input', 'date'],
            'id' => 'f-time',
            'pattern' => '^\\d{4,}\\/\\d{2}\\/\\d{2} \\d{2}:\\d{2}:\\d{2}$'
        ]);
        echo '</span>';
        echo '</p>';
    }
}

function panel_f_state() {
    extract(Lot::get(null, []));
    echo '<p class="f expand">';
    echo '<label for="f-state">' . $language->state . '</label>';
    echo ' <span>';
    if ($__sgr !== 's') {
        $x = $__page[0]->state;
        echo Form::submit('x', $x, $language->update, ['classes' => ['button', 'state-' . $x], 'id' => 'f-state:' . $x]);
        $__states = [
            'page' => 'publish',
            'draft' => 'save',
            'archive' => 'archive',
            'trash' => 'delete'
        ];
        foreach ($__states as $k => $v) {
            if ($x !== $k) {
                echo ' ' . Form::submit('x', $k, $language->{$v}, ['classes' => ['button', 'state-' . $k], 'id' => 'f-state:' . $k]);
            }
        }
    } else {
        echo Form::submit('x', 'page', $language->publish, ['classes' => ['button', 'state-page'], 'id' => 'f-state:page']);
        echo ' ' . Form::submit('x', 'draft', $language->save, ['classes' => ['button', 'state-draft'], 'id' => 'f-state:draft']);
    }
    echo '</span>';
    echo '</p>';
}

foreach ([
    10 => 'panel_f_title',
    20 => 'panel_f_slug',
    30 => 'panel_f_content',
    40 => 'panel_f_type',
    50 => 'panel_f_description',
    60 => 'panel_f_link',
    70 => 'panel_f_time'
] as $k => $v) {
    Hook::set('panel.m.editor', $v, $k);
}

function panel_s_author() {
    extract(Lot::get(null, []));
    echo '<section class="secondary-author">';
    echo '<h3>' . $language->author . '</h3>';
    echo '<p>';
    echo Form::text('author', $__page[0]->author, '@' . l($language->user), ['classes' => ['input', 'block']]);
    echo '</p>';
    echo '</section>';
}

function panel_s_search() {
    extract(Lot::get(null, []));
    echo '<section class="secondary-search">';
    echo '<h3>' . $language->search . '</h3>';
    echo '<form id="form.secondary.search" class="search" action="' . $url->current . '" method="get">';
    echo '<p>';
    echo Form::text('q', Request::get('q', ""), null, ['classes' => ['input']]);
    echo ' ' . Form::submit(null, null, $language->search, ['classes' => ['button']]);
    echo '</p>';
    echo '</form>';
    echo '</section>';
}

function panel_s_parent() {
    extract(Lot::get(null, []));
    $__r = count($__chops) === 2;
    if ($__r || $__parents[0]) {
        echo '<section class="secondary-parent">';
        echo '<h3>' . $language->{$__r || count($__parents[0]) === 1 ? 'parent' : 'parents'} . '</h3>';
        echo '<ul>';
        if ($__r) {
            echo '<li class="state-page">';
            echo HTML::a('./', $__state->path . '/::g::/' . $__chops[0] . $__is_pages);
            echo '</li>';
        } else {
            echo '<li class="state-' . $__parents[0][0]->state . '">';
            echo HTML::a($__parents[1][0]->title, $__parents[0][0]->url . $__is_pages);
            echo '</li>';
        }
        echo '</ul>';
        echo '</section>';
    }
}

function panel_s_kin() {
    extract(Lot::get(null, []));
    if ($__kins[0]) {
        echo '<section class="secondary-kin">';
        echo '<h3>' . $language->{count($__kins[0]) === 1 ? 'kin' : 'kins'} . '</h3>';
        echo '<ul>';
        foreach ($__kins[0] as $k => $v) {
            echo '<li class="state-' . $v->state . '">';
            echo HTML::a($__kins[1][$k]->title, $v->url . $__is_pages);
            echo '</li>';
        }
        if ($__is_kin_has_step) {
            echo '<li>';
            echo HTML::a('&#x2026;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]);
            echo '</li>';
        }
        echo '</ul>';
        echo '</section>';
    }
}

function panel_s_nav() {
    extract(Lot::get(null, []));
    echo '<section class="secondary-nav">';
    echo '<h3>' . $language->navigation . '</h3>';
    echo '<p>' . $__pager[0] . '</p>';
    echo '</section>';
}

function panel_s_setting() {
    extract(Lot::get(null, []));
    if ($__sgr === 'g' && count($__childs[0]) > 0) {
        echo '<section class="secondary-setting">';
        echo '<h3>' . $language->settings . '</h3>';
        echo '<h4>' . $language->sort . '</h4>';
        echo '<p>';
        echo Form::radio('sort[0]', $language->panel->sort, isset($__parents[0]->sort[0]) ? $__parents[0]->sort[0] : (isset($__page[1]->sort[0]) ? $__page[1]->sort[0] : ""), ['classes' => ['input']]);
        echo '</p>';
        echo '<h4>' . $language->by . '</h4>';
        echo '<p>';
        $__sort = (array) Panel::get('f.sorts', []);
        echo Form::radio('sort[1]', $__sort, isset($__parents[0]->sort[1]) ? $__parents[0]->sort[1] : (isset($__page[1]->sort[1]) ? $__page[1]->sort[1] : ""), ['classes' => ['input']]);
        echo '</p>';
        echo '<h4>' . $language->chunk . '</h4>';
        echo '<p>';
        echo Form::number('chunk', isset($__parents[0]->chunk) ? $__parents[0]->chunk : (isset($__page[1]->chunk) ? $__page[1]->chunk : ""), $site->chunk, ['classes' => ['input', 'block'], 'min' => 0, 'max' => 100]);
        echo '</p>';
        echo '</section>';
    }
}

foreach ($site->type === 'page' ? [
    10 => 'panel_s_author',
    20 => 'panel_s_parent',
    30 => 'panel_s_setting'
] : [
    10 => 'panel_s_search',
    20 => 'panel_s_parent',
    30 => 'panel_s_kin',
    40 => 'panel_s_nav'
] as $k => $v) {
    Hook::set('panel.s.left', $v, $k);
}

function panel_s_data() {
    extract(Lot::get(null, []));
    if ($__sgr === 'g') {
        echo '<section class="secondary-data">';
        echo '<h3>' . $language->{count($__datas[0]) === 1 ? 'data' : 'datas'} . '</h3>';
        echo '<ul>';
        foreach ($__datas[0] as $k => $v) {
            echo '<li class="data-' . $v->key . '">';
            echo HTML::a($__datas[1][$k]->key, $__state->path . '/::g::/' . $__path . '/d:' . $v->key);
            echo '</li>';
        }
        echo '<li>' . HTML::a('&#x2795;', $__state->path . '/::s::/' . $__path . '/d+', false, ['title' => $language->add]) . '</li>';
        echo '</ul>';
        echo '</section>';
    }
}

function panel_s_child() {
    extract(Lot::get(null, []));
    if (count($__chops) > 1) {
        echo '<section class="secondary-child">';
        echo '<h3>' . $language->{count($__childs[0]) === 1 ? 'child' : 'childs'} . '</h3>';
        echo '<ul>';
        foreach ($__childs[0] as $k => $v) {
            $g = $v->path;
            $gg = Path::X($g);
            $ggg = Path::D($g);
            $gggg = Path::N($g) === Path::N($ggg) && file_exists($ggg . '.' . $gg);
            if ($gggg) continue; // skip the placeholder page
            echo '<li class="state-' . $v->state . '">' . HTML::a($__childs[1][$k]->title, $v->url) . '</li>';
        }
        echo '<li>' . HTML::a('&#x2795;', $__state->path . '/::s::/' . $__path, false, ['title' => $language->add]); ?><?php if ($__is_child_has_step) echo ' ' . HTML::a('&#x2026;', $__state->path . '/::g::/' . $__path . '/2', false, ['title' => $language->more]) . '</li>';
        echo '</ul>';
        echo '</section>';
    }
}

foreach ([
    10 => 'panel_s_data',
    20 => 'panel_s_child'
] as $k => $v) {
    Hook::set('panel.s.right', $v, $k);
}

function panel_s_left() {
    extract(Lot::get(null, []));
    echo '<aside class="secondary">';
    Hook::fire('panel.s.left');
    echo '</aside>';
}

function panel_m() {
    extract(Lot::get(null, []));
    echo '<main class="main">';
    echo $__message;
    Hook::fire('panel.m');
    echo Form::token();
    echo '</main>';
}

function panel_s_right() {
    echo '<aside class="secondary">';
    Hook::fire('panel.s.right');
    echo '</aside>';
}

Hook::set('panel', 'panel_s_left', 10);
Hook::set('panel', 'panel_m', 20);
Hook::set('panel', 'panel_s_right', 30);