<?php

Hook::set('__comment.url', function($content, $lot) use($__state) {
    $s = Path::F($lot['path'], LOT);
    return rtrim(__url__('url') . '/' . $__state->path . '/::g::/' . ltrim(To::url($s), '/'), '/');
});

$site->is = $__is_has_step ? 'pages' : 'page';
$site->is_f = $__is_has_step ? false : 'editor';
$site->layout = $__is_has_step || $__is_data ? 2 : 3;

if ($__f = File::exist(__DIR__ . DS . 'comment' . DS . $__sgr . '.php')) require $__f;