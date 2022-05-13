<?php namespace x\panel\task\fire;

function fix($_) {
    if ('GET' === $_SERVER['REQUEST_METHOD']) {
        $file = \ENGINE . \D . 'log' . \D . $_['path'];
        // Invalid token?
        if ($_['token'] !== ($_['query']['token'] ?? \P)) {
            $_['alert']['error'][$file] = 'Invalid token.';
        } else if (\is_file($file)) {
            \unlink($file);
        }
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