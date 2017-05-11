<?php

if ($__f = Get::pages($__folder, 'draft,page,archive', $__sort, 'path')) {
    if ($__q = l(Request::get('q', ""))) {
        Message::info('search', '<em>' . $__q . '</em>');
        $__q = explode(' ', $__q);
        $__f = array_filter($__f, function($__v) use($__q) {
            $__v = Path::N($__v);
            foreach ($__q as $_) {
                if (strpos($__v, $_) !== false) {
                    return true;
                }
            }
            return false;
        });
    }
    foreach (Anemon::eat($__f)->chunk($__chunk, $__step) as $__v) {
        $__pages[0][] = new Page($__v, [], '__page');
        $__pages[1][] = new Page($__v, [], 'page');
    }
    $__is_has_step_page = count($__f) > $__chunk;
    Lot::set([
        '__pages' => $__pages,
        '__is_has_step_page' => $__is_has_step_page
    ]);
}

Lot::set([
    '__pager' => [new Elevator($__f ?: [], $__chunk, $__step, $url . '/' . $__state->path . '/::g::/' . $__path, [
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
    ], '__pages')]
]);