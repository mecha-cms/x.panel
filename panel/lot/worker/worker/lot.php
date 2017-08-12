<?php

Config::set('__is', $__chops[0]);
Config::set('__step', $__step); // 1–based index…

$__step = $__step - 1; // 0–based index
$__sort = $__state->sort;
$__chunk = $__state->chunk;
$__is_get = Request::is('get');
$__is_post = Request::is('post');
$__is_has_step = $__command === 'g' && (count($__chops) === 1 || is_numeric(basename($url->path))) ? '/1' : ""; // Force index view by appending page offset to the end of URL

Config::set('is', $__is_has_step ? 'pages' : 'page');
Config::set('panel.x.s.data', 'chunk,comments,css,id,js,kind,sort,time');

// Default feature(s) for each user(s)…
Config::set('panel.v.user', array_replace_recursive([
   -1 => false, // banned
    0 => [ // pending
        'user' => function($__user) use($__chops) {
            return $__user && isset($__chops[1]) && $__chops[1] === $__user->key;
        }
    ],
    1 => true, // primary
    2 => [ // secondary
        'comment' => true,
        'page' => true,
        'user' => function($__user) use($__chops) {
            return $__user && isset($__chops[1]) && $__chops[1] === $__user->key;
        }
    ]
], (array) a(Config::get('panel.v.user', []))));

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