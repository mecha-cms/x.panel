<?php namespace x\panel\task\fire;

function toggle($_) {
    $name = \basename($folder = $_['folder'] ?? P);
    if (\is_file($file = $folder . \D . 'index.php')) {
        if (\rename($file, $folder . \D . 'index.x')) {
            $_['alert']['success'][$file] = ['Extension %s successfully deactivated.', ['<code>' . $name . '</code>']];
        }
    } else if (\is_file($file = $folder . \D . 'index.x')) {
        if (\rename($file, $folder . \D . 'index.php')) {
            $_['alert']['success'][$file] = ['Extension %s successfully activated.', ['<code>' . $name . '</code>']];
        }
    } else {
        $_['alert']['error'][$file] = ['Extension %s could not be toggled.', ['<code>' . $name . '</code>']];
    }
    $_SESSION['_']['folder'][$folder] = 1;
    return $_;
}