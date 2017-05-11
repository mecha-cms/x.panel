<?php

Hook::set('__page.url', function($content, $lot) use($__state) {
    $s = Path::D(Path::F($lot['path'], LOT));
    return rtrim(__url__('url') . '/' . $__state->path . '/::g::/' . ltrim(To::url($s), '/'), '/');
});

// `panel/::s::/shield` → upload a new shield
// `panel/::g::/shield` → index view
// `panel/::s::/shield/blastula` → create new child file in `lot\shield\blastula`
// `panel/::g::/shield/blastula` → view blastula shield file(s)
$site->is = $__is_has_step ? 'pages' : 'page';
$site->is_f = $__is_has_step ? false : 'editor';
$site->layout = 2;

if ($__f = File::exist(__DIR__ . DS . 'shield' . DS . $__sgr . '.php')) require $__f;