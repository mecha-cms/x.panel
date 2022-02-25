<?php namespace x\panel\task;

function fire($_) {}
function get($_) {}
function let($_) {}
function set($_) {}

// Require task(s) from file to run after panel type is set
$tasks = \array_reverse(\step(\trim(\strtr($_['task'] ?? 'get', '/', \D), \D), \D));
foreach ($tasks as $task) {
    \is_file($f = __DIR__ . \D . 'task' . \D . $task . '.php') && (static function($f) {
        \extract($GLOBALS, \EXTR_SKIP);
        if ($_ = require $f) {
            $GLOBALS['_'] = array_replace_recursive($GLOBALS['_'], (array) $_);
        }
    })($f);
}