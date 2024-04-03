<?php namespace x\panel\task;

function fire($_) {
    if ('GET' === $_SERVER['REQUEST_METHOD']) {
        // Invalid token?
        if ($_['token'] !== ($_['query']['token'] ?? \P)) {
            $_['alert']['error'][] = 'Invalid token.';
        }
        $_['kick'] = $_GET['kick'] ?? null;
    }
    return $_;
}

function get($_) {}
function let($_) {}
function set($_) {}

// Require task(s) from file to run after panel type is set
$tasks = \array_reverse(\step(\trim(\strtr($_['task'] ?? 'get', '/', \D), \D), \D));
foreach ($tasks as $task) {
    \is_file($f = __DIR__ . \D . 'task' . \D . $task . '.php') && (static function ($f) {
        \extract(\lot(), \EXTR_SKIP);
        if (($_ = require $f) && \is_array($_)) {
            \lot('_', \array_replace_recursive(\lot('_'), $_));
        }
    })($f);
}

// Get
$_ = lot('_');