<?php

// a: anchor/article
// b: base/name.base
// c: capture/configuration/container/cookie
// d: data/directory
// e:
// f: field/file/folder/form
// g: get
// h: hint
// i: icon/file.include
// j:
// k: key
// l: layout
// m: main
// n: name/navigation
// o: option
// p: page/paragraph/path
// q: query
// r: force/hard/file.require/reset
// s: secondary/session/set/sidebar
// t: tab
// u: upload
// v: mark.check/enable/show/add
// w:
// x: mark.cross/disable/hide/remove
// y: redo
// z: undo

define('PANEL', __DIR__);

$__state = new State(Extend::state(__DIR__), []);
$__s = __DIR__ . DS . 'lot' . DS;

r(__DIR__ . DS . 'engine' . DS . 'plug', [
    'get.php',
    'user.php'
], null, Lot::set('__state', $__state)->get(null, []));

require __DIR__ . DS . 'engine' . DS . 'ignite.php';
require __DIR__ . DS . 'engine' . DS . 'fire.php';