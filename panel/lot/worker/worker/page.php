<?php

// Preparation(s)…
if (!Get::kin('_' . $__chops[0] . 's')) {
    function __fn_get_page_worker($__v, $__n = null) {
        $__n = $__n ?: Path::N($__v);
        $__v = file_get_contents($__v);
        if ($__n === 'time') {
            $__v = (new Date($__v))->format();
        } else if ($__n === 'slug') {
            $__v = h($__v);
        }
        return $__v;
    }
    function _fn_get_page($__path, $__key = null, $__fail = false, $__for = null) {
        if (!file_exists($__path)) return false;
        $__date = date(DATE_WISE, File::T($__path, time()));
        $__o = [
            'path' => $__path,
            'time' => $__date,
            'update' => $__date,
            'slug' => Path::N($__path),
            'state' => Path::X($__path)
        ];
        $__output = Page::open($__path, array_replace([
            $__for => null
        ], $__o))->get($__o);
        $__data = Path::F($__path);
        if (is_dir($__data)) {
            if ($__for === null) {
                foreach (g($__data, '*.data', "", false) as $__v) {
                    $__n = Path::N($__v);
                    $__output[$__n] = e(__fn_get_page_worker($__v, $__n));
                }
            } else if ($__v = File::exist($__data . DS . $__for . '.data')) {
                $__output[$__for] = e(__fn_get_page_worker($__v, $__for));
            }
        }
        return !isset($__key) ? $__output : (array_key_exists($__key, $__output) ? $__output[$__key] : $__fail);
    }
    function _fn_get_pages($__folder = PAGE, $__state = 'page', $__sort = [-1, 'time'], $__key = null) {
        $__output = [];
        $__by = is_array($__sort) && isset($__sort[1]) ? $__sort[1] : null;
        if ($__input = g($__folder, $__state, "", false)) {
            foreach ($__input as $__v) {
                $__output[] = _fn_get_page($__v, null, false, $__by);
            }
            $__output = $__o = Anemon::eat($__output)->sort($__sort)->vomit();
            if (isset($__key)) {
                $__o = [];
                foreach ($__output as $__v) {
                    if (!array_key_exists($__key, $__v)) continue;
                    $__o[] = $__v[$__key];
                }
            }
            unset($__output);
            return !empty($__o) ? $__o : false;
        }
        return false;
    }
    Get::plug('_' . $__chops[0] . 's', '_fn_get_pages');
}

$__is_data = substr($url->path, -2) === '/+' || strpos($url->path, '/+/') !== false;
$__g = false;
$__pth = $url . '/' . $__state->path . '/::g::/';

// Get current folder…
$__d = LOT . DS . $__path;
// Get current page file…
$__f = File::exist([
    $__d . '.draft',
    $__d . '.page',
    $__d . '.archive'
], "");
// Get current page(s) file…
if (Get::kin('_' . $__chops[0] . 's') && $__g = call_user_func('Get::_' . $__chops[0] . 's', $__is_has_step ? $__d : Path::D($__d), 'draft,page,archive', $__sort, 'path')) {
    if ($__q = l(Request::get('q', ""))) {
        Message::info('search', '<em>' . $__q . '</em>');
        $__q = explode(' ', $__q);
        $__g = array_filter($__g, function($__v) use($__q) {
            $__v = Path::N($__v);
            foreach ($__q as $__) {
                if (strpos($__v, $__) !== false) {
                    return true;
                }
            }
            return false;
        });
    }
}

