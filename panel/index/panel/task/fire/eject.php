<?php namespace x\panel\task\fire;

function eject($_) {
    $name = \basename($folder = \LOT . \D . 'x' . \D . $_['path']);
    if (\is_file($file = $folder . \D . 'index.x')) {
        $_['alert']['info'][$file] = ['Extension %s already ejected.', ['<code>' . $name . '</code>']];
    } else if (\is_file($file = $folder . \D . 'index.php')) {
        if (\rename($file, $folder . \D . 'index.x')) {
            $_['alert']['success'][$file] = ['Extension %s successfully ejected.', ['<code>' . $name . '</code>']];
        }
    } else {
        $_['alert']['error'][$file] = ['Extension %s could not be ejected.', ['<code>' . $name . '</code>']];
    }
    $_['kick'] = $_REQUEST['kick'] ?? [
        'path' => 'x',
        'query' => null,
        'task' => 'get'
    ];
    $_SESSION['_']['folder'][$folder] = 1;
    return $_;
}