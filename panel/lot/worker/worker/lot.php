<?php

$__ = explode('/+/', $__path . '/');
$__key = isset($__[1]) ? To::key(rtrim($__[1], '/')) : null;

$__step = $__step - 1;
$__sort = $__state->sort;
$__chunk = $__state->chunk;
$__is_get = Request::is('get');
$__is_post = Request::is('post');
$__is_r = count($__chops) === 1;
$__is_pages = $__is_r || is_numeric(Path::B($url->path)) ? '/1' : ""; // Force index view by appending page offset to the end of URL
$__is_data = substr($__path, -2) === '/+' || strpos($__path, '/+/') !== false;

$__seeds = [
    '__child' => [[], []],
    '__data' => [[], []],
    '__kin' => [[], []],
    '__page' => [[], []],
    '__parent' => [[], []],
    '__source' => [[], []],
    // Why “child(s)” and “data(s)”? Please open `lot\language\en-us.page` for more info
    '__childs' => [[], []],
    '__datas' => [[], []],
    '__kins' => [[], []],
    '__pages' => [[], []],
    '__parents' => [[], []],
    '__sources' => [[], []],
    '__pager' => [null, null],
    '__is_child_has_step' => false,
    '__is_data_has_step' => false,
    '__is_kin_has_step' => false,
    '__is_page_has_step' => false,
    '__is_parent_has_step' => false,
    '__is_source_has_step' => false,
    '__is_pages' => $__is_pages,
    '__is_data' => $__is_data
];

extract(Lot::set($__seeds)->get(null, []));