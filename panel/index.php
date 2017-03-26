<?php

// a: anchor
// b:
// c: container
// d: data
// e:
// f: field
// g:
// h: hint
// i: icon
// j:
// k:
// l:
// m: main
// n: navigation
// o:
// p: page/paragraph/path
// q:
// r:
// s: secondary/sidebar
// t:
// u:
// v:
// w:
// x:
// y:
// z:

define('PANEL', __DIR__);

$__state = State::_(Extend::state(__DIR__), []);

$s = PANEL . DS . 'lot' . DS;

define('PANEL_404', File::exist([
    $s . 'shield' . DS . $__state->shield . DS . '404.php',
    $s . 'worker' . DS . '404.php',
    SHIELD . DS . $config->shield . DS . '404.php'
]));

r(__DIR__ . DS . 'engine' . DS . 'plug', [
    'extend.php',
    'form.php',
    'get.php',
    'plugin.php',
    'shield.php'
], null, Lot::set('__state', $__state)->get(null, []));

Config::set('panel.f.page.types.HTML', 'HTML');
Config::set('panel.f.page.types.Markdown', 'Markdown');

require __DIR__ . DS . 'engine' . DS . 'fire.php';