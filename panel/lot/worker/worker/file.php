<?php

$__p = str_replace('/', DS, $__path);
$__u = $url . '/' . $__state->path . '/::g::/';
$__query = HTTP::query([
    'token' => false,
    'r' => false
]);

// Get current
if (Config::get('panel.x.s.current') !== true) {
    $__a = $__aa = File::inspect(LOT . DS . $__p);
    $__t = basename($__p);
    $__a['title'] = $__t;
    $__a['url'] = $__u . $__path . $__query;
    $__aa['title'] = '<i class="i i-' . (is_dir($__a['path']) ? 'd' : 'f x-' . $__a['extension']) . '"></i> <span>' . $__t . '</span>';
    Lot::set('__current', $__current = [o($__a), o($__aa)]);
}

if ($__is_has_step) {
    // Folder not found!
    if ($__command === 'g' && count($__chops) > 1 && !is_dir(LOT . DS . $__p)) {
        Shield::abort(404);
    }
    // Get file(s)…
    $__g = [];
    if (Config::get('panel.x.m.file') !== true) {
        $__g = array_filter(array_merge(
            // Show hidden file(s) for super user only!
            $__user_status === 1 ? glob(LOT . DS . $__p . DS . '.*', GLOB_NOSORT) : [],
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
            $__a['url'] = $__u . str_replace([LOT . DS, DS], ["", '/'], $__v) . (is_dir($__v) ? '/1' : "") . $__query;
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
                    2 => ['class[]' => ['button', 'x']]
                ],
               '-1' => [
                    1 => "",
                    2 => ['rel' => 'prev', 'class[]' => ['button']]
                ],
                '1' => [
                    1 => "",
                    2 => ['rel' => 'next', 'class[]' => ['button']]
                ]
            ]
        ], '__' . $__chops[0] . 's')) . ""]
    ]);
} else {
    if ($__is_post && !Message::$x) {
        $__ext = File::$config['extensions'];
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
            if ($__x && !Is::this($__ext)->contain($__x)) {
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
                    Session::set('panel.v.f.' . md5($__f), 1);
                    Hook::fire('on.folder.set', [$__f, null]);
                    Message::success('create', [$language->folder, '<em>' . $__d . '</em>']);
                } else {
                    Message::error('exist', [$language->folder, '<em>' . $__d . '</em>']);
                }
            }
            // Upload file…
            if (!Message::$x && !empty($_FILES)) {
                $__dd = LOT . DS . $__p;
                $__o = Request::post('o.upload.file', []);
                $__extract = isset($__o['extract']) && $__o['extract'];
                $__exist_reset = isset($__o['exist_reset']) && $__o['exist_reset'];
                foreach ($_FILES as $__k => $__v) {
                    if (!$__v || empty($__v['size'])) continue;
                    $__ss = $__dd . DS . To::file($__v['name']);
                    if ($__exist_reset) {
                        File::open($__ss)->delete();
                    }
                    File::upload($__v, $__dd, function($__a) use($__extract, $__exist_reset, $__ext, $__dd, $__ss) {
                        if ($__extract && !extension_loaded('zip')) {
                            Guardian::abort('<a href="http://www.php.net/manual/en/book.zip.php" title="PHP &#x2013; Zip" rel="nofollow" target="_blank">PHP Zip</a> extension is not installed on your web server.');
                        }
                        $__zip = new ZipArchive;
                        if ($__a['extension'] === 'zip' && $__zip->open($__a['path']) === true) {
                            $__aa = [];
                            for ($__i = 0; $__i < $__zip->numFiles; ++$__i) {
                                $__ff = str_replace('/', DS, $__zip->getNameIndex($__i));
                                $__fff = rtrim($__dd . DS . $__ff, DS);
                                // Re-check file extension in the package…
                                $__xx = Path::X($__fff);
                                if ($__xx && !Is::this($__ext)->contain($__xx)) {
                                    Request::save('post');
                                    Message::reset();
                                    Message::error('file_x', '<em>' . $__xx . '</em>');
                                    $__zip->close(); // close to force unlink…
                                    unlink($__a['path']);
                                    Session::reset('panel.v.f');
                                    break;
                                }
                                Session::set('panel.v.f.' . md5($__fff), 1);
                                if ($__extract && $__exist_reset) {
                                    File::open($__fff)->delete();
                                }
                                if ($__i === 0) {
                                    // Highlight the root folder…
                                    $__n = $__dd . DS . explode(DS, $__ff)[0];
                                    $__aa[] = $__n;
                                    Session::set('panel.v.f.', md5($__n));
                                }
                            }
                            if ($__extract && !Message::$x) {
                                $__zip->extractTo($__dd);
                                $__zip->close(); // close to unlink…
                            }
                            if ($__extract) {
                                Hook::fire('on.package.reset', [$__a['path'], null, $__a]);
                                if (file_exists($__a['path'])) {
                                    unlink($__a['path']);
                                }
                            }
                            $__aa = array_unique($__aa);
                            if ($__extract && count($__aa) === 1) {
                                // Mark the root folder on extract if it is the only child
                                $__ss = $__aa[0];
                            }
                        }
                        Session::set('panel.v.f.' . md5($__ss), 1);
                        Hook::fire('on.package.set', [$__ss, $__a['path'], $__a]);
                    });
                }
            }
            if (!Message::$x) {
                $__f = LOT . DS . $__p . DS . $__n;
                $__uu = str_replace('::s::', '::g::', URL::I($url->current));
                if ($__n) {
                    // Create file only if name/path is set
                    File::write(Request::post('content', "", false))->saveTo($__f);
                    Session::set('panel.v.f.' . md5($__f), 1);
                    Hook::fire(['on.file.set', 'on.' . $__chops[0] . '.set'], [$__f, null]);
                    Message::success('create', [$language->file, '<em>' .  $__n . '</em>']);
                }
                if ($__d) {
                    $__d = str_replace(DS, '/', $__d);
                    if (Request::post('o.folder.directory.kick')) {
                        Guardian::kick($__uu . '/' . $__d . '/1' . $__query);
                    } else {
                        Guardian::kick($__uu . '/1' . $__query);
                    }
                }
                Guardian::kick($__uu . '/' . ($__n ?: '1') . $__query);
            } else {
                Guardian::kick($url->current . $__query);
            }
        } else if ($__command === 'g') {
            // Delete file…
            if (Request::post('_x') === 'trash') {
                Guardian::kick(str_replace('::g::', '::r::', URL::I($url->current) . HTTP::query(['token' => Request::post('token')])));
            }
            // Update file…
            $__pp = trim(str_replace('/', DS, Request::post('path')), DS);
            $__pp = To::file($__pp);
            $__x = Path::X($__pp);
            if (!Is::this(File::$config['extensions'])->contain($__x)) {
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
                Guardian::kick($url . '/' . $__state->path . '/::g::/' . $__chops[0] . '/' . str_replace(DS, '/', $__pp) . $__query);
            } else {
                Guardian::kick($url->current . $__query);
            }
        }
    } else {
        if ($__command === 'r') {
            if (!$__t = Request::get('token')) {
                Shield::abort(404);
            } else if (!Guardian::check($__t)) {
                Shield::abort(404);
            }
            if (!$__f = File::exist(LOT . DS . $__p)) {
                Shield::abort(404);
            }
            $__back = str_replace('::r::', '::g::', $url->path);
            if (Message::$x) {
                Guardian::kick($__back . $__query);
            }
            $__ff = str_replace(LOT, LOT . DS . 'trash' . DS . 'lot', $__f);
            Hook::fire('on.' . $__t . '.reset', [$__f, $__ff = Request::get('r') === 1 ? null : $__ff]);
            if (!isset($__ff)) {
                File::open($__f)->delete();
            } else {
                File::open($__f)->moveTo(is_file($__f) ? dirname($__ff) : $__ff);
            }
            $__t = is_dir($__ff) ? 'folder' : 'file';
            if (!Message::get(false)) {
                Message::success('delete', [$language->{$__t}, '<em>' . basename($__f) . '</em>']);
            }
            $__uu = $__state->path . '/::g::/' . dirname($__path);
            Guardian::kick($__uu . '/1' . $__query);
        }
    }
    // Get file
    if ($__command === 'g' && !$__f = File::exist(LOT . DS . $__p)) {
        Shield::abort(404);
    }
    if ($__f && $__command === 's' && is_file($__f)) {
        Shield::abort(404); // Folder only!
    }
    if (Config::get('panel.x.m.file') !== true) {
        $__a = $__aa = File::inspect($__f);
        $__t = basename($__p);
        $__a['title'] = $__t;
        $__a['url'] = $__u . $__path . $__query;
        $__is_not_text = strpos(',' . TEXT_X . ',', ',' . Path::X($__f) . ',') === false;
        if ($__is_not_text && is_file($__f)) {
            $__s = mime_content_type($__f);
            $__is_not_text = !$__s || strpos($__s, 'text/') !== 0;
        }
        $__a['content'] = is_file($__a['path']) ? ($__is_not_text ? false : file_get_contents($__a['path'])) : null;
        $__aa['title'] = '<i class="i i-' . (is_dir($__a['path']) ? 'd' : 'f x-' . $__a['extension']) . '"></i> <span>' . $__t . '</span>';
        Lot::set('__file', $__file = [o($__a), o($__aa)]);
    }
}

