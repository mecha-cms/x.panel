<?php namespace x\panel\task\fire;

function detach($_) {
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error']) || $_['status'] >= 400) {
        return $_;
    }
    $n = \dirname($_['path']);
    $name = \basename($folder = \LOT . \D . $_['path']);
    $title = 'x' === $n ? 'Extension' : ('y' === $n ? 'Layout' : 'Folder');
    if (\is_file($file = $folder . \D . 'index.x')) {
        $_['alert']['info'][$file] = ['%s %s already detached.', [$title, '<code>' . $name . '</code>']];
    } else if (\is_file($file = $folder . \D . 'index.php')) {
        if (\rename($file, $folder . \D . '.index.php')) {
            $_['alert']['success'][$file] = ['%s %s successfully detached.', [$title, '<code>' . $name . '</code>']];
            if (!empty($_['query']['radio'])) {
                // Attach other(s)
                foreach (\g(\dirname($folder), 'php', 1) as $k => $v) {
                    if ('.index.php' !== \basename($k)) {
                        continue;
                    }
                    if ($folder . \D . '.index.php' === $k) {
                        continue;
                    }
                    \rename($k, \dirname($k) . \D . '.' . \basename($k));
                }
            }
        }
    } else {
        $_['alert']['error'][$file] = ['%s %s could not be detached.', [$title, '<code>' . $name . '</code>']];
    }
    $_['kick'] = $_REQUEST['kick'] ?? [
        'path' => $n,
        'query' => \x\panel\_query_set(),
        'task' => 'get'
    ];
    $_SESSION['_']['folders'][$folder] = 1;
    return $_;
}