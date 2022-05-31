<?php namespace x\panel\task\fire;

function attach($_) {
    $n = \dirname($_['path']);
    $name = \basename($folder = \LOT . \D . $_['path']);
    $title = 'x' === $n ? 'Extension' : ('y' === $n ? 'Layout' : 'Folder');
    if (\is_file($file = $folder . \D . 'index.php')) {
        $_['alert']['info'][$file] = ['%s %s already attached.', [$title, '<code>' . $name . '</code>']];
    } else if (\is_file($file = $folder . \D . '.index.php')) {
        if (\rename($file, $folder . \D . 'index.php')) {
            $_['alert']['success'][$file] = ['%s %s successfully attached.', [$title, '<code>' . $name . '</code>']];
            if (!empty($_['query']['radio'])) {
                // Detach other(s)
                foreach (\g(\dirname($folder), 'php', 1) as $k => $v) {
                    if ('index.php' !== \basename($k)) {
                        continue;
                    }
                    if ($folder . \D . 'index.php' === $k) {
                        continue;
                    }
                    \rename($k, \dirname($k) . \D . '.' . \basename($k));
                }
            }
        }
    } else {
        $_['alert']['error'][$file] = ['%s %s could not be attached.', [$title, '<code>' . $name . '</code>']];
    }
    $_['kick'] = $_REQUEST['kick'] ?? [
        'path' => $n,
        'query' => \x\panel\_query_set(['radio' => null]),
        'task' => 'get'
    ];
    $_SESSION['_']['folder'][$folder] = 1;
    return $_;
}