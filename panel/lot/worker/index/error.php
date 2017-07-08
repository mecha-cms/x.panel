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
    new Page($__file, [], '__page'),
    new Page($__file, [])
]);

$site->is = 'page';
$site->is_f = 'editor';
$site->layout = 2;

Config::set('panel.t', [
    'editor' => [
        'title' => $language->page,
        'content' => __DIR__ . DS . '..' . DS . 'page' . DS . 'error.1.t.editor.php',
        'stack' => 10
    ]
]);