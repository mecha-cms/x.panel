<?php namespace x\panel\task\fire;

function plug($_) {
    $name = \basename($folder = \LOT . \D . 'x' . \D . $_['path']);
    if (\is_file($file = $folder . \D . 'index.php')) {
        $_['alert']['info'][$file] = ['Extension %s already plugged.', ['<code>' . $name . '</code>']];
    } else if (\is_file($file = $folder . \D . 'index.x')) {
        if (\rename($file, $folder . \D . 'index.php')) {
            $_['alert']['success'][$file] = ['Extension %s successfully plugged.', ['<code>' . $name . '</code>']];
        }
    } else {
        $_['alert']['error'][$file] = ['Extension %s could not be plugged.', ['<code>' . $name . '</code>']];
    }
    $_['kick'] = $_REQUEST['kick'] ?? [
        'path' => 'x',
        'query' => null,
        'task' => 'get'
    ];
    $_SESSION['_']['folder'][$folder] = 1;
    return $_;
}