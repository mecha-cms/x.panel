<?php namespace x\panel\task\fire;

function fix($_) {
    // // Abort by previous hook’s return value if any
    // if (isset($_['kick']) || !empty($_['alert']['error'])) {
    //     return $_;
    // }
    if (\is_file($log = \ENGINE . \D . 'log' . \D . 'error')) {
        \unlink($log);
    }
    $_['kick'] = $_REQUEST['kick'] ?? [
        'query' => null,
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