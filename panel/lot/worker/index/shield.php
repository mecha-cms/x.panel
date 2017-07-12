<?php

// `panel/::s::/shield` → upload a new shield
// `panel/::g::/shield` → index view
// `panel/::s::/shield/blastula` → create new child file in `lot\shield\blastula`
// `panel/::g::/shield/blastula` → view blastula shield file(s)
Config::set([
    'is' => $__is_has_step ? 'pages' : 'page',
    'is_f' => $__is_has_step ? false : 'editor',
    'panel' => [
        'layout' => 2,
        'm' => [
            't' => [
                'file' => [
                    'title' => $language->editor,
                    'stack' => 10
                ]
            ]
        ]
    ]
]);

Hook::set('__page.url', function($__content, $__lot) use($__state) {
    $__s = Path::D(Path::F($__lot['path'], LOT));
    return rtrim($__state->path . '/::g::/' . ltrim(To::url($__s), '/'), '/');
});

if ($__f = File::exist(__DIR__ . DS . 'shield' . DS . $__action . '.php')) require $__f;