<?php

$__u = $url . '/' . $__state->path . '/::g::/';
$__p = str_replace('/', DS, $__path);
$__query = HTTP::query([
    'token' => false,
    'force' => false
]);

// Get current
if (Config::get('panel.x.s.current') !== true) {
    $__a = $__aa = File::inspect(LOT . DS . $__p);
    $__a['title'] = $__aa['title'] = '<i class="i i-' . (is_dir($__a['path']) ? 'd' : 'f x-' . $__a['extension']) . '"></i> ' . Path::B($__p);
    $__a['url'] = $__u . $__path;
    Lot::set('__current', $__current = [o($__a), o($__aa)]);
}

if ($__is_has_step) {
    // Folder not found!
    if ($__command === 'g' && count($__chops) > 1 && !is_dir(LOT . DS . $__path)) {
        Shield::abort(PANEL_404);
    }
    // Get file(s)…
    $__g = [];
    if (Config::get('panel.x.m.file') !== true) {
        $__g = array_filter(array_merge(
            glob(LOT . DS . $__p . DS . '.*', GLOB_NOSORT),
            glob(LOT . DS . $__p . DS . '*', GLOB_NOSORT)
        ), function($__v) {
            return substr($__v, -2) !== DS . '.' && substr($__v, -3) !== DS . '..';
        });
        if ($__q = l(Request::get('q', ""))) {
            Message::info('search', '<em>' . $__q . '</em>');
            $__q = explode(' ', $__q);
            $__g = array_filter($__g, function($__v) use($__q) {
                $__v = Path::B($__v);
                foreach ($__q as $__) {
                    if (strpos($__v, $__) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }
        natsort($__g);
        foreach (Anemon::eat($__g)->chunk($__chunk * 2, $__step) as $__v) {
            $__a = $__aa = File::inspect($__v);
            $__a['title'] = $__aa['title'] = '<i class="i i-' . (is_dir($__v) ? 'd' : 'f x-' . $__a['extension']) . '"></i> ' . Path::B($__v);
            $__a['url'] = $__u . str_replace([LOT . DS, DS], ["", '/'], $__v) . (is_dir($__v) ? '/1' : "");
            $__files[0][] = o($__a);
            $__files[1][] = o($__aa);
        }
        Lot::set([
            '__files' => $__files,
            '__is_has_step_file' => ($__is_has_step_file = count($__g) > $__chunk * 2)
        ]);
    }
    Lot::set([
        '__pager' => $__pager = [(new Elevator($__g ?: [], $__chunk * 2, $__step, $url . '/' . $__state->path . '/::g::/' . $__path, [
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
} else {
    if ($__is_post && !Message::$x) {
        if ($__command === 's') {
            // Create file…
            $__n = explode(DS, str_replace('/', DS, Request::post('path', "", false)));
            $__n = To::file(array_pop($__n));
            if (!$__n || $__n === '--.--') {
                $__n = "";
            } else {
                Request::set('post', 'path', $__n);
            }
            $__x = Path::X($__n);
            if ($__x && !Is::these(File::$config['extensions'])->has($__x)) {
                Request::save('post');
                Message::error('file_x', '<em>' . $__x . '</em>');
            }
            // Create folder…
            if (!Message::$x && $__d = Request::post('directory', "", false)) {
                $__d = To::folder($__d);
                $__dd = LOT . DS . $__path . DS . $__d;
                if (!file_exists($__dd)) {
                    Request::set('post', 'directory', $__d);
                    Folder::set($__dd, 0755);
                    Message::success('create', [$language->folder, '<em>' . $__d . '</em>']);
                    Hook::fire('on.folder.set', [$__f, null]);
                    Session::set('panel.file.s.' . md5(Path::B($__d)), 1);
                } else {
                    Message::error('exist', [$language->folder, '<em>' . $__d . '</em>']);
                }
            }
            // Upload file…
            if (!empty($_FILES)) {
                $__extract = Request::post('extract');
                foreach ($_FILES as $__k => $__v) {
                    if (!$__v) continue;
                    File::upload($__v, LOT . DS . $__path, $__extract && $__k === 'file' ? function($__a) {
                        if (!extension_loaded('zip')) {
                            Guardian::abort('<a href="http://www.php.net/manual/en/book.zip.php" title="PHP &#x2013; Zip" rel="nofollow" target="_blank">PHP Zip</a> extension is not installed on your web server.');
                        }
                        $__zip = new ZipArchive;
                        if ($__zip->open($__a['path']) === true) {
                            $__zip->extractTo($__d = dirname($__a['path']));
                            for ($__i = 0; $__i < $__zip->numFiles; ++$__i) {
                                $__s = str_replace('/', DS, $__zip->getNameIndex($__i));
                                Session::set('panel.file.s.' . md5(basename(rtrim($__s, DS))), 1);
                            }
                            $__zip->close();
                            unlink($__a['path']);
                            Hook::fire('on.package.reset', [$__a['path'], null, $__a]);
                        }
                    } : null);
                    if (!$__extract) {
                        Session::set('panel.file.s.' . md5($__v['name']), 1);
                        Hook::fire('on.package.set', [LOT . DS . str_replace('/', DS, $__path) . DS . $__v['name'], null]);
                    }
                }
            }
            if (!Message::$x) {
                $__f = LOT . DS . $__path . DS . $__n;
                $__uu = str_replace('::s::', '::g::', $url->current);
                if ($__n) {
                    // Create file only if name/path is set
                    File::write(Request::post('content', "", false))->saveTo($__f);
                    Message::success('create', [$language->file, '<em>' .  $__n . '</em>']);
                    Hook::fire(['on.file.set', 'on.' . $__chops[0] . '.set'], [$__f, null]);
                    Session::set('panel.file.s.' . md5($__n), 1);
                }
                if ($__d) {
                    $__d = str_replace(DS, '/', $__d);
                    if (Request::post('kick')) {
                        Guardian::kick($__uu . '/' . $__d . '/1' . $__query);
                    } else {
                        Guardian::kick($__uu . '/' . Path::D($__d) . '/1' . $__query);
                    }
                }
                Guardian::kick($__uu . '/' . ($__n ?: '1') . $__query);
            } else {
                Guardian::kick($url->current . $__query);
            }
        } else if ($__command === 'g') {
            // Delete file…
            if (Request::post('_x') === 'trash') {
                Guardian::kick(str_replace('::g::', '::r::', URL::I($url->current) . HTTP::query(['token' => Request::post('token')], [1 => '&'])));
            }
            // Update file…
            $__p = trim(str_replace('/', DS, Request::post('path')), DS);
            $__p = To::file($__p);
            $__x = Path::X($__p);
            if (!Is::these(File::$config['extensions'])->has($__x)) {
                Request::save('post');
                Message::error('file_x', '<em>' . $__x . '</em>');
            }
            if (!Message::$x) {
                $__f = LOT . DS . $__chops[0] . DS . $__p;
                if ($__s = Request::post('content', "", false)) {
                    // text file
                    File::write($__s)->saveTo($__f);
                } else {
                    // folder and other(s)
                    $__ff = LOT . DS . $__path;
                    File::open($__ff)->moveTo(is_file($__ff) ? Path::D($__f) : $__f);
                }
                if ($__chops[0] . DS . $__p !== str_replace('/', DS, $__path)) {
                    File::open(LOT . DS . $__path)->delete();
                }
                Message::success('update', [$language->file, '<em>' .  Path::B($__p) . '</em>']);
                Hook::fire('on.' . $__chops[0] . '.set', [$__f, $__ff]);
                Guardian::kick($url . '/' . $__state->path . '/::g::/' . $__chops[0] . '/' . str_replace(DS, '/', $__p) . $__query);
            } else {
                Guardian::kick($url->current . $__query);
            }
        }
    } else {
        if ($__command === 'r') {
            if (!$__t = Request::get('token')) {
                Shield::abort(PANEL_404);
            } else if ($__t !== Session::get(Guardian::$config['session']['token'])) {
                Shield::abort(PANEL_404);
            }
            if (!$__f = File::exist(LOT . DS . $__path)) {
                Shield::abort(PANEL_404);
            }
            $__back = str_replace('::r::', '::g::', $url->path);
            if (Message::$x) {
                Guardian::kick($__back . $__query);
            }
            if (Request::get('force') === 1) {
                $__ff = null;
                File::open($__f)->delete();
            } else {
                $__ff = str_replace(LOT, LOT . DS . 'trash' . DS . 'lot', $__f);
                File::open($__f)->moveTo(is_file($__f) ? Path::D($__ff) : $__ff);
            }
            Message::success('delete', [$language->file, '<em>' . Path::B($__f) . '</em>']);
            Hook::fire('on.' . $__chops[0] . '.reset', [$__f, $__ff]);
            Guardian::kick(Path::D(str_replace('::r::', '::g::', $url->path)) . '/1' . $__query);
        }
    }
    // Get file
    if ($__command === 'g' && !$__f = File::exist(LOT . DS . $__p)) {
        Shield::abort(PANEL_404);
    }
    if ($__f && $__command === 's' && is_file($__f)) {
        Shield::abort(PANEL_404); // Folder only!
    }
    if (Config::get('panel.x.m.file') !== true) {
        $__a = $__aa = File::inspect($__f);
        $__a['title'] = $__aa['title'] = '<i class="i i-' . (is_dir($__a['path']) ? 'd' : 'f x-' . $__a['extension']) . '"></i> ' . Path::B($__p);
        $__a['url'] = $__u . $__path;
        $__a['content'] = is_file($__a['path']) ? (strpos(',' . SCRIPT_X . ',', ',' . Path::X($__p) . ',') === false ? false : file_get_contents($__a['path'])) : null;
        Lot::set('__file', $__file = [o($__a), o($__aa)]);
    }
}

// Get parent
if (Config::get('panel.x.s.parent') !== true) {
    $__a = $__aa = File::inspect(rtrim(LOT . DS . Path::D($__p), DS));
    $__a['title'] = $__aa['title'] = '<i class="i i-d"></i> ' . (count($__chops) > 2 ? Path::B(Path::D($__p)) : '..');
    $__a['url'] = rtrim($__u . Path::D($__path), '/') . ($__is_has_step ? '/1' : "");
    Lot::set('__parent', $__parent = [o($__a), o($__aa)]);
}

// Get child(s)
if (Config::get('panel.x.s.child') !== true) {
    $__b = Path::B($__p);
    $__g = array_filter(array_merge(
        glob(LOT . DS . $__p . DS . '.*', GLOB_NOSORT),
        glob(LOT . DS . $__p . DS . '*', GLOB_NOSORT)
    ), function($__v) use($__b) {
        return substr($__v, -2) !== DS . '.' && substr($__v, -3) !== DS . '..' && Path::B($__v) !== $__b;
    });
    natsort($__g);
    foreach (Anemon::eat($__g)->chunk($__chunk * 2, 0) as $__v) {
        $__a = $__aa = File::inspect($__v);
        $__a['title'] = $__aa['title'] = '<i class="i i-' . (is_dir($__v) ? 'd' : 'f x-' . $__a['extension']) . '"></i> ' . Path::B($__v);
        $__a['url'] = $__u . str_replace([LOT . DS, DS], ["", '/'], $__v) . ($__is_has_step && is_dir($__v) ? '/1' : "");
        $__childs[0][] = o($__a);
        $__childs[1][] = o($__aa);
    }
    Lot::set([
        '__childs' => $__childs,
        '__is_has_step_child' => ($__is_has_step_child = count($__g) > $__chunk * 2)
    ]);
}

// Get kin(s)
if (Config::get('panel.x.s.kin') !== true && $__p = Path::D($__p)) {
    $__g = array_filter(array_merge(
        glob(LOT . DS . $__p . DS . '.*', GLOB_NOSORT),
        glob(LOT . DS . $__p . DS . '*', GLOB_NOSORT)
    ), function($__v) use($__b) {
        return substr($__v, -2) !== DS . '.' && substr($__v, -3) !== DS . '..' && Path::B($__v) !== $__b;
    });
    natsort($__g);
    foreach (Anemon::eat($__g)->chunk($__chunk * 2, 0) as $__v) {
        $__a = $__aa = File::inspect($__v);
        $__a['title'] = $__aa['title'] = '<i class="i i-' . (is_dir($__v) ? 'd' : 'f x-' . $__a['extension']) . '"></i> ' . Path::B($__v);
        $__a['url'] = $__u . str_replace([LOT . DS, DS], ["", '/'], $__v) . ($__is_has_step && is_dir($__v) ? '/1' : "");
        $__kins[0][] = o($__a);
        $__kins[1][] = o($__aa);
    }
    Lot::set([
        '__kins' => $__kins,
        '__is_has_step_kin' => ($__is_has_step_kin = count($__g) > $__chunk * 2)
    ]);
}

Config::set('panel', [
    'layout' => Config::get('panel.layout', 2),
    'c:f' => Config::get('panel.c:f', false),
    'm:f' => Config::get('panel.m:f', !$__is_has_step),
    'm' => [
        't' => [
            'file' => [
                'legend' => $language->{$__command === 's' ? 'create' : 'update'},
                'list' => [
                    'content' => $__command === 's' || isset($__file[0]->content) && $__file[0]->content !== false ? [
                        'type' => 'editor',
                        'value' => isset($__file[0]->content) ? ($__file[0]->content ?: "") : null,
                        'attributes' => [
                            'data' => [
                                'type' => Anemon::alter($__command === 's' ? 'PHP' : u(Path::X($__path, 'HTML')), [
                                    'JS' => 'JavaScript',
                                    'PAGE' => 'Markdown'
                                ])
                            ]
                        ],
                        'is' => [
                            'expand' => true
                        ],
                        'expand' => true,
                        'stack' => 10
                    ] : null,
                    'path' => [
                        'type' => 'text',
                        'value' => $__command === 'g' ? str_replace(['/', LOT . DS . $__chops[0] . DS], [DS, ""], LOT . DS . $__path) : null,
                        'title' => $__command === 's' ? $language->name : $language->path,
                        'pattern' => $__command === 'g' ? '^[a-z\\d_.-]+(?:[\\\\/][a-z\\d._-]+)*$' : '^[a-z\\d_.-]+$',
                        'is' => [
                            'block' => true
                        ],
                        'stack' => 20
                    ],
                    '_x' => [
                        'type' => 'submit[]',
                        'values' => [
                            Path::X($__path, 'txt') => $language->{$__command === 's' ? 'submit' : 'update'},
                            'trash' => $__command === 's' ? null : $language->delete
                        ],
                        'stack' => 0
                    ]
                ],
                'stack' => 10
            ],
            'folder' => $__command === 's' || isset($__file[0]->content) && $__file[0]->content !== false && $__file[0]->is->files ? [
                'legend' => $language->{$__command === 's' ? 'create' : 'update'},
                'list' => [
                    'directory' => [
                        'type' => 'text',
                        'title' => $language->folder,
                        'is' => [
                            'block' => true
                        ],
                        'stack' => 10
                    ],
                    'kick' => [
                        'type' => 'toggle',
                        'title' => null,
                        'text' => $language->h_kick__($language->folder),
                        'attributes' => [
                            'checked' => !!Request::restore('post', 'kick', false)
                        ],
                        'stack' => 20
                    ]
                ],
                'stack' => 20
            ] : null,
            'upload' => $__command === 's' || isset($__file[0]->content) && $__file[0]->content !== false && $__file[0]->is->files ? [
                'legend' => $language->upload,
                'list' => [
                    'file' => [
                        'type' => 'file',
                        'stack' => 10
                    ],
                    'extract' => [
                        'type' => 'toggle',
                        'title' => null,
                        'text' => $language->h_extract,
                        'stack' => 20
                    ]
                ],
                'stack' => 30
            ] : null
        ]
    ],
    's' => [
        1 => [
            'search' => [
                'content' => __DIR__ . DS . '..' . DS . 'pages' . DS . '-search.php',
                'if' => $__is_has_step,
                'stack' => 10
            ],
            'parent' => [
                'title' => $language->parent,
                'list' => [[$__parent[0]], [$__parent[1]]],
                'if' => count($__chops) > 1,
                'stack' => 20
            ],
            'kin' => [
                'list' => $__kins,
                'a' => [
                    'set' => ['&#x2795;', str_replace('::g::', '::s::', $__command === 's' ? $url->current : Path::D($url->current)), false, ['title' => $language->add]]
                ],
                'if' => count($__chops) > 1 && $__kins[0],
                'stack' => 30
            ],
            'child' => [
                'list' => $__childs,
                'a' => [
                    'set' => $__command === 's' ? ['&#x2795;', str_replace('::g::', '::s::', $url->current), false, ['title' => $language->add]] : null
                ],
                'if' => !$__is_has_step && is_dir(LOT . DS . $__path) && $__childs[0],
                'stack' => 40
            ],
            'nav' => [
                'title' => $language->navigation,
                'content' => '<p>' . $__pager[0] . '</p>',
                'if' => $__is_has_step,
                'stack' => 50
            ]
        ]
    ]
]);