if ($__is_data) {
    $__s = explode('/+', $__path . '/+');
    $__s[1] = trim($__s[1], '/');
    $__d = LOT . DS . $__s[0];
    // Get source…
    if ($__f = File::exist([
        $__d . '.draft',
        $__d . '.page',
        $__d . '.archive'
    ])) {
        $__a = new Page($__f, [], '__' . $__chops[0]);
        $__a->url = rtrim($__pth . ltrim(str_replace(DS, '/', Path::F($__f, LOT)), '/'), '/');
        $__aa = new Page($__f, [], $__chops[0]);
        $__source = [$__a, $__aa];
        Lot::set('__source', $__source);
    } else {
        Shield::abort(PANEL_404);
    }
    $__f = File::exist($__d . DS . $__s[1] . '.data', "");
    if (!$__s[1] && $__action !== 's' || $__s[1] && !$__f) {
        Shield::abort(PANEL_404);
    }
    if ($__is_post && !Message::$x) {
        if (Request::post('x') === 'trash') {
            Guardian::kick(str_replace('::g::', '::r::', $url->current . HTTP::query(['token' => Request::post('token')])));
        }
        $__key = Request::post('key', "", false);
        $__ff = $__d . DS . $__key . '.data';
        if ($__s[1] !== $__key && file_exists($__ff)) {
            Request::save('post');
            Message::error('exist', [$language->key, '<em>' . $__key . '</em>']);
        }
        if (!Message::$x) {
            $__value = Request::post('value', "", false);
            File::write(is_array($__value) ? json_encode($__value) : $__value)->saveTo($__ff, 0600);
            if ($__s[1] !== $__key) {
                File::open($__f)->delete();
            }
            Hook::fire('on.' . $__chops[0] . '.+.set', [$__ff, $__action === 's' ? null : $__f]);
            Message::success($__action === 's' ? 'create' : 'update', [$language->data, '<em>' . $__key . '</em>']);
            Guardian::kick($__state->path . '/::g::/' . $__s[0] . '/+/' . $__key);
        }
    } else {
        if ($__action === 'r') {
            if (!$__t = Request::get('token')) {
                Shield::abort(PANEL_404);
            } else if ($__t !== Session::get(Guardian::$config['session']['token'])) {
                Shield::abort(PANEL_404);
            }
            if (!$__f = File::exist($__d . DS . $__s[1] . '.data')) {
                Shield::abort(PANEL_404);
            }
            $__back = str_replace('::r::', '::g::', $url->path);
            if (Message::$x) {
                Guardian::kick($__back);
            }
            $__ff = str_replace(LOT, LOT . DS . 'trash' . DS . 'lot', $__f);
            File::open($__f)->moveTo(Path::D($__ff));
            Hook::fire('on.' . $__chops[0] . '.+.reset', [$__f, $__ff]);
            Message::success('delete', [$language->data, '<em>' . $__s[1] . '</em>']);
            Guardian::kick($__state->path . '/::g::/' . $__s[0]);
        }
    }
    $__ss = (object) [
        'path' => $__f,
        'key' => $__f ? Path::N($__f) : null,
        'value' => $__f ? file_get_contents($__f) : null
    ];
    $__ss->title = $__ss->key;
    $__ss->type = $__ss->key ?: 'HTML';
    Lot::set('__data', $__data = [$__ss, $__ss]);
    // Get kin(s)…
    foreach (glob($__d . DS . '*.data') as $__v) {
        $__ss = Path::N($__v);
        $__ss = (object) [
            'path' => $__v,
            'title' => $__ss,
            'key' => $__ss,
            'url' => $url . '/' . $__state->path . '/::g::/' . $__s[0] . '/+/' . $__ss
        ];
        $__datas[0][] = $__ss;
        $__datas[1][] = $__ss;
    }
    Lot::set('__datas', $__datas);
} else {
    if ($__is_has_step) {
        if ($__g) {
            foreach (Anemon::eat($__g)->chunk($__chunk, $__step) as $__v) {
                $__a = new Page($__v, [], '__' . $__chops[0]);
                $__a->url = rtrim($__pth . ltrim(str_replace(DS, '/', Path::F($__v, LOT)), '/'), '/');
                $__aa = new Page($__v, [], $__chops[0]);
                $__pages[0][] = $__a;
                $__pages[1][] = $__aa;
            }
        }
        Lot::set([
            '__pages' => $__pages,
            '__is_has_step_page' => ($__is_has_step_page = count($__g) > $__chunk),
            '__pager' => $__pager = [(new Elevator($__g ?: [], $__chunk, $__step, $url . '/' . $__state->path . '/::g::/' . $__path, [
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
            ], '__' . $__chops[0] . 's')) . ""]
        ]);
        // Get parent…
        $__p = Path::D($__f);
        if ($__p = File::exist([
            $__p . '.draft',
            $__p . '.page',
            $__p . '.archive'
        ])) {
            $__a = new Page($__p, [], '__' . $__chops[0]);
            $__a->url = rtrim($__pth . ltrim(str_replace(DS, '/', Path::F($__p, LOT)), '/'), '/');
            $__aa = new Page($__p, [], $__chops[0]);
            Lot::set('__parent', $__parent = [$__a, $__aa]);
        }
        // Get current…
        $__a = new Page($__f ?: null, [], '__' . $__chops[0]);
        $__a->url = rtrim($__pth . ltrim(str_replace(DS, '/', Path::F($__f ?: "", LOT)), '/'), '/');
        $__aa = new Page($__f ?: null, [], $__chops[0]);
        Lot::set('__page', $__page = [$__a, $__aa]);
        // Get kin(s)…
        if (Get::kin('_' . $__chops[0] . 's') && $__g = call_user_func('Get::_' . $__chops[0] . 's', Path::D($__d), 'draft,page,archive', $__sort, 'path')) {
            $__q = $__action === 's' ? "" : Path::B($__d);
            foreach (Anemon::eat($__g)->chunk($__chunk, 0) as $__k => $__v) {
                if ($__q && Path::N($__v) === $__q) continue;
                $__a = new Page($__v, [], '__' . $__chops[0]);
                $__a->url = rtrim($__pth . ltrim(str_replace(DS, '/', Path::F($__v, LOT)), '/'), '/');
                $__aa = new Page($__v, [], $__chops[0]);
                $__kins[0][] = $__a;
                $__kins[1][] = $__aa;
            }
            Lot::set([
                '__kins' => $__kins,
                '__is_has_step_kin' => ($__is_has_step_kin = count($__g) > $__chunk)
            ]);
        }
    } else {
        if ($__is_post && !Message::$x) {
            // Delete page…
            if (Request::post('x') === 'trash') {
                Guardian::kick(str_replace('::g::', '::r::', $url->current . HTTP::query(['token' => Request::post('token')])));
            }
            // Set as home page?
            if ($__s = Request::post('as_')) {
                if ($__state_shield = Shield::state($config->shield)) {
                    $__state_shield['path'] = $__s;
                    File::export($__state_shield)->saveTo(SHIELD . DS . $config->shield . DS . 'state' . DS . 'config.php', 0600);
                } else if ($__state_extend_page = Extend::state('page')) {
                    $__state_extend_page['path'] = $__s;
                    File::export($__state_extend_page)->saveTo(EXTEND . DS . 'page' . DS . 'lot' . DS . 'state' . DS . 'config.php', 0600);
                }
            }
            // Hide page(s) view?
            if ($__f) {
                $__p = Path::F($__f) . DS . Path::B($__f);
                if (Request::post('as_page')) {
                    File::write("")->saveTo($__p, 0600); // a placeholder page
                } else {
                    if (File::open($__p)->read(X) === "") {
                        File::open($__p)->delete();
                    }
                }
            }
            // ...
            $__N = Path::N($__f);
            $__X = Path::X($__f);
            $__D = Path::D($__f) ?: $__d;
            $__S = $__f;
            $__NN = Request::post('slug', date('Y-m-d-H-i-s'));
            $__XX = Request::post('x', $__action === 's' ? 'page' : $__X);
            $__DD = $__D . DS . $__NN;
            $__SS = $__DD . '.' . $__XX;
            $__headers_alt = [];
            foreach (explode("\n", trim(Request::post('__datas', ""))) as $__v) {
                $__v = trim($__v);
                if ($__v === "") continue;
                $__v = explode(Page::$v[2], $__v, 2);
                if (!isset($__v[1])) $__v[1] = $__v[0];
                $__headers_alt[trim($__v[0])] = trim($__v[1]);
            }
            $__headers = array_replace([
                'title' => function($__s) {
                    return To::text($__s, HTML_WISE_I) ?: false;
                },
                'description' => false,
                'author' => false,
                'type' => false,
                'link' => false,
                'email' => false,
                'status' => false,
                'content' => false
            ], $__headers_alt);
            $__headers_alt = (array) $site->page;
            foreach ($__headers as $__k => $__v) {
                if (file_exists($__DD . DS . $__k . '.data')) continue;
                if (is_callable($__v)) {
                    $__v = call_user_func($__v, Request::post($__k, false));
                } else {
                    $__v = Request::post($__k, $__v);
                }
                if (isset($__headers_alt[$__k]) && $__headers_alt[$__k] === $__v) {
                    $__v = false; // reset
                }
                $__headers[$__k] = $__v;
            }
            if ($__action === 's' && File::exist([
                $__D . DS . $__NN . '.draft',
                $__D . DS . $__NN . '.page',
                $__D . DS . $__NN . '.archive'
            ]) || $__N !== $__NN && File::exist([
                $__DD . '.draft',
                $__DD . '.page',
                $__DD . '.archive'
            ])) {
                Request::save('post');
                Message::error('exist', [$language->slug, '<em>' . $__NN . '</em>']);
                Guardian::kick($url->current);
            }
            if (!Message::$x) {
                // Update current path value…
                // Append page name to the current path (up one level)…
                if ($__action === 's') {
                    $__DD = $__d . DS . $__NN;
                    $__SS = $__DD . '.' . $__XX;
                }
                // Create page…
                if ($__action === 's') {
                    Page::data($__headers)->saveTo($__SS, 0600);
                // Update page…
                } else {
                    Page::open($__S)->data($__headers)->save(0600);
                    if ($__N !== $__NN || $__X !== $__XX) {
                        // Rename file…
                        File::open($__S)->renameTo($__NN . '.' . $__XX);
                        // Rename folder…
                        if ($__N !== $__NN) {
                            File::open($__d)->renameTo($__NN);
                        }
                    }
                }
                // Working with custom field(s)…
                // Any `<input name="+[foo]">` value will be stored in the folder :)
                if ($__s = Request::post('+', [], false)) {
                    foreach ($__s as $__k => $__v) {
                        if (
                            is_string($__v) && trim($__v) === "" ||
                            is_array($__v) && empty($__v)
                        ) {
                            File::open($__DD . DS . $__k . '.data')->delete();
                        } else {
                            File::write(is_array($__v) ? json_encode(e($__v)) : $__v)->saveTo($__DD . DS . $__k . '.data', 0600);
                        }
                    }
                }
                // Create `time.data` file if slug format does not look like a time format…
                // Detect time format on the page slug @see `engine\kernel\page.php`
                if (
                    $__NN &&
                    is_numeric($__NN[0]) &&
                    (
                        // `2017-04-21.page`
                        substr_count($__NN, '-') === 2 ||
                        // `2017-04-21-14-25-00.page`
                        substr_count($__NN, '-') === 5
                    ) &&
                    is_numeric(str_replace('-', "", $__NN)) &&
                    preg_match('#^\d{4,}-\d{2}-\d{2}(?:-\d{2}-\d{2}-\d{2})?$#', $__NN)
                ) {
                    File::open($__DD . DS . 'time.data')->delete();
                } else {
                    if (!$__s = Request::post('+.time')) {
                        $__s = date(DATE_WISE);
                    } else {
                        $__s = DateTime::createFromFormat('Y/m/d H:i:s', $__s)->format(DATE_WISE);
                    }
                    File::write($__s)->saveTo($__DD . DS . 'time.data', 0600);
                }
                $__tt = $__headers['title'] ?: $language->_title;
                if ($__action === 'g') {
                    Message::success($__XX === 'draft' ? 'save' : 'update', [$language->{$__chops[0]}, '<strong>' . $__tt . '</strong>']);
                    Hook::fire('on.' . $__chops[0] . '.set', [$__SS, $__action === 's' ? null : $__S]);
                    Guardian::kick(Path::D($url->current) . '/' . $__NN);
                } else {
                    Message::success($__XX === 'draft' ? 'save' : 'create', [$language->{$__chops[0]}, '<strong>' . $__tt . '</strong>']);
                    Hook::fire('on.' . $__chops[0] . '.set', [$__SS, $__action === 's' ? null : $__S]);
                    Guardian::kick(str_replace('::s::', '::g::', $url->current) . '/' . $__NN);
                }
            }
        } else {
            if ($__action === 'r') {
                if (!$__t = Request::get('token')) {
                    Shield::abort(PANEL_404);
                } else if ($__t !== Session::get(Guardian::$config['session']['token'])) {
                    Shield::abort(PANEL_404);
                }
                $__back = str_replace('::r::', '::g::', $url->path);
                $__B = Path::B($__d);
                if (Message::$x) {
                    Guardian::kick($__back);
                }
                $__tt = To::text(Request::post('title'), HTML_WISE_I) ?: call_user_func(function() use($language, $__d, $__chops) {
                    if ($__f = File::exist([
                        $__d . '.draft',
                        $__d . '.page',
                        $__d . '.archive'
                    ])) {
                        return (new Page($__f, [], $__chops[0]))->title;
                    }
                    return $language->_title;
                });
                $__ff = str_replace(LOT, LOT . DS . 'trash' . DS . 'lot', $__f);
                File::open($__f)->moveTo(Path::D($__ff));
                Hook::fire('on.' . $__chops[0] . '.reset', [$__f, $__ff]);
                Message::success('delete', [$language->{$__chops[0]}, '<strong>' . $__tt . '</strong>']);
                Guardian::kick(Path::D($__back) . '/1');
            }
        }
        if (!$__f && count($__chops) > 1) {
            Shield::abort(PANEL_404);
        }
        // Get parent…
        $__p = Path::D($__f);
        if ($__p = File::exist([
            $__p . '.draft',
            $__p . '.page',
            $__p . '.archive'
        ])) {
            Lot::set('__parent', $__parent = [
                new Page($__p, [], '__' . $__chops[0]),
                new Page($__p, [], $__chops[0])
            ]);
        }
        // Get current…
        $__a = new Page($__f && $__action !== 's' ? $__f : null, [], '__' . $__chops[0]);
        $__a->url = rtrim($__pth . ltrim(str_replace(DS, '/', Path::F($__f ?: "", LOT)), '/'), '/');
        $__aa = new Page($__f && $__action !== 's' ? $__f : null, [], $__chops[0]);
        Lot::set('__page', $__page = [$__a, $__aa]);
        // Get kin(s)…
        if ($__g) {
            $__q = $__action === 's' ? "" : Path::B($__d);
            foreach (Anemon::eat($__g)->chunk($__chunk, 0) as $__k => $__v) {
                if ($__q && Path::N($__v) === $__q) continue;
                $__a = new Page($__v, [], '__' . $__chops[0]);
                $__a->url = rtrim($__pth . ltrim(str_replace(DS, '/', Path::F($__v, LOT)), '/'), '/');
                $__aa = new Page($__v, [], $__chops[0]);
                $__kins[0][] = $__a;
                $__kins[1][] = $__aa;
            }
            Lot::set([
                '__kins' => $__kins,
                '__is_has_step_kin' => ($__is_has_step_kin = count($__g) > $__chunk)
            ]);
        }
        // Get data(s)…
        $__u = str_replace('::s::', '::g::', $url->current);
        $__x = ',' . Config::get('panel.x.s.data') . ',';
        foreach (glob($__d . DS . '*.data') as $__v) {
            $__s = Path::N($__v);
            if (strpos($__x, ',' . $__s . ',') !== false) continue;
            $__s = [
                'path' => $__v,
                'title' => $__s,
                'key' => $__s,
                'url' => $__u . '/+/' . $__s
            ];
            $__datas[0][] = $__s;
            $__datas[1][] = $__s;
        }
        Lot::set('__datas', $__datas);
        // Get child(s)…
        if (Get::kin('_' . $__chops[0] . 's') && $__g = call_user_func('Get::_' . $__chops[0] . 's', $__d, 'draft,page,archive', $__sort, 'path')) {
            $__q = Path::B($__d);
            foreach (Anemon::eat($__g)->chunk($__chunk, 0) as $__k => $__v) {
                if (Path::N($__v) === $__q) continue;
                $__a = new Page($__v, [], '__' . $__chops[0]);
                $__a->url = rtrim($__pth . ltrim(str_replace(DS, '/', Path::F($__v, LOT)), '/'), '/');
                $__aa = new Page($__v, [], $__chops[0]);
                $__childs[0][] = $__a;
                $__childs[1][] = $__aa;
            }
            Lot::set([
                '__childs' => $__childs,
                '__is_has_step_child' => ($__is_has_step_child = count($__g) > $__chunk)
            ]);
        }
    }
}

