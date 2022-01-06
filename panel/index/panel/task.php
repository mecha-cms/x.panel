<?php namespace x\panel\task;

function fire($_) {}
function get($_) {}
function let($_) {}
function set($_) {}

\is_file($f = __DIR__ . \D . 'task' . \D . \trim(\strtr($_['task'], '/', \D), \D) . '.php') && (static function($f) {
    \extract($GLOBALS, \EXTR_SKIP);
    if ($_ = require $f) {
        $GLOBALS['_'] = array_replace_recursive($GLOBALS['_'], (array) $_);
    }
})($f);