// Get parent
if (Config::get('panel.x.s.parent') !== true) {
    $__a = $__aa = File::inspect(rtrim(LOT . DS . dirname($__p), DS));
    $__t = count($__chops) > 2 ? basename(dirname($__p)) : '..';
    $__a['title'] = $__t;
    $__a['url'] = rtrim($__u . dirname($__path), '/') . ($__is_has_step ? '/1' : "") . $__query;
    $__aa['title'] = '<i class="i i-d"></i> <span>' . $__t . '</span>';
    Lot::set('__parent', $__parent = [o($__a), o($__aa)]);
}

// Get child(s)
if (Config::get('panel.x.s.child') !== true) {
    $__b = basename($__p);
    $__g = array_filter(array_merge(
        // Show hidden file(s) for super user only!
        $__user_status === 1 ? glob(LOT . DS . $__p . DS . '.*', GLOB_NOSORT) : [],
        glob(LOT . DS . $__p . DS . '*', GLOB_NOSORT)
    ), function($__v) use($__b) {
        return substr($__v, -2) !== DS . '.' && substr($__v, -3) !== DS . '..' && basename($__v) !== $__b;
    });
    natsort($__g);
    foreach (Anemon::eat($__g)->chunk($__chunk * 2, 0) as $__v) {
        $__a = $__aa = File::inspect($__v);
        $__t = basename($__v);
        $__a['title'] = $__t;
        $__a['url'] = $__u . str_replace([LOT . DS, DS], ["", '/'], $__v) . ($__is_has_step && is_dir($__v) ? '/1' : "") . $__query;
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
        // Show hidden file(s) for super user only!
        $__user_status === 1 ? glob(LOT . DS . $__p . DS . '.*', GLOB_NOSORT) : [],
        glob(LOT . $__pp . DS . '*', GLOB_NOSORT)
    ), function($__v) use($__b) {
        return substr($__v, -2) !== DS . '.' && substr($__v, -3) !== DS . '..' && basename($__v) !== $__b;
    });
    natsort($__g);
    foreach (Anemon::eat($__g)->chunk($__chunk * 2, 0) as $__v) {
        $__a = $__aa = File::inspect($__v);
        $__t = basename($__v);
        $__a['title'] = $__t;
        $__a['url'] = $__u . str_replace([LOT . DS, DS], ["", '/'], $__v) . ($__is_has_step && is_dir($__v) ? '/1' : "") . $__query;
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
        't' => $__is_has_step ? [
            'file' => [
                'content' => require __DIR__ . DS . 'files.m.t.file.php',
                'stack' => 10
            ]
        ] : [
            'file' => [
                'legend' => $language->{$__command === 's' ? 'create' : 'update'},
                'list' => $__is_has_step ? [] : [
                    'content' => $__command === 's' || isset($__file[0]->content) && $__file[0]->content !== false ? [
                        'type' => 'editor',
                        'value' => isset($__file[0]->content) ? ($__file[0]->content ?: "") : null,
                        'attributes' => [
                            'data[]' => [
                                'type' => Anemon::alter($__command === 's' ? 'PHP' : u(Path::X($__path, 'FILE')), [
                                    'JS' => 'JavaScript',
                                    'JSON' => 'JavaScript',
                                    'PAGE' => 'YAML-Frontmatter',
                                    'XML' => 'HTML'
                                ])
                            ]
                        ],
                        'height' => true,
                        'expand' => true,
                        'stack' => 10
                    ] : false,
                    'path' => [
                        'type' => 'text',
                        'value' => $__command === 'g' ? str_replace(['/', LOT . DS . $__chops[0] . DS], [DS, ""], LOT . DS . $__p) : null,
                        'placeholder' => $__command === 's' ? $language->f_file : null,
                        'title' => $__command === 's' ? $language->name : $language->path,
                        'pattern' => $__command === 'g' ? '^[a-z\\d_.-]+(?:[\\\\/][a-z\\d._-]+)*$' : '^[a-z\\d_.-]+$',
                        'width' => true,
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
                        'pattern' => '^[a-z\\d]+(?:[-._\\/][a-z\\d]+)*$',
                        'title' => $language->path,
                        'width' => true,
                        'stack' => 10
                    ],
                    'o[folder][directory]' => [
                        'key' => 'o-folder-directory',
                        'type' => 'toggle[]',
                        'title' => "",
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
                        'title' => $language->file . '/' . $language->package,
                        'stack' => 10
                    ],
                    'o[upload][file]' => [
                        'key' => 'o-upload-file',
                        'type' => 'toggle[]',
                        'title' => "",
                        'value' => ['extract' => false],
                        'values' => [
                            'extract' => [$language->h_file_extract, 1],
                            'exist_reset' => [$language->h_file_exist_reset, 1]
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
                'hidden' => !$__is_has_step,
                'stack' => 10
            ],
            'parent' => [
                'title' => $language->parent,
                'list' => [[$__parent[0]], [$__parent[1]]],
                'hidden' => count($__chops) === 1,
                'stack' => 20
            ],
            'kin' => [
                'list' => $__kins,
                'a' => [
                    'set' => ["", str_replace('::g::', '::s::', $__command === 's' ? $url->current : dirname($url->current)), false, ['title' => $language->add]]
                ],
                'hidden' => count($__chops) === 1 || !$__kins[0],
                'stack' => 30
            ],
            'child' => [
                'list' => $__childs,
                'a' => [
                    'set' => $__command === 's' ? ["", str_replace('::g::', '::s::', $url->current), false, ['title' => $language->add]] : false
                ],
                'hidden' => $__is_has_step || !is_dir(LOT . DS . $__p) || !$__childs[0],
                'stack' => 40
            ],
            'nav' => [
                'title' => $language->navigation,
                'content' => __DIR__ . DS . '..' . DS . 'pages' . DS . '-nav.php',
                'hidden' => !$__is_has_step,
                'stack' => 50
            ]
        ]
    ]
], (array) a(Config::get('panel', []))));