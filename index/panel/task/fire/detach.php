<?php namespace x\panel\task\fire;

function detach($_) {
    $n = \dirname($_['path']);
    $name = \basename($folder = \LOT . \D . $_['path']);
    $title = 'x' === $n ? 'Extension' : ('y' === $n ? 'Layout' : 'Folder');
    if (\is_file($file = $folder . \D . 'index.x')) {
        $_['alert']['info'][$file] = ['%s %s already detached.', [$title, '<code>' . $name . '</code>']];
    } else if (\is_file($file = $folder . \D . 'index.php')) {
        if (\rename($file, $folder . \D . 'index.x')) {
            $_['alert']['success'][$file] = ['%s %s successfully detached.', [$title, '<code>' . $name . '</code>']];
            if (!empty($_GET['radio'])) {
                // Attach other(s)
                foreach (\g(\dirname($folder), 'x', 1) as $k => $v) {
                    if ('index.x' !== \basename($k)) {
                        continue;
                    }
                    if ($folder . \D . 'index.x' === $k) {
                        continue;
                    }
                    \rename($k, \dirname($k) . \D . \basename($k, '.x') . '.php');
                }
            }
        }
    } else {
        $_['alert']['error'][$file] = ['%s %s could not be detached.', [$title, '<code>' . $name . '</code>']];
    }
    $_['kick'] = $_REQUEST['kick'] ?? [
        'path' => $n,
        'query' => null,
        'task' => 'get'
    ];
    $_SESSION['_']['folder'][$folder] = 1;
    return $_;
}