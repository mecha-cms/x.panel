<?php

if ($__files = glob(SHIELD . DS . '*', GLOB_ONLYDIR)) {
    foreach ($__files as $v) {
        $v = Shield::info(Path::B($v));
        $__pages[0][] = $v;
        $__pages[1][] = $v;
    }
}

$site->is = 'pages';

if (isset($__chops[1])) {
    $site->is = 'page';
    if ($__file = File::exist(LOT . DS . $__path)) {
        if (Is::F($__file)) {
            $s = [
                'key' => str_replace(SHIELD . DS . $__chops[1], "", $__file),
                'content' => file_get_contents($__file)
            ];
            $__page = [
                Page::_($__file, $s, '__file'),
                Page::_($__file, $s, 'file')
            ];
        } else {
            Shield::abort(PANEL_404);
        }
    } else {
        Shield::abort(PANEL_404);
    }
    Lot::set('__page', $__page);
    foreach (File::explore(SHIELD . DS . $__chops[1], true, true) as $k => $v) {
        if ($v === 0) continue;
        $s = [
            'key' => str_replace(SHIELD . DS, "", $k)
        ];
        $__kins[0][] = Page::_($k, $s, '__file');
        $__kins[1][] = Page::_($k, $s, 'file');
    }
    Lot::set('__kins', $__kins);
}

Lot::set([
    '__pages' => $__pages,
    '__pager' => [Elevator::_($__files ?: [], $__chunk, $__step, $url . '/' . $__state->path . '/::g::/' . $__path, [
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