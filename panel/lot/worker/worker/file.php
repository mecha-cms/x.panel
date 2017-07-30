<?php

$__u = $url . '/' . $__state->path . '/::g::/';
$__p = str_replace('/', DS, $__path);

// Get current
$__a = $__aa = File::inspect(LOT . DS . $__p);
$__a['title'] = $__aa['title'] = '<i class="i i-' . (is_dir($__a['path']) ? 'd' : 'f x-' . $__a['extension']) . '"></i> ' . Path::B($__p);
$__a['url'] = $__u . $__path;
Lot::set('__current', $__current = [o($__a), o($__aa)]);

if ($__is_has_step) {
    // Get file(s)
    if (!$__g = array_filter(array_merge(
        glob(LOT . DS . $__p . DS . '.*', GLOB_NOSORT),
        glob(LOT . DS . $__p . DS . '*', GLOB_NOSORT)
    ), function($__v) {
        return substr($__v, -2) !== DS . '.' && substr($__v, -3) !== DS . '..';
    })) {
        // File/folder not found!
        Shield::abort(PANEL_404);
    }
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
    // Get file
    if (!$__f = File::exist(LOT . DS . $__p)) {
        Shield::abort(PANEL_404);
    }
    if ($__action === 's' && is_file($__f)) {
        Shield::abort(PANEL_404); // Folder only!
    }
    $__a = $__aa = File::inspect($__f);
    $__a['title'] = $__aa['title'] = '<i class="i i-' . (is_dir($__a['path']) ? 'd' : 'f x-' . $__a['extension']) . '"></i> ' . Path::B($__p);
    $__a['url'] = $__u . $__path;
    $__a['content'] = is_file($__a['path']) ? (strpos(',' . SCRIPT_X . ',', ',' . Path::X($__p) . ',') === false ? false : file_get_contents($__a['path'])) : null;
    Lot::set('__file', $__file = [o($__a), o($__aa)]);
}

// Get parent
$__a = $__aa = File::inspect(rtrim(LOT . DS . Path::D($__p), DS));
$__a['title'] = $__aa['title'] = '<i class="i i-d"></i> ' . (count($__chops) > 2 ? Path::B(Path::D($__p)) : '..');
$__a['url'] = rtrim($__u . Path::D($__path), '/') . ($__is_has_step ? '/1' : "");
Lot::set('__parent', $__parent = [o($__a), o($__aa)]);

// Get kin(s)
$__b = Path::B($__p);
$__p = Path::D($__p);
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

Config::set('panel', [
    'layout' => 2,
    'm:f' => !$__is_has_step,
    'm' => [
        't' => [
            'file' => [
                'legend' => $language->editor,
                'content' => [
                    'content' => $__action === 's' || isset($__file[0]->content) && $__file[0]->content !== false ? [
                        'type' => 'editor',
                        'value' => isset($__file[0]->content) ? ($__file[0]->content ?: "") : null,
                        'attributes' => [
                            'data' => [
                                'type' => $__action === 's' ? 'HTML' : u(Path::X($__path, 'HTML'))
                            ]
                        ],
                        'is' => [
                            'expand' => true
                        ],
                        'expand' => true,
                        'stack' => 10
                    ] : null,
                    '*path' => [
                        'type' => $__action === 's' ? 'hidden' : 'text',
                        'value' => str_replace(['/', LOT . DS . $__chops[0] . DS], [DS, ""], LOT . DS . $__path),
                        'pattern' => '^[a-z\\d-_.]+(?:[\\/][a-z\\d-._]+)*$',
                        'is' => [
                            'block' => true
                        ],
                        'stack' => 20
                    ],
                    '*name' => $__action === 's' ? [
                        'type' => 'text',
                        'pattern' => '^[a-z\\d-_.]+$',
                        'is' => [
                            'block' => true
                        ],
                        'stack' => 20
                    ] : null,
                    '_' => [
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
            'folder' => $__action === 's' || isset($__file[0]->content) && $__file[0]->content !== false && $__file[0]->is->folder ? [
                'content' => [
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
                        'text' => 'Redirect to folder.',
                        'stack' => 20
                    ]
                ],
                'stack' => 20
            ] : null,
            'upload' => $__action === 's' || isset($__file[0]->content) && $__file[0]->content !== false && $__file[0]->is->folder ? [
                'content' => [
                    'file' => [
                        'type' => 'file',
                        'stack' => 10
                    ],
                    'extract' => [
                        'type' => 'toggle',
                        'title' => null,
                        'text' => 'Extract package after upload.',
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
                'content' => [[$__parent[0]], [$__parent[1]]],
                'if' => count($__chops) > 1,
                'stack' => 20
            ],
            'kin' => [
                'content' => $__kins,
                'if' => count($__chops) > 1 && $__kins[0],
                'stack' => 30
            ],
            'nav' => [
                'title' => $language->navigation,
                'content' => '<p>' . $__pager[0] . '</p>',
                'if' => $__is_has_step,
                'stack' => 40
            ],
            'child' => null
        ]
    ]
]);