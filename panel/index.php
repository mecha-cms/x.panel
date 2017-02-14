<?php

define('PANEL', __DIR__);

$__state = new State(Extend::state(__DIR__), []);

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

Panel::set('f.types.HTML', 'HTML');
Panel::set('f.types.Markdown', 'Markdown');

Panel::set('f.sorts', [
    'time' => '<em>time</em>',
    'slug' => '<em>slug</em>',
    'update' => '<em>update</em>'
]);

require __DIR__ . DS . 'engine' . DS . 'fire.php';