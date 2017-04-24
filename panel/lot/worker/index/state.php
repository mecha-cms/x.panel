<?php

if ($__files = g(STATE, 'php')) {
    foreach ($__files as $__v) {
        $__v = o(File::inspect($__v));
        $__kins[0][] = $__v;
        $__kins[1][] = $__v;
    }
}

Lot::set('__kins', $__kins);

$__name = count($__chops) === 1 ? 'config' : $__chops[1];
if ($__file = File::exist(STATE . DS . $__name . '.php')) {
    $s = [
        'path' => $__file,
        'config' => File::open($__file)->import()
    ];
    $__page = [
        new Page(null, $s, '__state'),
        new Page(null, $s, 'state')
    ];
}

Lot::set([
    '__kins' => $__kins,
    '__page' => $__page
]);

if ($__is_post) {
    if ($c = Request::post('content')) {
        File::export(From::yaml($c))->saveTo(STATE . DS . $__name . '.php', 0600);
    } else {
        File::export(Request::post('config'))->saveTo(STATE . DS . $__name . '.php', 0600);
    }
    Message::success(To::sentence($language->updateed));
    Guardian::kick($url->current);
}