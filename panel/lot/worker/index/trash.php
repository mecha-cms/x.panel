<?php

$__query = HTTP::query([
    'token' => false,
    'r' => false
]);

Config::set('panel.s', [
    1 => [
        'child' => [
            'a' => false
        ],
        'kin' => [
            'a' => false
        ]
    ]
]);
Hook::set('panel.a.' . $__chops[0] . 's', function() use($language, $__chops, $__query, $__state, $__token) {
    return [
        'reset' => [$language->delete, $__state->path . '/::r::/' . $__chops[0] . HTTP::query([
            'token' => $__token,
            'r' => 1
        ])]
    ];
}, 0);
Hook::set('panel.a.' . $__chops[0], function($__a, $__v) use($language, $__chops, $__is_has_step, $__state, $__token) {
    return $__v[0]->is->file ? [
        'restore' => [$language->restore, str_replace('::g::', '::z::', $__v[0]->url) . HTTP::query(['token' => $__token])],
        'reset' => [$language->delete, str_replace('::g::', '::r::', $__v[0]->url) . HTTP::query(['token' => $__token, 'r' => 1])]
    ] : [];
}, 0);

if ($__is_get && $__command === 'z') {
    if (!$__t = Request::get('token')) {
        Shield::abort(404);
    }
    if ($__t !== Session::get(Guardian::$config['session']['token'])) {
        Shield::abort(404);
    }
    if (!$__f = File::exist(LOT . DS . $__path)) {
        Shield::abort(404);
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
    }
    Guardian::kick($__state->path . '/::g::/' . $__pp . HTTP::query([
        'token' => false,
        'r' => false
    ]));
}

// Read only!
if ($__command !== 'g' || !$__is_has_step) {
    if ($__is_get && $__command === 'r' && count($__chops) === 1) {
        if (!$__t = Request::get('token')) {
            Shield::abort(404);
        } else if ($__t !== Session::get(Guardian::$config['session']['token'])) {
            Shield::abort(404);
        }
        File::open(LOT . DS . $__chops[0])->delete();
        Hook::fire('on.' . $__chops[0] . '.reset', [null, null]);
        Message::info('void', $language->{$__chops[0]});
        Guardian::kick($__state->path . '/::g::/' . $__state->kick('page') . '/1' . $__query);
    }
    // Shield::abort(404);
}