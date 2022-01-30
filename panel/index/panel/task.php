<?php namespace x\panel\task;

function fire($_) {}
function get($_) {}
function let($_) {}
function set($_) {}

\is_file($f = __DIR__ . \D . 'task' . \D . \trim(\strtr($task = $_['task'] ?? 'get', '/', \D), \D) . '.php') && (static function($f) {
    \extract($GLOBALS, \EXTR_SKIP);
    if ($_ = require $f) {
        $GLOBALS['_'] = array_replace_recursive($GLOBALS['_'], (array) $_);
    }
})($f);

foreach (\array_values(\step($task . "\\" . \strtr($type = $_['type'] ?? \P, '/', "\\"), "\\")) as $v) {
    // Function-based task
    if (\is_callable($f = "\\x\\panel\\task\\" . $v)) {
        \Hook::set(\strtr('do.' . $type . '.' . $task, "\\", '/'), $f, 10);
    }
}