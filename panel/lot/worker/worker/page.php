<?php

$__is_data = substr($url->path, -2) === '/+' || strpos($url->path, '/+/') !== false;
$__g = false;

Hook::set('__' . $__chops[0] . '.url', function($__content, $__lot) use($__state) {
    $__s = Path::F($__lot['path'], LOT);
    return rtrim($__state->path . '/::g::/' . ltrim(To::url($__s), '/'), '/');
});

// Get current folder…
$__d = LOT . DS . $__path;
// Get current page file…
$__f = File::exist([
    $__d . '.draft',
    $__d . '.page',
    $__d . '.archive'
], "");
// Get current page(s) file…
if (Get::kin($__chops[0] . 's') && $__g = call_user_func('Get::' . $__chops[0] . 's', $__is_has_step ? $__d : Path::D($__d), 'draft,page,archive', $__sort, 'path')) {
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
        $__source = [
            new Page($__f, [], '__' . $__chops[0]),
            new Page($__f, [], $__chops[0])
        ];
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
            Hook::NS('on.data.set', [$__ff, $__action === 's' ? null : $__f]);
        }
        if (!Message::$x) {
            $__value = Request::post('value', "", false);
            File::write(is_array($__value) ? json_encode($__value) : $__value)->saveTo($__ff, 0600);
            if ($__s[1] !== $__key) {
                File::open($__f)->delete();
            }
            Message::success($language->{'message_success_' . ($__action === 's' ? 'create' : 'update')}($language->data . ' <em>' . $__key . '</em>'));
            Guardian::kick($__state->path . '/::g::/' . $__s[0] . '/+/' . $__key);
        }
    } else {
        if ($__action === 'r') {
            if (!Request::get('token')) {
                Shield::abort(PANEL_404);
            }
            if (!$__f = File::exist([
                $__d . DS . $__s[1] . '.data',
                $__d . DS . $__s[1] . '.trash'
            ])) {
                Shield::abort(PANEL_404);
            }
            $__back = str_replace('::r::', '::g::', $url->path);
            if (!Message::$x) {
                Hook::NS('on.data.reset', [$__d . DS . $__s[0] . '.trash', $__f]);
            }
            if (Message::$x) {
                Guardian::kick($__back);
            }
            if (Request::get('abort')) {
                File::open($__d . DS . $__s[1] . '.trash')->renameTo($__s[1] . '.data');
                Message::success($language->message_success_restore([$language->data, '<em>' . $__s[1] . '</em>']));
            } else {
                File::open($__f)->renameTo($__s[1] . '.trash');
                Message::success($language->message_success_delete([$language->data, '<em>' . $__s[1] . '</em>']) . ' ' . HTML::a($language->restore, $url->path . HTTP::query(['abort' => 1]), false, ['classes' => ['right']]));
            }
            Guardian::kick($__state->path . '/::g::/' . $__s[0]);
        }
    }
    $__ss = (object) [
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
                $__pages[0][] = new Page($__v, [], '__' . $__chops[0]);
                $__pages[1][] = new Page($__v, [], $__chops[0]);
            }
        }
        Lot::set([
            '__pages' => $__pages,
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
            Lot::set('__parent', $__parent = [
                new Page($__p, [], '__' . $__chops[0]),
                new Page($__p, [], $__chops[0])
            ]);
        }
        // Get current…
        Lot::set('__page', $__page = [
            new Page($__f ?: null, [], '__' . $__chops[0]),
            new Page($__f ?: null, [], $__chops[0])
        ]);
        // Get kin(s)…
        if (Get::kin($__chops[0] . 's') && $__g = call_user_func('Get::' . $__chops[0] . 's', Path::D($__d), 'draft,page,archive', $__sort, 'path')) {
            $__q = Path::B($__d);
            $__g = array_filter($__g, function($__v) use($__q) {
                return Path::N($__v) !== $__q;
            });
            foreach (Anemon::eat($__g)->chunk($__chunk, 0) as $__k => $__v) {
                $__kins[0][] = new Page($__v, [], '__' . $__chops[0]);
                $__kins[1][] = new Page($__v, [], $__chops[0]);
            }
            $__is_has_step_kin = count($__g) > $__chunk;
            Lot::set([
                '__kins' => $__kins,
                '__is_has_step_kin' => $__is_has_step_kin
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
                    return To::text($__s, HTML_WISE_I);
                },
                'description' => false,
                'author' => false,
                'type' => false,
                'link' => false,
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
                Hook::fire('on.' . $__chops[0] . '.set', [$__SS, $__action === 's' ? null : $__S]);
            }
            if (!Message::$x) {
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
                        if (trim($__v) !== "") {
                            File::write(is_array($__v) ? json_encode($__v) : $__v)->saveTo($__DD . DS . $__k . '.data', 0600);
                        } else {
                            File::open($__DD . DS . $__k . '.data')->delete();
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
                    if (!$__s = Request::post('+[time]')) {
                        $__s = date(DATE_WISE);
                    } else {
                        $__s = DateTime::createFromFormat('Y/m/d H:i:s', $__s)->format(DATE_WISE);
                    }
                    File::write($__s)->saveTo($__DD . DS . 'time.data', 0600);
                }
                $__tt = $__headers['title'] ?: $language->_title;
                if ($__action === 'g') {
                    Message::success($language->{'message_success_' . ($__XX === 'draft' ? 'save' : 'update')}($language->{$__chops[0]} . ' <strong>' . $__tt . '</strong>'));
                    Guardian::kick(Path::D($url->current) . '/' . $__NN);
                } else {
                    Message::success($language->{'message_success_' . ($__XX === 'draft' ? 'save' : 'create')}($language->{$__chops[0]} . ' <strong>' . $__tt . '</strong>'));
                    Guardian::kick(str_replace('::s::', '::g::', $url->current) . '/' . $__NN);
                }
            }
        } else {
            if ($__action === 'r') {
                if (!Request::get('token')) {
                    Shield::abort(PANEL_404);
                }
                $__back = str_replace('::r::', '::g::', $url->path);
                $__B = Path::B($__d);
                if (!Message::$x) {
                    Hook::fire('on.' . $__chops[0] . '.reset', [$__d . '.trash', $__f]);
                }
                if (Message::$x) {
                    Guardian::kick($__back);
                }
                $__tt = To::text(Request::post('title'), HTML_WISE_I) ?: call_user_func(function() use($language, $__d, $__chops) {
                    if ($__f = File::exist([
                        $__d . '.draft',
                        $__d . '.page',
                        $__d . '.archive',
                        $__d . '.trash'
                    ])) {
                        return (new Page($__f, [], $__chops[0]))->title;
                    }
                    return $language->_title;
                });
                if (Request::get('abort')) {
                    File::open($__d . '.trash')->renameTo($__B . '.draft');
                    Message::success($language->message_success_restore([$language->{$__chops[0]}, '<strong>' . $__tt . '</strong>']));
                } else {
                    File::open($__f)->renameTo($__B . '.trash');
                    Message::success($language->message_success_delete([$language->{$__chops[0]}, '<strong>' . $__tt . '</strong>']) . ' ' . HTML::a($language->restore, $url->path . HTTP::query(['abort' => 1]), false, ['classes' => ['right']]));
                }
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
        Lot::set('__page', $__page = [
            new Page($__f && $__action !== 's' ? $__f : null, [], '__' . $__chops[0]),
            new Page($__f && $__action !== 's' ? $__f : null, [], $__chops[0])
        ]);
        // Get kin(s)…
        if ($__g) {
            $__q = Path::B($__d);
            $__g = array_filter($__g, function($__v) use($__q) {
                return Path::N($__v) !== $__q;
            });
            foreach (Anemon::eat($__g)->chunk($__chunk, 0) as $__k => $__v) {
                $__kins[0][] = new Page($__v, [], '__' . $__chops[0]);
                $__kins[1][] = new Page($__v, [], $__chops[0]);
            }
            $__is_has_step_kin = count($__g) > $__chunk;
            Lot::set([
                '__kins' => $__kins,
                '__is_has_step_kin' => $__is_has_step_kin
            ]);
        }
        // Get data(s)…
        $__u = str_replace('::s::', '::g::', $url->current);
        $__x = ',' . Config::get('panel.x.s.data', 'chunk,css,id,js,kind,sort,time') . ',';
        foreach (glob($__d . DS . '*.data') as $__v) {
            $__s = Path::N($__v);
            if (strpos($__x, ',' . $__s . ',') !== false) continue;
            $__s = [
                'title' => $__s,
                'key' => $__s,
                'url' => $__u . '/+/' . $__s
            ];
            $__datas[0][] = $__s;
            $__datas[1][] = $__s;
        }
        Lot::set('__datas', $__datas);
        // Get child(s)…
        if (Get::kin($__chops[0] . 's') && $__g = call_user_func('Get::' . $__chops[0] . 's', $__d, 'draft,page,archive', $__sort, 'path')) {
            $__q = Path::B($__d);
            $__g = array_filter($__g, function($__v) use($__q) {
                return Path::N($__v) !== $__q;
            });
            foreach (Anemon::eat($__g)->chunk($__chunk, 0) as $__k => $__v) {
                $__childs[0][] = new Page($__v, [], '__' . $__chops[0]);
                $__childs[1][] = new Page($__v, [], $__chops[0]);
            }
            $__is_has_step_child = count($__g) > $__chunk;
            Lot::set([
                '__childs' => $__childs,
                '__is_has_step_child' => $__is_has_step_child
            ]);
        }
    }
}

Config::set([
    'is' => $__is_has_step ? 'pages' : 'page',
    'panel' => [
        'layout' => $__is_has_step || $__is_data ? 2 : 3,
        'c:f' => $__is_has_step ? false : 'editor',
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

Config::set('panel.s', [
    1 => substr($__path, -2) === '/+' || strpos($__path, '/+/') !== false ? [
        'source' => [
            'content' => $__source[0] ? [[$__source[0]], [$__source[1]]] : [],
            'if' => $__source[0],
            'stack' => 10
        ],
        'kin' => [
            'content' => $__datas,
            'a' => [
                ['&#x2795;', $__state->path . '/::s::/' . rtrim(explode('/+/', $__path . '/')[0], '/') . '/+', false, ['title' => $language->add]]
            ],
            'stack' => 20
        ]
    ] : [
        'search' => $__is_has_step ? [
            'content' => __DIR__ . DS . '..' . DS . 'pages' . DS . '-search.php',
            'stack' => 10
        ] : null,
        'author' => $__is_has_step ? null : [
            'content' => __DIR__ . DS . '..' . DS . 'page' . DS . '-author.php',
            'stack' => 10
        ],
        'parent' => count($__chops) > 1 ? [
            'content' => $__parent[0] ? [[$__parent[0]], [$__parent[1]]] : [[$__], [$__]],
            'lot' => $__is_has_step ? ['%{0}%/1'] : null,
            'stack' => 20
        ] : null,
        'current' => $__is_has_step && $__page[0] && count($__chops) > 1 ? [
            'content' => [[$__page[0]], [$__page[1]]],
            'stack' => 30
        ] : null,
        'kin' => count($__chops) > 1 ? [
            'content' => $__kins,
            'a' => [
                ['&#x2795;', $__state->path . '/::s::/' . (Path::D($__path) ?: $__path), false, ['title' => $language->add]],
                $__is_has_step_kin ? ['&#x22EF;', $__state->path . '/::g::/' . Path::D($__path) . '/2', false, ['title' => $language->more]] : null
            ],
            'lot' => $__is_has_step ? ['%{0}%/1'] : null,
            'stack' => 40
        ] : null,
        'nav' => $__is_has_step ? [
            'title' => $language->navigation,
            'content' => '<p>' . $__pager[0] . '</p>',
            'stack' => 50
        ] : null,
        'setting' => $__is_has_step ? null : [
            'title' => $language->settings,
            'content' => __DIR__ . DS . '..' . DS . 'page' . DS . '-setting.php',
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
            'stack' => 10
        ],
        'child' => [
            'content' => $__childs,
            'a' => [
                ['&#x2795;', $__state->path . '/::s::/' . $__path, false, ['title' => $language->add]],
                $__is_has_step_child ? ['&#x22EF;', $__state->path . '/::g::/' . $__path . '/2', false, ['title' => $language->more]] : null
            ],
            'if' => count($__chops) > 1,
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