<?php namespace x\panel\task\fire;

function flush($_) {
    foreach (\g($folder = \LOT . \D . 'trash', null, true) as $k => $v) {
        0 === $v ? \rmdir($k) : \unlink($k);
    }
    $_['alert']['success'][$folder] = 'Trash is now empty.';
    $_['kick'] = $_REQUEST['kick'] ?? \x\panel\to\link([
        'part' => 1,
        'path' => 'trash',
        'query' => null,
        'task' => 'get'
    ]);
    return $_;
}