<?php

$__step = $__step - 1;
$__sort = $__state->sort;
$__chunk = $__state->chunk;
$__is_get = Request::is('get');
$__is_post = Request::is('post');
$__is_r = count($__chops) === 1;
$__is_pages = $__is_r || is_numeric(Path::B($url->path)); // Force index view by appending page offset to the end of URL

$__pages = [[], []];
if ($__files = glob(SHIELD . DS . '*', GLOB_ONLYDIR)) {
    foreach ($__files as $v) {
        $v = Shield::info(Path::B($v));
        $__pages[0][] = $v;
        $__pages[1][] = $v;
    }
}

$site->type = 'pages';

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