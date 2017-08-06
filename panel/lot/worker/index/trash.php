<?php

Hook::set('shield.enter', function() {
    extract(Lot::get(null, []));
    Config::set('panel.s', [
        1 => [
            'child' => [
                'a' => null
            ],
            'kin' => [
                'a' => null
            ]
        ]
    ]);
    Hook::set('panel.a.' . $__chops[0] . 's', function() use($language) {
        return [
            'reset' => ['&#x2716; ' . $language->delete, '#']
        ];
    });
    Hook::set('panel.a.' . $__chops[0], function($__a, $__v) use($language, $__chops, $__is_has_step, $__state, $__token) {
        return $__v[0]->is->file ? [
            'restore' => [$language->restore, str_replace('::g::', '::x::', $__v[0]->url) . HTTP::query(['token' => $__token])],
            'reset' => [$language->delete, str_replace('::g::', '::r::', $__v[0]->url) . HTTP::query(['token' => $__token, 'force' => 1])]
        ] : [];
    });
}, 0);

if ($__is_get && $__command === 'x') {
    if (!$__t = Request::get('token')) {
        Shield::abort(PANEL_404);
    }
    if ($__t !== Session::get(Guardian::$config['session']['token'])) {
        Shield::abort(PANEL_404);
    }
    if (!$__f = File::exist(LOT . DS . $__path)) {
        Shield::abort(PANEL_404);
    }
    $__back = str_replace('::r::', '::g::', $url->path);
    if (Message::$x) {
        Guardian::kick($__back);
    }
    $__ff = str_replace(LOT . DS . $__chops[0] . DS . 'lot', LOT, $__f);
    File::open($__f)->moveTo(is_file($__f) ? Path::D($__ff) : $__ff);
    Hook::fire('on.' . $__chops[0] . '.reset', [$__f, $__f]);
    Message::success('restore', [$language->{$__chops[0]}, '<em>' . Path::B($__f) . '</em>']);
    $__pp = str_replace([LOT . DS, DS], ["", '/'], $__ff);
    $__xx = Path::X($__pp);
    if ($__xx === 'draft' || $__xx === 'page' || $__xx === 'archive') {
        $__pp = Path::F($__pp);
    } else if ($__xx === 'data') {
        $__aa = explode('/', Path::F($__pp, null, '/'));
        $__nn = array_pop($__aa);
        $__pp = implode('/', $__aa) . '/+/' . $__nn;
    } else {
        $__query = HTTP::query([
            'token' => false,
            'force' => false,
            'l' => 'file'
        ]);
    }
    Guardian::kick($__state->path . '/::g::/' . $__pp . $__query);
}

// Read only!
if ($__command !== 'g' || !$__is_has_step) {
    Shield::abort(PANEL_404);
}