<?php

if (!$__log = File::exist(ENGINE . DS . 'log' . DS . 'error.log')) {
    Shield::abort(PANEL_404);
}

Config::set([
    'is' => 'page',
    'panel' => [
        'layout' => 2,
        'c:f' => true,
        'm' => [
            't' => [
                'summary' => [
                    'stack' => 10
                ],
                'detail' => [
                    'stack' => 20
                ],
                'file' => null,
                'folder' => null,
                'upload' => null
            ]
        ],
        's' => [
            1 => null
        ]
    ]
]);

if ($__is_post) {
    File::open($__log)->delete();
    Message::success(To::sentence($language->deleteed));
    Guardian::kick($__state->path . '/::g::/page/1');
}

Lot::set('__page', [
    new Page($__log, [], '__' . $__chops[0]),
    new Page($__log, [], $__chops[0])
]);