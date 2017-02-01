<?php

$__step = $__step - 1;
$__sort = $__state->sort;
$__chunk = $__state->chunk;
$__is_get = Request::is('get');
$__is_post = Request::is('post');
$__is_r = count($__chops) === 1;
$__is_pages = $__is_r || is_numeric(Path::B($url->path)); // Force index view by appending page offset to the end of URL

$__kins = [[], []];
if ($__files = g(STATE, 'php')) {
    foreach ($__files as $v) {
        $v = (object) ['key' => Path::N($v)];
        $__kins[0][] = $v;
        $__kins[1][] = $v;
    }
}

Lot::set('__kins', $__kins);

$__page = [[], []];
$__name = $__is_r ? 'config' : $__chops[1];
if ($__file = File::exist(STATE . DS . $__name . '.php')) {
    $__f = File::open($__file)->import();
    $__page = [
        new Page(null, ['content' => $__f], '__state'),
        new Page(null, ['content' => $__f], 'state')
    ];
}

Lot::set([
    '__kins' => $__kins,
    '__page' => $__page
]);