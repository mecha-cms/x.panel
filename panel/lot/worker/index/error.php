<?php

if (!$__file = File::exist(ENGINE . DS . 'log' . DS . 'error.log')) {
    Shield::abort(PANEL_404);
}

if (Request::is('post')) {
    File::open($__file)->delete();
    Message::success(To::sentence($language->deleteed));
    Guardian::kick($__state->path . '/::g::/page/1');
}

Lot::set('__page', [
    new Page($__file, [
        'type' => ""
    ], '__page'),
    new Page($__file, [
        'type' => ""
    ])
]);

$site->is = 'page';
$site->is_f = 'editor';
$site->layout = 2;