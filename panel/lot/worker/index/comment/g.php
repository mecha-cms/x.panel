<?php

if ($__is_has_step) {
    if ($__files = File::explore(COMMENT, true, true)) {
        $__f = [];
        foreach ($__files as $__k => $__v) {
            $__x = Path::X($__k);
            if ($__v === 0 || (
                $__x !== 'draft' &&
                $__x !== 'page' &&
                $__x !== 'archive'
            )) continue;
            $__f[Path::N($__k)] = $__k;
        }
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
        krsort($__f);
        foreach (Anemon::eat($__f)->chunk($__chunk, $__step) as $__v) {
            $__pages[0][] = new Comment($__v, [], '__comment');
            $__pages[1][] = new Comment($__v, [], 'comment');
        }
        Lot::set([
            '__pages' => $__pages,
            '__pager' => [new Elevator($__f ?: [], $__chunk, $__step, $url . '/' . $__state->path . '/::g::/comment', [
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
            ], '__comments')]
        ]);
    }
} else {
    if ($__file = File::exist([
        LOT . DS . $__path . '.draft',
        LOT . DS . $__path . '.page',
        LOT . DS . $__path . '.archive'
    ])) {
        $__page = [
            new Page($__file, [], '__page'),
            new Page($__file)
        ];
        Lot::set('__page', $__page);
    }
}