Config::set([
    'panel' => [
        'layout' => $__is_has_step || $__is_data ? 2 : 3,
        'c:f' => !$__is_has_step,
        'm' => [
            't' => [
                'page' => $__is_data ? null : [
                    'content' => require __DIR__ . DS . 'page.m.t.page.php',
                    'stack' => 10
                ],
                'data' => $__is_data ? [
                    'content' => require __DIR__ . DS . 'page.m.t.data.php',
                    'stack' => 10
                ] : null
            ]
        ]
    ]
]);

$__ = Path::D($__path);
$__ = [
    'url' => $__state->path . '/::g::/' . ($__ ?: $__path),
    'title' => '..'
];

$__is_data = substr($__path, -2) === '/+' || strpos($__path, '/+/') !== false;
Config::set('panel.s', [
    1 => [
        'source' => [
            'title' => $language->source,
            'content' => $__source[0] ? [[$__source[0]], [$__source[1]]] : [],
            'if' => $__is_data,
            'stack' => 10
        ],
        'search' => [
            'content' => __DIR__ . DS . '..' . DS . 'pages' . DS . '-search.php',
            'if' => $__is_has_step,
            'stack' => 10
        ],
        'author' => [
            'content' => __DIR__ . DS . '..' . DS . 'page' . DS . '-author.php',
            'if' => !$__is_has_step && !$__is_data,
            'stack' => 10
        ],
        'parent' => [
            'title' => $language->parent,
            'content' => $__parent[0] ? [[$__parent[0]], [$__parent[1]]] : [[$__], [$__]],
            'if' => !$__is_data && count($__chops) > 1,
            'lot' => $__is_has_step ? ['%{0}%/1'] : null,
            'stack' => 20
        ],
        'current' => [
            'content' => [[$__page[0]], [$__page[1]]],
            'if' => $__is_has_step && $__page[0] && count($__chops) > 1,
            'stack' => 30
        ],
        'kin' => $__is_data ? [
            'content' => $__datas,
            'a' => [
                ['&#x2795;', $__state->path . '/::s::/' . rtrim(explode('/+/', $__path . '/')[0], '/') . '/+', false, ['title' => $language->add]]
            ],
            'stack' => 20
        ] : [
            'content' => $__kins,
            'a' => [
                ['&#x2795;', $__state->path . '/::s::/' . (Path::D($__path) ?: $__path), false, ['title' => $language->add]],
                $__is_has_step_kin ? ['&#x22EF;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]] : null
            ],
            'if' => $__action === 's' || count($__chops) > 1,
            'lot' => $__is_has_step ? ['%{0}%/1'] : null,
            'stack' => 40
        ],
        'nav' => [
            'title' => $language->navigation,
            'content' => '<p>' . $__pager[0] . '</p>',
            'if' => $__is_has_step,
            'stack' => 50
        ],
        'setting' => [
            'title' => $language->settings,
            'content' => __DIR__ . DS . '..' . DS . 'page' . DS . '-setting.php',
            'if' => !$__is_has_step && !$__is_data,
            'stack' => 50
        ]
    ],
    2 => [
        'data' => [
            'title' => $language->datas,
            'content' => $__action === 'g' ? $__datas : [[], []],
            'after' => __DIR__ . DS . '..' . DS . 'page' . DS . '-data.php',
            'a' => $__action === 'g' ? [
                ['&#x2795;', $__state->path . '/::s::/' . rtrim(explode('/+/', $__path . '/')[0], '/') . '/+', false, ['title' => $language->add]]
            ] : [],
            'if' => !$__is_data,
            'stack' => 10
        ],
        'child' => [
            'content' => $__childs,
            'a' => [
                ['&#x2795;', $__state->path . '/::s::/' . $__path, false, ['title' => $language->add]],
                $__is_has_step_child ? ['&#x22EF;', $__state->path . '/::g::/' . $__path . '/2', false, ['title' => $language->more]] : null
            ],
            'if' => !$__is_data && count($__chops) > 1,
            'stack' => 20
        ]
    ]
]);

if (!$__is_has_step && $__action !== 's' && $__page[0]) {
    $__s = trim(To::url(Path::F($__path, 'page', '/')), '/');
    Config::set('panel.o.page.setting.option', [
        ($site->path === $__s ? '.' : "") . 'as_' => [
            'title' => $language->__->panel->as_,
            'value' => $__s,
            'is' => [
                'active' => $site->path === $__s
            ]
        ],
        'as_page' => Get::pages(LOT . DS . $__path, 'draft,page,archive') ? [
            'title' => $language->__->panel->as_page,
            'value' => 1,
            'is' => [
                'active' => file_exists(Path::F($__page[0]->path) . DS . $__page[0]->slug . '.' . $__page[0]->state)
            ]
        ] : null
    ]);
}