<?php

if (!file_exists(__DIR__ . DS . '..' . DS . $site->is . DS . $__chops[0] . '.php')) {

    $__u = $url . '/' . $__state->path . '/::g::/';
    $__p = str_replace('/', DS, $__path);
    $__query = HTTP::query();

    // Get current
    $__a = $__aa = File::inspect(LOT . DS . $__p);
    $__a['title'] = $__aa['title'] = '<i class="i i-' . (is_dir($__a['path']) ? 'd' : 'f x-' . $__a['extension']) . '"></i> ' . Path::B($__p);
    $__a['url'] = $__u . $__path . $__query;
    Lot::set('__current', $__current = [o($__a), o($__aa)]);

    if ($__is_has_step) {
        // Get file(s)
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
            $__a['url'] = $__u . str_replace([LOT . DS, DS], ["", '/'], $__v) . (is_dir($__v) ? '/1' : "") . $__query;
            $__files[0][] = o($__a);
            $__files[1][] = o($__aa);
        }
        Lot::set([
            '__files' => $__files,
            '__is_has_step_file' => ($__is_has_step_file = count($__g) > $__chunk * 2),
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
        if ($__is_post) {
            if ($__action === 's') {
                // Create file…
                $__n = explode(DS, str_replace('/', DS, Request::post('path')));
                $__n = array_pop($__n);
                $__x = Path::X($__n);
                if (!Is::these(File::$config['extensions'])->has($__x)) {
                    Request::save('post');
                    Message::error('file_x', '<em>' . $__x . '</em>');
                }
                if (!Message::$x) {
                    if ($__s = Request::post('content', "", false)) {
                        File::write($__s)->saveTo(LOT . DS . $__path . DS . $__n);
                    }
                    Message::success('create', [$language->{$__chops[0]}, '<em>' .  $__n . '</em>']);
                    Guardian::kick(str_replace('::s::', '::g::', $url->current) . '/' . $__n . $__query);
                } else {
                    Guardian::kick($url->current . $__query);
                }
            } else if ($__action === 'g') {
                // Delete file…
                if (Request::post('_x') === 'trash') {
                    Guardian::kick(str_replace('::g::', '::r::', $url->current . HTTP::query(['token' => Request::post('token')])));
                }
                // Update file…
                $__p = trim(str_replace('/', DS, Request::post('path')), DS);
                $__x = Path::X($__p);
                if (!Is::these(File::$config['extensions'])->has($__x)) {
                    Request::save('post');
                    Message::error('file_x', '<em>' . $__x . '</em>');
                }
                if (!Message::$x) {
                    if ($__s = Request::post('content', "", false)) {
                        File::write($__s)->saveTo(LOT . DS . $__chops[0] . DS . $__p);
                    } else {
                        File::open(LOT . DS . $__path)->moveTo(LOT . DS . $__chops[0] . DS . $__p);
                    }
                    if ($__chops[0] . DS . $__p !== str_replace('/', DS, $__path)) {
                        File::open(LOT . DS . $__path)->delete();
                    }
                    Message::success('update', [$language->{$__chops[0]}, '<em>' .  Path::B($__p) . '</em>']);
                    Guardian::kick($url . '/' . $__state->path . '/::g::/' . $__chops[0] . '/' . str_replace(DS, '/', $__p) . $__query);
                } else {
                    Guardian::kick($url->current . $__query);
                }
            }
        } else {
            if ($__action === 'r') {
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
                Hook::fire('on.' . $__chops[0] . '.reset', [$__f, $__ff]);
                Message::success('delete', [$language->{$__chops[0]}, '<em>' . Path::B($__f) . '</em>']);
                Guardian::kick(Path::D(str_replace('::r::', '::g::', $url->path)) . '/1' . $__query);
            }
        }
        // Get file
        if ($__action === 'g' && !$__f = File::exist(LOT . DS . $__p)) {
            Shield::abort(PANEL_404);
        }
        if ($__f && $__action === 's' && is_file($__f)) {
            Shield::abort(PANEL_404); // Folder only!
        }
        $__a = $__aa = File::inspect($__f);
        $__a['title'] = $__aa['title'] = '<i class="i i-' . (is_dir($__a['path']) ? 'd' : 'f x-' . $__a['extension']) . '"></i> ' . Path::B($__p);
        $__a['url'] = $__u . $__path . $__query;
        $__a['content'] = is_file($__a['path']) ? (strpos(',' . SCRIPT_X . ',', ',' . Path::X($__p) . ',') === false ? false : file_get_contents($__a['path'])) : null;
        Lot::set('__file', $__file = [o($__a), o($__aa)]);
    }

    // Get parent
    $__a = $__aa = File::inspect(rtrim(LOT . DS . Path::D($__p), DS));
    $__a['title'] = $__aa['title'] = '<i class="i i-d"></i> ' . (count($__chops) > 2 ? Path::B(Path::D($__p)) : '..');
    $__a['url'] = rtrim($__u . Path::D($__path), '/') . ($__is_has_step ? '/1' : "") . $__query;
    Lot::set('__parent', $__parent = [o($__a), o($__aa)]);

    // Get child(s)
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
        $__a['url'] = $__u . str_replace([LOT . DS, DS], ["", '/'], $__v) . ($__is_has_step && is_dir($__v) ? '/1' : "") . $__query;
        $__childs[0][] = o($__a);
        $__childs[1][] = o($__aa);
    }
    Lot::set([
        '__childs' => $__childs,
        '__is_has_step_child' => ($__is_has_step_child = count($__g) > $__chunk * 2)
    ]);

    // Get kin(s)
    if ($__p = Path::D($__p)) {
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
            $__a['url'] = $__u . str_replace([LOT . DS, DS], ["", '/'], $__v) . ($__is_has_step && is_dir($__v) ? '/1' : "") . $__query;
            $__kins[0][] = o($__a);
            $__kins[1][] = o($__aa);
        }
        Lot::set([
            '__kins' => $__kins,
            '__is_has_step_kin' => ($__is_has_step_kin = count($__g) > $__chunk * 2)
        ]);
    }

    Config::set('panel', [
        'layout' => 2,
        'm:f' => !$__is_has_step,
        'm' => [
            't' => [
                'file' => [
                    'legend' => $language->editor,
                    'list' => [
                        'content' => $__action === 's' || isset($__file[0]->content) && $__file[0]->content !== false ? [
                            'type' => 'editor',
                            'value' => isset($__file[0]->content) ? ($__file[0]->content ?: "") : null,
                            'attributes' => [
                                'data' => [
                                    'type' => $__action === 's' ? 'PHP' : u(Path::X($__path, 'HTML'))
                                ]
                            ],
                            'is' => [
                                'expand' => true
                            ],
                            'expand' => true,
                            'stack' => 10
                        ] : null,
                        '*path' => [
                            'type' => 'text',
                            'value' => $__action === 'g' ? str_replace(['/', LOT . DS . $__chops[0] . DS], [DS, ""], LOT . DS . $__path) : null,
                            'pattern' => $__action === 'g' ? '^[a-z\\d-_.]+(?:[\\/][a-z\\d-._]+)*$' : '^[a-z\\d-_.]+$',
                            'is' => [
                                'block' => true
                            ],
                            'stack' => 20
                        ],
                        '_x' => [
                            'type' => 'submit[]',
                            'values' => [
                                Path::X($__path, 'txt') => $language->{$__action === 's' ? 'create' : 'update'},
                                'trash' => $__action === 's' ? null : $language->delete
                            ],
                            'stack' => 0
                        ]
                    ],
                    'stack' => 10
                ],
                'folder' => $__action === 's' || isset($__file[0]->content) && $__file[0]->content !== false && $__file[0]->is->files ? [
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
                            'stack' => 20
                        ]
                    ],
                    'stack' => 20
                ] : null,
                'upload' => $__action === 's' || isset($__file[0]->content) && $__file[0]->content !== false && $__file[0]->is->files ? [
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
                    'list' => [[$__parent[0]], [$__parent[1]]],
                    'if' => count($__chops) > 1,
                    'stack' => 20
                ],
                'kin' => [
                    'list' => $__kins,
                    'a' => [
                        'set' => ['&#x2795;', str_replace('::g::', '::s::', $__action === 's' ? $url->current : Path::D($url->current)), false, ['title' => $language->add]]
                    ],
                    'if' => count($__chops) > 1 && $__kins[0],
                    'stack' => 30
                ],
                'child' => [
                    'list' => $__childs,
                    'a' => [
                        'set' => $__action === 's' ? ['&#x2795;', str_replace('::g::', '::s::', $url->current), false, ['title' => $language->add]] : null
                    ],
                    'if' => !$__is_has_step && is_dir(LOT . DS . $__path),
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
}