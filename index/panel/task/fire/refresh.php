<?php namespace x\panel\task\fire;

function refresh($_) {
    foreach (\g($folder = \LOT . \D . 'cache', null, true) as $k => $v) {
        0 === $v ? \rmdir($k) : \unlink($k);
    }
    $_['alert']['success'][$folder] = 'Cache successfully refreshed.';
    $_['kick'] = $_REQUEST['kick'] ?? [
        'part' => 1,
        'path' => 'cache',
        'query' => \x\panel\_query_set(),
        'task' => 'get'
    ];
    return $_;
}