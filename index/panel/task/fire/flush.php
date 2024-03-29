<?php namespace x\panel\task\fire;

function flush($_) {
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error']) || $_['status'] >= 400) {
        return $_;
    }
    foreach (\g($folder = \LOT . \D . $_['path'], null, true) as $k => $v) {
        0 === $v ? \rmdir($k) : \unlink($k);
    }
    $_['alert']['success'][$folder] = ['Folder %s successfully flushed.', ['<code>' . \x\panel\from\path($folder) . '</code>']];
    $_['kick'] = $_REQUEST['kick'] ?? [
        'part' => 1,
        'query' => \x\panel\_query_set(),
        'task' => 'get'
    ];
    return $_;
}