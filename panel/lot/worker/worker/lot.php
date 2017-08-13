<?php

Config::set('__is', $__chops[0]);
Config::set('__step', $__step); // 1–based index…

$__step = $__step - 1; // 0–based index
$__sort = $__state->sort;
$__chunk = $__state->chunk;
$__is_get = Request::is('get');
$__is_post = Request::is('post');

// Force index view by appending page offset to the end of URL
$__is_has_step = $__command === 'g' && (count($__chops) === 1 || is_numeric(basename($url->path))) ? '/1' : "";

// Automatic page type
Config::set('is', $__is_has_step ? 'pages' : 'page');

// List of field(s) that should not be visible in the embedded data
Config::set('panel.x.s.data', 'chunk,comments,css,id,js,kind,sort,time');

// Reserved variable(s)…
$__seeds = [
    '__child' => [[], []],
    '__data' => [[], []],
    '__file' => [[], []],
    '__kin' => [[], []],
    '__page' => [[], []],
    '__parent' => [[], []],
    '__source' => [[], []],
    // Why “child(s)” and “data(s)”? Please open `lot\language\en-us.page` for more info
    '__childs' => [[], []],
    '__datas' => [[], []],
    '__files' => [[], []],
    '__kins' => [[], []],
    '__pages' => [[], []],
    '__parents' => [[], []],
    '__sources' => [[], []],
    '__pager' => [null, null],
    '__is_has_step' => $__is_has_step,
    '__is_has_step_child' => false,
    '__is_has_step_data' => false,
    '__is_has_step_file' => false,
    '__is_has_step_kin' => false,
    '__is_has_step_page' => false,
    '__is_has_step_parent' => false,
    '__is_has_step_source' => false
];

extract(Lot::set($__seeds)->get(null, []));