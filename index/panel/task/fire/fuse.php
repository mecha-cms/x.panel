<?php namespace x\panel\task\fire;

function fuse($_) {
    \extract($GLOBALS, \EXTR_SKIP);
    $n = \basename($path = (string) $_['path']);
    $_['kick'] = $_REQUEST['kick'] ?? [
        'hash' => null,
        'part' => 0,
        'path' => 0 === \strpos($n, 'x.') ? 'x/1' : (0 === \strpos($n, 'y.') ? 'y/1' : ($state->x->panel->route ?? 'asset/1')),
        'query' => null,
        'task' => 'get'
    ];
    // Abort by previous hook’s return value if any
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    // TODO
    return $_;
}