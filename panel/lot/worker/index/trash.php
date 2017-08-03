<?php

if ($__is_get && $__action === 'x') {
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
    Guardian::kick($__state->path . '/::g::/' . str_replace([LOT . DS, DS], ["", '/'], $__ff));
}

// Read only!
if ($__action !== 'g') {
    Shield::abort(PANEL_404);
}

Hook::set('panel.a.' . $__chops[0] . 's', function($__a) {
    return [];
});

Hook::set('panel.a.' . $__chops[0], function($__a, $__v) use($language, $__chops, $__state, $__token) {
    return $__v[0]->is->file ? [
        [$language->restore, str_replace('::g::', '::x::', $__v[0]->url) . HTTP::query(['token' => $__token])]
    ] : [];
});