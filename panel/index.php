<?php

// a: anchor/article
// b:
// c: configuration/container
// d: data
// e:
// f: field/form
// g:
// h: hint
// i: icon
// j:
// k:
// l:
// m: main
// n: navigation
// o: option
// p: page/paragraph/path
// q:
// r: require path
// s: secondary/sidebar
// t: tab
// u:
// v: check mark/enable/show/add
// w:
// x: cross mark/disable/hide/remove
// y:
// z:

define('PANEL', __DIR__);

$__state = new State(Extend::state(__DIR__), []);

$__s = PANEL . DS . 'lot' . DS;

define('PANEL_404', File::exist([
    $__s . 'shield' . DS . $__state->shield . DS . '404.php',
    $__s . 'worker' . DS . '404.php',
    SHIELD . DS . $config->shield . DS . '404.php'
]));

r(__DIR__ . DS . 'engine' . DS . 'plug', [
    'get.php',
    'user.php'
], null, Lot::set('__state', $__state)->get(null, []));

require __DIR__ . DS . 'engine' . DS . 'ignite.php';
require __DIR__ . DS . 'engine' . DS . 'fire.php';