<?php namespace x\panel\task\fire;

function fix($_) {
    if ('GET' === $_SERVER['REQUEST_METHOD']) {
        if (\is_file($file = \ENGINE . \D . 'log' . \D . $_['path'])) {
            \unlink($file);
        }
    }
    $_['kick'] = $_REQUEST['kick'] ?? [
        'query' => \x\panel\_query_set(),
        'task' => 'get'
    ];
    return $_;
}

// Special case, need to execute this function immediately!
$_ = fix($_);

// Redirect away!
if (isset($_['kick'])) {
    \kick($_['kick']);
}