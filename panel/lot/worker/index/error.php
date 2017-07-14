<?php

if (!$__file = File::exist(ENGINE . DS . 'log' . DS . 'error.log')) {
    Shield::abort(PANEL_404);
}

Config::set([
    'is' => 'page',
    'panel' => [
        'layout' => 2,
        'c:f' => 'editor',
        'm' => [
            't' => [
                'summary' => [
                    'stack' => 10
                ],
                'detail' => [
                    'stack' => 20
                ]
            ]
        ]
    ]
]);

if (Request::is('post')) {
    File::open($__file)->delete();
    Message::success(To::sentence($language->deleteed));
    Guardian::kick($__state->path . '/::g::/page/1');
}

Lot::set('__page', [
    new Page($__file, [], '__page'),
    new Page($__file, [])
]);