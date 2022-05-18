<?php namespace x\panel\task\fire;

function recover($_) {
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error'])) {
        return $_;
    }
    $_['kick'] = $_REQUEST['kick'] ?? [
        'path' => 'trash',
        'query' => \x\panel\_query_set(),
        'task' => 'get'
    ];
    return $_;
}