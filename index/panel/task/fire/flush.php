<?php namespace x\panel\task\fire;

function flush($_) {
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