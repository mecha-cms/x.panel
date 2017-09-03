<?php

// Preparation(s)…
if (!Get::kin('_' . $__chops[0] . 's')) {
    function __fn_get_($__v, $__n = null) {
        $__n = $__n ?: Path::N($__v);
        $__v = file_get_contents($__v);
        if ($__n === 'time') {
            $__v = (new Date($__v))->format();
        } else if ($__n === 'slug') {
            $__v = h($__v);
        }
        return $__v;
    }
    function _fn_get_($__path, $__key = null, $__fail = false, $__for = null) {
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
                foreach (g($__data, 'data') as $__v) {
                    $__n = Path::N($__v);
                    $__output[$__n] = e(__fn_get_($__v, $__n));
                }
            } else if ($__v = File::exist($__data . DS . $__for . '.data')) {
                $__output[$__for] = e(__fn_get_($__v, $__for));
            }
        }
        return !isset($__key) ? $__output : (array_key_exists($__key, $__output) ? $__output[$__key] : $__fail);
    }
    function _fn_get_s($__folder = PAGE, $__state = 'page', $__sort = [-1, 'time'], $__key = null) {
        $__output = [];
        $__by = is_array($__sort) && isset($__sort[1]) ? $__sort[1] : null;
        if ($__input = g($__folder, $__state)) {
            foreach ($__input as $__v) {
                $__output[] = _fn_get_($__v, null, false, $__by);
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
    Get::plug('_' . $__chops[0] . 's', '_fn_get_s');
}

$__is_data = substr($url->path, -2) === '/+' || strpos($url->path, '/+/') !== false;
$__query = HTTP::query([
    'token' => false,
    'r' => false
]);
$__g = false;
$__p = str_replace('/', DS, $__path);
$__u = $url . '/' . $__state->path . '/::g::/';

// Fix for comment toggle…
$__s = LOT . DS . $__p . DS . 'comments.data';
if (
    Extend::exist('comment') &&
    $__is_post && !Request::post('+.comments.x') &&
    file_exists($__s) && file_get_contents($__s) === '{"x":1}'
) {
    unlink($__s);
}

// Get current folder…
$__d = LOT . DS . $__p;
// Get current page file…
$__f = File::exist([
    $__d . '.draft',
    $__d . '.page',
    $__d . '.archive'
], "");
// Get current page(s) file…
if (Get::kin('_' . $__chops[0] . 's') && $__g = call_user_func('Get::_' . $__chops[0] . 's', $__is_has_step ? $__d : dirname($__d), 'draft,page,archive', $__sort, 'path')) {
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
    if (Config::get('panel.x.s.source') !== true && $__f = File::exist([
        $__d . '.draft',
        $__d . '.page',
        $__d . '.archive'
    ])) {
        $__a = new Page($__f, [], '__' . $__chops[0]);
        $__a->url = rtrim($__u . ltrim(Path::F($__f, LOT, '/'), '/'), '/');
        $__aa = new Page($__f, [], $__chops[0]);
        $__source = [$__a, $__aa];
        Lot::set('__source', $__source);
    } else {
        Shield::abort(404);
    }
    $__f = File::exist($__d . DS . $__s[1] . '.data', "");
    if (!$__s[1] && $__command !== 's' || $__s[1] && !$__f) {
        Shield::abort(404);
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
            Session::set('panel.v.f.' . md5($__ff), 1);
            Hook::fire('on.' . $__chops[0] . '.+.set', [$__ff, $__command === 's' ? null : $__f]);
            Message::success($__command === 's' ? 'create' : 'update', [$language->data, '<em>' . $__key . '</em>']);
            Guardian::kick($__state->path . '/::g::/' . $__s[0] . '/+/' . $__key . $__query);
        }
    } else {
        if ($__command === 'r') {
            if (!$__t = Request::get('token')) {
                Shield::abort(404);
            } else if (!Guardian::check($__t)) {
                Shield::abort(404);
            }
            if (!$__f = File::exist($__d . DS . $__s[1] . '.data')) {
                Shield::abort(404);
            }
            $__back = str_replace('::r::', '::g::', $url->path);
            if (Message::$x) {
                Guardian::kick($__back . $__query);
            }
            $__ff = Request::get('r') === 1 ? null : str_replace(LOT, LOT . DS . 'trash' . DS . 'lot', $__f);
            Hook::fire('on.' . $__chops[0] . '.+.reset', [$__f, $__ff]);
            if (!isset($__ff)) {
                File::open($__f)->delete();
                File::open(Path::F($__f))->delete();
            } else {
                File::open($__f)->moveTo(dirname($__ff));
                File::open(Path::F($__f))->moveTo(dirname(Path::F($__ff)));
            }
            Message::success('delete', [$language->data, '<em>' . $__s[1] . '</em>']);
            Guardian::kick($__state->path . '/::g::/' . $__s[0] . $__query);
        }
    }
    if (Config::get('panel.x.m.data') !== true) {
        $__ss = (object) [
            'path' => $__f,
            'key' => $__f ? Path::N($__f) : null,
            'value' => $__f ? file_get_contents($__f) : null
        ];
        $__ss->title = $__ss->key;
        $__ss->type = $__ss->key ?: 'HTML';
        Lot::set('__data', $__data = [$__ss, $__ss]);
    }
    // Get kin(s)…
    if (Config::get('panel.x.s.data') !== true) {
        $__q = end($__chops);
        foreach (glob($__d . DS . '*.data') as $__v) {
            $__ss = Path::N($__v);
            if ($__q && $__q === $__ss) continue;
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
    }
} else {
    if ($__is_has_step) {
        if ($__g && Config::get('panel.x.m.page') !== true) {
            foreach (Anemon::eat($__g)->chunk($__chunk, $__step) as $__v) {
                $__a = new Page($__v, [], '__' . $__chops[0]);
                $__a->url = rtrim($__u . ltrim(Path::F($__v, LOT, '/'), '/'), '/');
                $__aa = new Page($__v, [], $__chops[0]);
                $__pages[0][] = $__a;
                $__pages[1][] = $__aa;
            }
            Lot::set([
                '__pages' => $__pages,
                '__is_has_step_page' => ($__is_has_step_page = count($__g) > $__chunk)
            ]);
        }
        Lot::set([
            '__pager' => $__pager = [(new Elevator($__g ?: [], $__chunk, $__step, $url . '/' . $__state->path . '/::g::/' . $__path, [
                'direction' => [
                   '-1' => 'previous',
                    '0' => false,
                    '1' => 'next'
                ],
                'union' => [
                   '-2' => [
                        2 => ['classes' => ['button', 'x']]
                    ],
                   '-1' => [
                        1 => "",
                        2 => ['rel' => 'prev', 'classes' => ['button']]
                    ],
                    '1' => [
                        1 => "",
                        2 => ['rel' => 'next', 'classes' => ['button']]
                    ]
                ]
            ], '__' . $__chops[0] . 's')) . ""]
        ]);
        // Get parent…
        $__pp = dirname($__f);
        if (Config::get('panel.x.s.parent') !== true && $__pp = File::exist([
            $__pp . '.draft',
            $__pp . '.page',
            $__pp . '.archive'
        ])) {
            $__a = new Page($__pp, [], '__' . $__chops[0]);
            $__a->url = rtrim($__u . ltrim(Path::F($__pp, LOT, '/'), '/'), '/');
            $__aa = new Page($__pp, [], $__chops[0]);
            Lot::set('__parent', $__parent = [$__a, $__aa]);
        }
        // Get current…
        if (Config::get('panel.x.m.page') !== true) {
            $__a = new Page($__f ?: null, [], '__' . $__chops[0]);
            $__a->url = rtrim($__u . ltrim(Path::F($__f ?: "", LOT, '/'), '/'), '/');
            $__aa = new Page($__f ?: null, [], $__chops[0]);
            Lot::set('__page', $__page = [$__a, $__aa]);
        }
        // Get kin(s)…
        if (Config::get('panel.x.s.kin') !== true) {
            if (Get::kin('_' . $__chops[0] . 's') && $__g = call_user_func('Get::_' . $__chops[0] . 's', dirname($__d), 'draft,page,archive', $__sort, 'path')) {
                $__q = $__command === 's' ? "" : basename($__d);
                foreach (Anemon::eat($__g)->chunk($__chunk, 0) as $__k => $__v) {
                    if ($__q && Path::N($__v) === $__q) continue;
                    $__a = new Page($__v, [], '__' . $__chops[0]);
                    $__a->url = rtrim($__u . ltrim(Path::F($__v, LOT, '/'), '/'), '/');
                    $__aa = new Page($__v, [], $__chops[0]);
                    $__kins[0][] = $__a;
                    $__kins[1][] = $__aa;
                }
                Lot::set([
                    '__kins' => $__kins,
                    '__is_has_step_kin' => ($__is_has_step_kin = count($__g) > $__chunk)
                ]);
            }
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
                $__pp = Path::F($__f) . DS . basename($__f);
                if (Request::post('as_page')) {
                    File::write("")->saveTo($__pp, 0600); // a placeholder page
                } else {
                    if (File::open($__pp)->read(X) === "") {
                        File::open($__pp)->delete();
                    }
                }
            }
            // ...
            $__N = Path::N($__f);
            $__X = Path::X($__f);
            $__D = dirname($__f) ?: $__d;
            $__S = $__f;
            $__NN = Request::post('slug', date('Y-m-d-H-i-s'));
            $__XX = Request::post('x', $__command === 's' ? 'page' : $__X);
            $__DD = $__D . DS . $__NN;
            $__SS = $__DD . '.' . $__XX;
            Request::set('post', 'slug', $__NN);
            $__headers_c = [];
            // Inline custom field(s)…
            // Any `<input name=":[foo]">` value will be appended to the page header :)
            if ($__s = Request::post(':', [], false)) {
                foreach ($__s as $__k => $__v) {
                    if (
                        is_string($__v) && trim($__v) === "" ||
                        is_array($__v) && empty($__v)
                    ) {
                        continue;
                    } else {
                        $__headers_c[$__k] = is_array($__v) ? json_encode($__v) : $__v;
                    }
                }
            }
            foreach (explode("\n", trim(Request::post('__datas', ""))) as $__v) {
                $__v = trim($__v);
                if ($__v === "") continue;
                $__v = explode(Page::v[2], $__v, 2);
                if (!isset($__v[1])) $__v[1] = $__v[0];
                $__headers_c[trim($__v[0])] = trim($__v[1]);
            }
            $__headers = array_replace([
                'title' => function($__s) {
                    return To::text($__s, HTML_WISE_I) ?: false;
                },
                'description' => false,
                'version' => false,
                'author' => false,
                'type' => false,
                'link' => false,
                'email' => false,
                'status' => false,
                'content' => false
            ], $__headers_c);
            $__headers_c = (array) $site->page;
            foreach ($__headers as $__k => $__v) {
                if (file_exists($__DD . DS . $__k . '.data')) continue;
                if (is_callable($__v)) {
                    $__v = call_user_func($__v, Request::post($__k, false));
                } else {
                    $__v = Request::post($__k, $__v);
                }
                if (isset($__headers_c[$__k]) && $__headers_c[$__k] === $__v) {
                    $__v = false; // reset
                }
                $__headers[$__k] = $__v;
            }
            if ($__command === 's' && File::exist([
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
                Guardian::kick($url->current . $__query);
            }
            if (!Message::$x) {
                // Update current path value…
                // Append page name to the current path (up one level)…
                if ($__command === 's') {
                    $__DD = $__d . DS . $__NN;
                    $__SS = $__DD . '.' . $__XX;
                }
                // Create page…
                if ($__command === 's') {
                    Page::data($__headers)->saveTo($__SS, 0600);
                    Request::delete('post');
                // Update page…
                } else {
                    Page::open($__S)->data($__headers)->save(0600);
                    Request::delete('post');
                    if ($__N !== $__NN || $__X !== $__XX) {
                        // Rename file…
                        File::open($__S)->renameTo($__NN . '.' . $__XX);
                        // Rename folder…
                        if ($__N !== $__NN) {
                            File::open($__d)->renameTo($__NN);
                        }
                    }
                }
                // Separate custom field(s)…
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
                // Detect time format in the page slug @see `engine\kernel\page.php`
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
                $__tt = (new Page($__SS, [], $__chops[0]))->title ?: $language->_title;
                Session::set('panel.v.f.' . md5($__SS), 1);
                if ($__command === 'g') {
                    Hook::fire('on.' . $__chops[0] . '.set', [$__SS, $__S]);
                    Message::success($__XX === 'draft' ? 'save' : 'update', [$language->{$__chops[0]}, '<strong>' . $__tt . '</strong>']);
                    Guardian::kick(dirname($url->current) . '/' . $__NN . $__query);
                } else {
                    Hook::fire('on.' . $__chops[0] . '.set', [$__SS, null]);
                    Message::success($__XX === 'draft' ? 'save' : 'create', [$language->{$__chops[0]}, '<strong>' . $__tt . '</strong>']);
                    Guardian::kick(str_replace('::s::', '::g::', $url->current) . '/' . $__NN . $__query);
                }
            }
        } else {
            if ($__command === 'r') {
                if (!$__t = Request::get('token')) {
                    Shield::abort(404);
                } else if (!Guardian::check($__t)) {
                    Shield::abort(404);
                }
                $__back = str_replace('::r::', '::g::', $url->path);
                $__B = basename($__d);
                if (Message::$x) {
                    Guardian::kick($__back . $__query);
                }
                $__tt = call_user_func(function() use($language, $__chops, $__d) {
                    if ($__f = File::exist([
                        $__d . '.draft',
                        $__d . '.page',
                        $__d . '.archive'
                    ])) {
                        return (new Page($__f, [], $__chops[0]))->title;
                    }
                    return $language->_title;
                });
                $__ff = Request::get('r') === 1 ? null : str_replace(LOT, LOT . DS . 'trash' . DS . 'lot', $__f);
                Hook::fire('on.' . $__chops[0] . '.reset', [$__f, $__ff]);
                if (!isset($__ff)) {
                    File::open($__f)->delete();
                    File::open(Path::F($__f))->delete();
                } else {
                    File::open($__f)->moveTo(dirname($__ff));
                    File::open(Path::F($__f))->moveTo(Path::F($__ff));
                }
                if (!Message::get(false)) {
                    Message::success('delete', [$language->{$__chops[0]}, '<strong>' . $__tt . '</strong>']);
                }
                Guardian::kick(dirname($__back) . '/1' . $__query);
            }
        }
        if (!$__f && count($__chops) > 1) {
            Shield::abort(404);
        }
        // Get parent…
        $__pp = $__command === 'g' ? dirname($__f) : Path::F($__f);
        if (Config::get('panel.x.s.parent') !== true && $__pp = File::exist([
            $__pp . '.draft',
            $__pp . '.page',
            $__pp . '.archive'
        ])) {
            $__a = new Page($__pp, [], '__' . $__chops[0]);
            $__a->url = $__u . ltrim(Path::F($__pp, LOT, '/'), '/');
            $__aa = new Page($__pp, [], $__chops[0]);
            Lot::set('__parent', $__parent = [$__a, $__aa]);
        }
        // Get current…
        if (Config::get('panel.x.m.page') !== true) {
            $__a = new Page($__f && $__command !== 's' ? $__f : null, [], '__' . $__chops[0]);
            $__a->url = rtrim($__u . ltrim(Path::F($__f ?: "", LOT, '/'), '/'), '/');
            $__aa = new Page($__f && $__command !== 's' ? $__f : null, [], $__chops[0]);
            Lot::set('__page', $__page = [$__a, $__aa]);
        }
        // Get kin(s)…
        if ($__command !== 's' && $__g && Config::get('panel.x.s.kin') !== true) {
            $__q = basename($__d);
            foreach (Anemon::eat($__g)->chunk($__chunk, 0) as $__k => $__v) {
                if ($__q && Path::N($__v) === $__q) continue;
                $__a = new Page($__v, [], '__' . $__chops[0]);
                $__a->url = rtrim($__u . ltrim(Path::F($__v, LOT, '/'), '/'), '/');
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
        if (($__x = Config::get('panel.x.s.data')) !== true) {
            $__uu = str_replace('::s::', '::g::', $url->current);
            $__x = ',' . $__x . ',';
            foreach (glob($__d . DS . '*.data') as $__v) {
                $__s = Path::N($__v);
                if (strpos($__x, ',' . $__s . ',') !== false) continue;
                $__s = [
                    'path' => $__v,
                    'title' => $__s,
                    'key' => $__s,
                    'url' => $__uu . '/+/' . $__s
                ];
                $__datas[0][] = $__s;
                $__datas[1][] = $__s;
            }
            Lot::set('__datas', $__datas);
        }
        // Get child(s)…
        if (Config::get('panel.x.s.child') !== true) {
            if (Get::kin('_' . $__chops[0] . 's') && $__g = call_user_func('Get::_' . $__chops[0] . 's', $__d, 'draft,page,archive', $__sort, 'path')) {
                $__q = basename($__d);
                foreach (Anemon::eat($__g)->chunk($__chunk, 0) as $__k => $__v) {
                    if (Path::N($__v) === $__q) continue;
                    $__a = new Page($__v, [], '__' . $__chops[0]);
                    $__a->url = rtrim($__u . ltrim(Path::F($__v, LOT, '/'), '/'), '/');
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
}

$__ = dirname($__path);
$__ = [
    'url' => $__state->path . '/::g::/' . ($__ ?: $__path),
    'title' => '..'
];

Config::set('panel', array_replace_recursive([
    'layout' => $__is_has_step || $__is_data ? 2 : 3,
    'c:f' => !$__is_has_step,
    'm:f' => false,
    'm' => [
        't' => $__is_has_step ? [
            'page' => [
                'title' => $language->{$__chops[0] . 's'},
                'content' => require __DIR__ . DS . 'pages.m.t.page.php',
                'stack' => 10
            ]
        ] : [
            'page' => $__is_data ? false : [
                'title' => $language->{$__chops[0]},
                'list' => require __DIR__ . DS . 'page.m.t.page.php',
                'stack' => 10
            ],
            'data' => $__is_data ? [
                'list' => require __DIR__ . DS . 'page.m.t.data.php',
                'stack' => 10
            ] : false
        ]
    ],
    's' => [
        1 => [
            'source' => [
                'title' => $language->source,
                'list' => $__source[0] ? [[$__source[0]], [$__source[1]]] : [],
                'hidden' => !$__is_data,
                'stack' => 10
            ],
            'search' => [
                'content' => __DIR__ . DS . '..' . DS . 'pages' . DS . '-search.php',
                'hidden' => !$__is_has_step,
                'stack' => 10
            ],
            'author' => [
                'content' => __DIR__ . DS . '..' . DS . 'page' . DS . '-author.php',
                'hidden' => $__is_has_step || $__is_data,
                'stack' => 10
            ],
            'parent' => [
                'title' => $language->parent,
                'list' => $__parent[0] ? [[$__parent[0]], [$__parent[1]]] : [[$__], [$__]],
                'hidden' => $__is_data || count($__chops) === 1,
                'lot' => $__is_has_step ? ['%{0}%/1' . $__query] : ['%{0}%' . $__query],
                'stack' => 20
            ],
            'current' => [
                'title' => $language->current,
                'list' => [[$__page[0]], [$__page[1]]],
                'hidden' => !$__is_has_step || count($__chops) === 1,
                'stack' => 30
            ],
            'kin' => $__is_data ? [
                'list' => $__datas,
                'a' => [
                    'set' => ["", $__state->path . '/::s::/' . rtrim(explode('/+/', $__path . '/')[0], '/') . '/+' . $__query, false, ['title' => $language->add]]
                ],
                'stack' => 20
            ] : [
                'list' => $__kins,
                'a' => [
                    'set' => ["", $__state->path . '/::s::/' . (dirname($__path) ?: $__path) . $__query, false, ['title' => $language->add]],
                    'get' => $__is_has_step_kin ? ["", $__state->path . '/::g::/' . dirname($__path) . '/2' . $__query, false, ['title' => $language->more]] : false
                ],
                'hidden' => $__command === 's' || count($__chops) === 1,
                'lot' => $__is_has_step ? ['%{0}%/1' . $__query] : ['%{0}%' . $__query],
                'stack' => 40
            ],
            'nav' => [
                'title' => $language->navigation,
                'content' => __DIR__ . DS . '..' . DS . 'pages' . DS . '-nav.php',
                'hidden' => !$__is_has_step,
                'stack' => 50
            ],
            'setting' => [
                'title' => $language->settings,
                'content' => __DIR__ . DS . '..' . DS . 'page' . DS . '-setting.php',
                'hidden' => $__is_has_step || $__is_data,
                'stack' => 50
            ]
        ],
        2 => [
            'data' => [
                'title' => $language->datas,
                'list' => $__command === 'g' ? $__datas : [[], []],
                'after' => __DIR__ . DS . '..' . DS . 'page' . DS . '-data.php',
                'a' => $__command === 'g' ? [
                    'set' => ["", $__state->path . '/::s::/' . rtrim(explode('/+/', $__path . '/')[0], '/') . '/+', false, ['title' => $language->add]]
                ] : [],
                'hidden' => $__is_data,
                'stack' => 10
            ],
            'child' => [
                'list' => $__childs,
                'a' => [
                    'set' => ["", $__state->path . '/::s::/' . $__path, false, ['title' => $language->add]],
                    'get' => $__is_has_step_child ? ["", $__state->path . '/::g::/' . $__path . '/2', false, ['title' => $language->more]] : false
                ],
                'hidden' => $__is_data || count($__chops) === 1,
                'lot' => ['%{0}%' . $__query],
                'stack' => 20
            ]
        ]
    ]
], (array) a(Config::get('panel', []))));

if (!$__is_has_step && $__command !== 's' && $__page[0]) {
    $__s = trim(To::url(Path::F($__path, 'page', '/')), '/');
    $__toggle = (array) a(Config::get('panel.o.page.toggle', []));
    Config::set('panel.o.page.toggle', array_replace_recursive([
        'as_' => [
            'value' => $__s,
            'active' => $site->path === $__s,
            'attributes' => [
                'disabled' => $site->path === $__s
            ]
        ],
        'as_page' => count($__chops) > 1 && Get::kin($__chops[0] . 's') && call_user_func('Get::' . $__chops[0] . 's', LOT . DS . $__p, 'draft,page,archive') ? [
            'value' => 1,
            'active' => file_exists(Path::F($__page[0]->path) . DS . $__page[0]->slug . '.' . $__page[0]->state)
        ] : false,
        '+[comments][x]' => Extend::exist('comment') ? [
            'value' => 1,
            'active' => $__page[0]->comments && !empty($__page[0]->comments['x'])
        ] : false
    ], $__toggle));
}

// Hide embed custom field(s) from the raw embed field(s)…
Hook::set('shield.enter', function() {
    $__hides = [];
    foreach ((array) a(Config::get('panel.f', [])) as $__k => $__v) {
        if (!is_array($__v)) continue;
        foreach ($__v as $__kk => $__vv) {
            if (strpos($__kk, ':[') === 0) {
                $__hides[substr($__kk . ']', 2, strpos($__kk, ']') - 2)] = 1;
            }
        }
    }
    foreach ((array) a(Config::get('panel.m.t', [])) as $__k => $__v) {
        if (!is_array($__v)) continue;
        if (!empty($__v['list']) && is_array($__v['list'])) {
            foreach ($__v['list'] as $__kk => $__vv) {
                if (strpos($__kk, ':[') === 0) {
                    $__hides[substr($__kk . ']', 2, strpos($__kk, ']') - 2)] = 1;
                }
            }
        }
    }
    Config::set('panel.x.s.data', Config::get('panel.x.s.data') . ',' . implode(',', array_keys($__hides)));
}, 0);