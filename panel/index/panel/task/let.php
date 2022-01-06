<?php namespace x\panel\task\let;

// Prevent user(s) from deleting file(s) above the `.\lot\*` level
if ('POST' === $_SERVER['REQUEST_METHOD'] && false === \strpos(\strtr($source = $_['file'] ?? $_['folder'], [\LOT . \D => ""]), \D)) {
    \abort('Could not delete <code>' . \x\panel\from\path($source) . '</code> because it is above the <code>' . \x\panel\from\path(\LOT) . '</code> directory level.');
}

function file($_) {
    // Method not allowed!
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error'])) {
        return $_;
    }
    if (\is_file($file = $_['file'])) {
        $trash = !empty($_POST['trash']) ? (new \Time($_POST['trash']))->name : false;
        if ($trash) {
            $trash = \strtr($file, [\LOT . \D => \LOT . \D . 'trash' . \D . $trash . \D]);
            if (!\is_dir($folder = \dirname($trash))) {
                \mkdir($folder, 0775, true);
            }
            \rename($file, $trash);
            $_SESSION['_']['file'][\rtrim($trash, \D)] = 1;
        } else {
            \unlink($file);
        }
        $_['alert']['success'][$file] = [$trash ? 'File %s successfully moved to trash.' : 'File %s successfully deleted.', '<code>' . \x\panel\from\path($file) . '</code>'];
        $_['kick'] = $_POST['kick'] ?? \x\panel\to\link([
            'hash' => $_POST['hash'] ?? null,
            'part' => 1,
            'path' => \dirname($_['path']),
            'query' => \array_replace_recursive([
                'stack' => $_POST['stack'] ?? null,
                'tab' => $_POST['tab'] ?? null,
                'trash' => null,
                'type' => null
            ], $_POST['query'] ?? []),
            'task' => 'get'
        ]);
    }
    return $_;
}

function folder($_) {
    // Method not allowed!
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error'])) {
        return $_;
    }
    if (\is_dir($folder = $_['folder'])) {
        $trash = !empty($_POST['trash']) ? (new \Time($_POST['trash']))->name : false;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $k) {
            $v = $k->getPathname();
            if ($trash) {
                $vv = \strtr($v, [\LOT . \D => \LOT . \D . 'trash' . \D . $trash . \D]);
                if (!\is_dir($dd = \dirname($vv))) {
                    \mkdir($dd, 0775, true);
                }
                if (!\is_dir($vv) && !\is_file($vv)) {
                    \rename($v, $vv);
                }
                if ($k->isDir()) {
                    \rmdir($v);
                }
                $_SESSION['_'][$k->isDir() ? 'folder' : 'file'][\rtrim($vv, \D)] = 1;
            } else {
                if ($k->isDir()) {
                    \rmdir($v);
                } else {
                    \unlink($v);
                }
            }
        }
        if ($trash) {
            $_SESSION['_']['folder'][\rtrim(\strtr($folder, [\LOT . \D => \LOT . \D . 'trash' . \D . $trash . \D]), \D)] = 1;
        }
        \rmdir($folder);
        $_['alert']['success'][$folder] = [$trash ? 'Folder %s successfully moved to trash.' : 'Folder %s successfully deleted.', '<code>' . \x\panel\from\path($folder) . '</code>'];
        $_['kick'] = $_POST['kick'] ?? \x\panel\to\link([
            'hash' => $_POST['hash'] ?? null,
            'part' => 1,
            'path' => \dirname($_['path']),
            'query' => \array_replace_recursive([
                'stack' => $_POST['stack'] ?? null,
                'tab' => $_POST['tab'] ?? null,
                'trash' => null,
                'type' => null
            ], $_POST['query'] ?? []),
            'task' => 'get'
        ]);
    }
    return $_;
}