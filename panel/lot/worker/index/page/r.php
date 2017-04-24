<?php

if ($__is_data) {
    if (!Request::get('token')) {
        Shield::abort(PANEL_404);
    }
    $__folder = explode('/+/', $__folder)[0];
    if (!$__f = File::exist([
        $__folder . DS . $__key . '.data',
        $__folder . DS . $__key . '.trash'
    ])) {
        Shield::abort(PANEL_404);
    }
    Hook::NS('on.data.reset', [$__f]);
    if (Request::get('abort')) {
        File::open($__folder . DS . $__key . '.trash')->renameTo($__key . '.data');
        Message::success(To::sentence($language->restoreed));
    } else {
        File::open($__f)->renameTo($__key . '.trash');
        Message::success(To::sentence($language->deleteed) . ' ' . HTML::a($language->restore, $url->path . HTTP::query(['abort' => 1]), false, ['classes' => ['right']]));
    }
    Guardian::kick($__state->path . '/::g::/' . $__[0]);
} else {
    if (!Request::get('token')) {
        Shield::abort(PANEL_404);
    }
    if (!$__f = File::exist([
        $__folder . '.draft',
        $__folder . '.page',
        $__folder . '.archive',
        $__folder . '.trash'
    ])) {
        Shield::abort(PANEL_404);
    }
    $__k = str_replace('::r::', '::g::', $url->path);
    $__n = Path::B($__folder);
    Hook::fire('on.page.reset', [$__f]);
    if (Message::$x) {
        Guardian::kick($__k);
    }
    if (Request::get('abort')) {
        File::open($__folder . '.trash')->renameTo($__n . '.draft');
        Message::success(To::sentence($language->restoreed));
    } else {
        File::open($__f)->renameTo($__n . '.trash');
        Message::success(To::sentence($language->deleteed) . ' ' . HTML::a($language->restore, $url->path . HTTP::query(['abort' => 1]), false, ['classes' => ['right']]));
    }
    Guardian::kick(Path::D($__k) . '/1');
}