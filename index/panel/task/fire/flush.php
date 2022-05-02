<?php namespace x\panel\task\fire;

function flush($_) {
    foreach (\g($folder = \LOT . \D . $_['path'], null, true) as $k => $v) {
        0 === $v ? \rmdir($k) : \unlink($k);
    }
    $_['alert']['success'][$folder] = ['Folder %s is now empty.', ['<code>' . \x\panel\from\path($folder) . '</code>']];
    $_['kick'] = $_REQUEST['kick'] ?? [
        'part' => 1,
        'query' => null,
        'task' => 'get'
    ];
    return $_;
}