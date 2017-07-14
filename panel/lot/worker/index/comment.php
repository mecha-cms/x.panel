<?php

Hook::set('__page.url', function($__content, $__lot) use($__state) {
    $__s = Path::F($__lot['path'], LOT);
    return rtrim($__state->path . '/::g::/' . ltrim(To::url($__s), '/'), '/');
});

Hook::set('__comment.url', function($__content, $__lot) use($__state) {
    $__s = Path::F($__lot['path'], LOT);
    return rtrim($__state->path . '/::g::/' . ltrim(To::url($__s), '/'), '/');
});

if ($__f = File::exist(__DIR__ . DS . 'comment' . DS . $__action . '.php')) require $__f;

Config::set([
    'is' => $__is_has_step ? 'pages' : 'page',
    'panel' => [
        'layout' => $__is_has_step ? 2 : 3,
        'c:f' => $__is_has_step ? false : 'editor',
        'm' => [
            't' => [
                'page' => [
                    'title' => $language->comment,
                    'stack' => 10
                ]
            ]
        ]
    ]
]);