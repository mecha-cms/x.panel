<?php

if (!Request::get('token')) {
    Shield::abort(PANEL_404);
}

if (count($__chops) >= 2) {
    if (!Message::$x) {
        if (!$__f = File::exist(LOT . DS . $__path)) {
            Shield::abort(PANEL_404);
        }
        $__n = Path::B($__path);
        if (Request::get('abort')) {
            if (substr($__n, -6) !== '.trash') {
                Shield::abort(PANEL_404);
            }
            $__n = str_replace(['.trash' . X, X], "", $__n . X);
            Folder::open($__f)->renameTo($__n);
            Message::success(To::sentence($language->restoreed));
        } else {
            Folder::open($__f)->renameTo($__n . '.trash');
            Message::success(To::sentence($language->deleteed) . ' ' . HTML::a($language->restore, $url->current . '.trash' . HTTP::query(['abort' => 1]), false, ['classes' => ['right']]));
        }
        Guardian::kick(str_replace('::r::', '::g::', Path::D($url->current)));
    }
}