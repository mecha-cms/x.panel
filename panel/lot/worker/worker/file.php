<?php

$__p = str_replace('/', DS, $__path);
$__u = $url . '/' . $__state->path . '/::g::/';
$__query = HTTP::query([
    'token' => false,
    'force' => false
]);

// Get current
if (Config::get('panel.x.s.current') !== true) {
    $__a = $__aa = File::inspect(LOT . DS . $__p);
    $__t = basename($__p);
    $__a['title'] = $__t;
    $__a['url'] = $__u . $__path;
    $__aa['title'] = '<i class="i i-' . (is_dir($__a['path']) ? 'd' : 'f x-' . $__a['extension']) . '"></i> <span>' . $__t . '</span>';
    Lot::set('__current', $__current = [o($__a), o($__aa)]);
}

if ($__is_has_step) {
    // Folder not found!
    if ($__command === 'g' && count($__chops) > 1 && !is_dir(LOT . DS . $__p)) {
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
                $__v = basename($__v);
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
            $__t = basename($__v);
            $__a['title'] = $__t;
            $__a['url'] = $__u . str_replace([LOT . DS, DS], ["", '/'], $__v) . (is_dir($__v) ? '/1' : "");
            $__aa['title'] = '<i class="i i-' . (is_dir($__v) ? 'd' : 'f x-' . $__a['extension']) . '"></i> <span>' . $__t . '</span>';
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
                $__dd = LOT . DS . $__p . DS . $__d;
                if (!file_exists($__dd)) {
                    Request::set('post', 'directory', $__d);
                    Folder::set($__dd, 0755);
                    Session::set('panel.ff.s.' . md5($__f), 1);
                    Hook::fire('on.folder.set', [$__f, null]);
                    Message::success('create', [$language->folder, '<em>' . $__d . '</em>']);
                } else {
                    Message::error('exist', [$language->folder, '<em>' . $__d . '</em>']);
                }
            }
            // Upload file…
            if (!empty($_FILES)) {
                $__extract = Request::post('o.upload.extract');
                foreach ($_FILES as $__k => $__v) {
                    if (!$__v || empty($__v['size'])) continue;
                    File::upload($__v, LOT . DS . $__p, $__extract && $__k === 'file' ? function($__a) use($__p) {
                        if (!extension_loaded('zip')) {
                            Guardian::abort('<a href="http://www.php.net/manual/en/book.zip.php" title="PHP &#x2013; Zip" rel="nofollow" target="_blank">PHP Zip</a> extension is not installed on your web server.');
                        }
                        $__zip = new ZipArchive;
                        if ($__zip->open($__a['path']) === true) {
                            $__zip->extractTo($__d = dirname($__a['path']));
                            for ($__i = 0; $__i < $__zip->numFiles; ++$__i) {
                                $__ff = str_replace('/', DS, $__zip->getNameIndex($__i));
                                Session::set('panel.ff.s.' . md5(rtrim($__d . DS . $__ff, DS)), 1);
                                if ($__i === 0) {
                                    // Highlight the root folder…
                                    Session::set('panel.ff.s.', md5(explode(DS, $__ff)[0]));
                                }
                            }
                            $__zip->close();
                            unlink($__a['path']);
                            Hook::fire('on.package.reset', [$__a['path'], null, $__a]);
                        }
                    } : null);
                    if (!$__extract) {
                        $__ff = LOT . DS . $__p . DS . To::file($__v['name']);
                        Session::set('panel.ff.s.' . md5($__ff), 1);
                        Hook::fire('on.package.set', [$__ff, null]);
                    }
                }
            }
            if (!Message::$x) {
                $__f = LOT . DS . $__p . DS . $__n;
                $__uu = str_replace('::s::', '::g::', $url->current);
                if ($__n) {
                    // Create file only if name/path is set
                    File::write(Request::post('content', "", false))->saveTo($__f);
                    Session::set('panel.ff.s.' . md5($__f), 1);
                    Hook::fire(['on.file.set', 'on.' . $__chops[0] . '.set'], [$__f, null]);
                    Message::success('create', [$language->file, '<em>' .  $__n . '</em>']);
                }
                if ($__d) {
                    $__d = str_replace(DS, '/', $__d);
                    if (Request::post('o.folder.kick')) {
                        Guardian::kick($__uu . '/' . $__d . '/1' . $__query);
                    } else {
                        Guardian::kick($__uu . '/' . dirname($__d) . '/1' . $__query);
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
            $__pp = trim(str_replace('/', DS, Request::post('path')), DS);
            $__pp = To::file($__pp);
            $__x = Path::X($__pp);
            if (!Is::these(File::$config['extensions'])->has($__x)) {
                Request::save('post');
                Message::error('file_x', '<em>' . $__x . '</em>');
            }
            if (!Message::$x) {
                $__f = LOT . DS . $__chops[0] . DS . $__pp;
                if ($__s = Request::post('content', "", false)) {
                    // text file
                    File::write($__s)->saveTo($__f);
                } else {
                    // folder and other(s)
                    $__ff = LOT . DS . $__p;
                    File::open($__ff)->moveTo(is_file($__ff) ? dirname($__f) : $__f);
                }
                if ($__chops[0] . DS . $__pp !== $__p) {
                    File::open(LOT . DS . $__p)->delete();
                }
                $__t = is_dir($__f) ? 'folder' : 'file';
                Hook::fire('on.' . $__t . '.set', [$__f, $__ff]);
                Message::success('update', [$language->{$__t}, '<em>' .  basename($__p) . '</em>']);
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
            if (!$__f = File::exist(LOT . DS . $__p)) {
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
                File::open($__f)->moveTo(is_file($__f) ? dirname($__ff) : $__ff);
            }
            $__t = is_dir($__ff) ? 'folder' : 'file';
            Hook::fire('on.' . $__t . '.reset', [$__f, $__ff]);
            Message::success('delete', [$language->{$__t}, '<em>' . basename($__f) . '</em>']);
            $__uu = dirname(str_replace('::r::', '::g::', $url->path));
            if (is_dir($__ff)) {
                $__uu = dirname($__uu);
            }
            Guardian::kick($__uu . '/1' . $__query);
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
        $__t = basename($__p);
        $__a['title'] = $__t;
        $__a['url'] = $__u . $__path;
        $__a['content'] = is_file($__a['path']) ? (strpos(',' . SCRIPT_X . ',', ',' . Path::X($__p) . ',') === false ? false : file_get_contents($__a['path'])) : null;
        $__aa['title'] = '<i class="i i-' . (is_dir($__a['path']) ? 'd' : 'f x-' . $__a['extension']) . '"></i> <span>' . $__t . '</span>';
        Lot::set('__file', $__file = [o($__a), o($__aa)]);
    }
}

// Get parent
if (Config::get('panel.x.s.parent') !== true) {
    $__a = $__aa = File::inspect(rtrim(LOT . DS . dirname($__p), DS));
    $__t = count($__chops) > 2 ? basename(dirname($__p)) : '..';
    $__a['title'] = $__t;
    $__a['url'] = rtrim($__u . dirname($__path), '/') . ($__is_has_step ? '/1' : "");
    $__aa['title'] = '<i class="i i-d"></i> <span>' . $__t . '</span>';
    Lot::set('__parent', $__parent = [o($__a), o($__aa)]);
}

// Get child(s)
if (Config::get('panel.x.s.child') !== true) {
    $__b = basename($__p);
    $__g = array_filter(array_merge(
        glob(LOT . DS . $__p . DS . '.*', GLOB_NOSORT),
        glob(LOT . DS . $__p . DS . '*', GLOB_NOSORT)
    ), function($__v) use($__b) {
        return substr($__v, -2) !== DS . '.' && substr($__v, -3) !== DS . '..' && basename($__v) !== $__b;
    });
    natsort($__g);
    foreach (Anemon::eat($__g)->chunk($__chunk * 2, 0) as $__v) {
        $__a = $__aa = File::inspect($__v);
        $__t = basename($__v);
        $__a['title'] = $__t;
        $__a['url'] = $__u . str_replace([LOT . DS, DS], ["", '/'], $__v) . ($__is_has_step && is_dir($__v) ? '/1' : "");
        $__aa['title'] = '<i class="i i-' . (is_dir($__v) ? 'd' : 'f x-' . $__a['extension']) . '"></i> <span>' . $__t . '</span>';
        $__childs[0][] = o($__a);
        $__childs[1][] = o($__aa);
    }
    Lot::set([
        '__childs' => $__childs,
        '__is_has_step_child' => ($__is_has_step_child = count($__g) > $__chunk * 2)
    ]);
}

// Get kin(s)…
if (Config::get('panel.x.s.kin') !== true && $__pp = dirname($__p)) {
    $__pp = $__pp === '.' ? "" : DS . $__pp;
    $__g = array_filter(array_merge(
        glob(LOT . $__pp . DS . '.*', GLOB_NOSORT),
        glob(LOT . $__pp . DS . '*', GLOB_NOSORT)
    ), function($__v) use($__b) {
        return substr($__v, -2) !== DS . '.' && substr($__v, -3) !== DS . '..' && basename($__v) !== $__b;
    });
    natsort($__g);
    foreach (Anemon::eat($__g)->chunk($__chunk * 2, 0) as $__v) {
        $__a = $__aa = File::inspect($__v);
        $__t = basename($__v);
        $__a['title'] = $__t;
        $__a['url'] = $__u . str_replace([LOT . DS, DS], ["", '/'], $__v) . ($__is_has_step && is_dir($__v) ? '/1' : "");
        $__aa['title'] = '<i class="i i-' . (is_dir($__v) ? 'd' : 'f x-' . $__a['extension']) . '"></i> <span>' . $__t . '</span>';
        $__kins[0][] = o($__a);
        $__kins[1][] = o($__aa);
    }
    Lot::set([
        '__kins' => $__kins,
        '__is_has_step_kin' => ($__is_has_step_kin = count($__g) > $__chunk * 2)
    ]);
}

Config::set('panel', array_replace_recursive([
    'layout' => 2,
    'c:f' => false,
    'm:f' => !$__is_has_step,
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
                                    'JSON' => 'JavaScript',
                                    'PAGE' => 'YAML-Frontmatter',
                                    'XML' => 'HTML'
                                ])
                            ]
                        ],
                        'is' => [
                            'expand' => true
                        ],
                        'expand' => true,
                        'stack' => 10
                    ] : false,
                    'path' => [
                        'type' => 'text',
                        'value' => $__command === 'g' ? str_replace(['/', LOT . DS . $__chops[0] . DS], [DS, ""], LOT . DS . $__path) : null,
                        'placeholder' => $__command === 's' ? $language->f_file : null,
                        'title' => $__command === 's' ? $language->name : $language->path,
                        'pattern' => $__command === 'g' ? '^[a-z\\d_.-]+(?:[\\\\/][a-z\\d._-]+)*$' : '^[a-z\\d_.-]+$',
                        'is' => [
                            'block' => true
                        ],
                        'stack' => 20
                    ],
                    '_x' => [
                        'key' => 'submit',
                        'type' => 'submit[]',
                        'values' => [
                            Path::X($__path, 'txt') => $language->{$__command === 's' ? 'submit' : 'update'},
                            'trash' => $__command === 's' ? false : $language->delete
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
                        'placeholder' => $language->f_path,
                        'title' => $language->path,
                        'is' => [
                            'block' => true
                        ],
                        'stack' => 10
                    ],
                    'o[folder]' => [
                        'key' => 'o-folder',
                        'type' => 'toggle[]',
                        'title' => $language->folder,
                        'value' => ['kick' => 1],
                        'values' => [
                            'kick' => [$language->h_kick__($language->folder), 1]
                        ],
                        'stack' => 20
                    ]
                ],
                'stack' => 20
            ] : false,
            'upload' => $__command === 's' || isset($__file[0]->content) && $__file[0]->content !== false && $__file[0]->is->files ? [
                'legend' => $language->upload,
                'list' => [
                    'file' => [
                        'type' => 'file',
                        'stack' => 10
                    ],
                    'o[upload]' => [
                        'key' => 'o-upload',
                        'type' => 'toggle[]',
                        'title' => $language->package,
                        'value' => ['extract' => false],
                        'values' => [
                            'extract' => [$language->h_extract, 1]
                        ],
                        'stack' => 20
                    ]
                ],
                'stack' => 30
            ] : false
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
                    'set' => ['&#x2795;', str_replace('::g::', '::s::', $__command === 's' ? $url->current : dirname($url->current)), false, ['title' => $language->add]]
                ],
                'if' => count($__chops) > 1 && $__kins[0],
                'stack' => 30
            ],
            'child' => [
                'list' => $__childs,
                'a' => [
                    'set' => $__command === 's' ? ['&#x2795;', str_replace('::g::', '::s::', $url->current), false, ['title' => $language->add]] : false
                ],
                'if' => !$__is_has_step && is_dir(LOT . DS . $__p) && $__childs[0],
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
], (array) a(Config::get('panel', []))));