<?php namespace x\panel\task\let;

// Prevent user(s) from deleting file(s)/folder(s) above the `.\lot\*` level
if ('POST' === $_SERVER['REQUEST_METHOD'] && false === \strpos(\strtr($source = $_['file'] ?? $_['folder'], [\LOT . \D => ""]), \D)) {
    \abort('Could not delete <code>' . \x\panel\from\path($source) . '</code> because it is above the <code>' . \x\panel\from\path(\LOT) . '</code> directory level.');
}

function data($_) {
    // Allow to use `GET` request
    if ('GET' === $_SERVER['REQUEST_METHOD']) {
        // Fake `POST` request
        $_SERVER['REQUEST_METHOD'] = 'POST';
        foreach (['hash', 'kick', 'path', 'query', 'stack', 'tab', 'token', 'trash'] as $k) {
            if (isset($_GET[$k])) {
                $_POST[$k] = $_GET[$k];
            }
        }
    }
    // Method not allowed!
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    // Invalid token?
    if ($_['token'] !== ($_POST['token'] ?? \P)) {
        $_['alert']['error'][] = 'Invalid token.';
    }
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error']) || $_['status'] >= 400) {
        return $_;
    }
    $_ = file($_); // Move to `file`
    if (empty($_['alert']['error']) && $parent = \glob(\dirname($_['file']) . '.{archive,draft,page}', \GLOB_BRACE | \GLOB_NOSORT)) {
        $_['kick'] = $_POST['kick'] ?? [
            'hash' => $_POST['hash'] ?? null,
            'part' => 0,
            'path' => \dirname($_['path']) . '.' . \pathinfo($parent[0], \PATHINFO_EXTENSION),
            'query' => \x\panel\_query_set(\array_replace_recursive($_POST['query'] ?? [], [
                'stack' => $_POST['stack'] ?? [],
                'tab' => $_POST['tab'] ?? [],
                'trash' => null,
                'type' => null
            ])),
            'task' => 'get'
        ];
    }
    return $_;
}

function file($_) {
    // Allow to use `GET` request
    if ('GET' === $_SERVER['REQUEST_METHOD']) {
        // Fake `POST` request
        $_SERVER['REQUEST_METHOD'] = 'POST';
        foreach (['hash', 'kick', 'path', 'query', 'stack', 'tab', 'token', 'trash'] as $k) {
            if (isset($_GET[$k])) {
                $_POST[$k] = $_GET[$k];
            }
        }
    }
    // Method not allowed!
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    // Invalid token?
    if ($_['token'] !== ($_POST['token'] ?? \P)) {
        $_['alert']['error'][] = 'Invalid token.';
    }
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error']) || $_['status'] >= 400) {
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
            $_SESSION['_']['files'][\rtrim($trash, \D)] = 1;
        } else {
            \unlink($file);
        }
        $_['alert']['success'][$file] = [$trash ? 'File %s successfully moved to trash.' : 'File %s successfully deleted.', '<code>' . \x\panel\from\path($file) . '</code>'];
        $_['kick'] = $_POST['kick'] ?? [
            'hash' => $_POST['hash'] ?? null,
            'part' => 1,
            'path' => \dirname($_['path']),
            'query' => \x\panel\_query_set(\array_replace_recursive($_POST['query'] ?? [], [
                'stack' => $_POST['stack'] ?? [],
                'tab' => $_POST['tab'] ?? [],
                'trash' => null,
                'type' => null
            ])),
            'task' => 'get'
        ];
    }
    return $_;
}

function folder($_) {
    // Allow to use `GET` request
    if ('GET' === $_SERVER['REQUEST_METHOD']) {
        // Fake `POST` request
        $_SERVER['REQUEST_METHOD'] = 'POST';
        foreach (['hash', 'kick', 'path', 'query', 'stack', 'tab', 'token', 'trash'] as $k) {
            if (isset($_GET[$k])) {
                $_POST[$k] = $_GET[$k];
            }
        }
    }
    // Method not allowed!
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    // Invalid token?
    if ($_['token'] !== ($_POST['token'] ?? \P)) {
        $_['alert']['error'][] = 'Invalid token.';
    }
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error']) || $_['status'] >= 400) {
        return $_;
    }
    $folder = isset($_POST['path']) && "" !== $_POST['path'] ? \LOT . \D . \trim(\strtr(\strip_tags((string) $_POST['path']), '/', \D), \D) : $_['folder'];
    if (\is_dir($folder)) {
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
                $_SESSION['_'][($k->isDir() ? 'folder' : 'file') . 's'][\rtrim($vv, \D)] = 1;
            } else {
                if ($k->isDir()) {
                    \rmdir($v);
                } else {
                    \unlink($v);
                }
            }
        }
        if ($trash) {
            $_SESSION['_']['folders'][\rtrim(\strtr($folder, [\LOT . \D => \LOT . \D . 'trash' . \D . $trash . \D]), \D)] = 1;
        }
        \rmdir($folder);
        $_['alert']['success'][$folder] = [$trash ? 'Folder %s successfully moved to trash.' : 'Folder %s successfully deleted.', '<code>' . \x\panel\from\path($folder) . '</code>'];
        $_['kick'] = $_POST['kick'] ?? [
            'hash' => $_POST['hash'] ?? null,
            'part' => 1,
            'path' => \dirname($_['path']),
            'query' => \x\panel\_query_set(\array_replace_recursive($_POST['query'] ?? [], [
                'stack' => $_POST['stack'] ?? [],
                'tab' => $_POST['tab'] ?? [],
                'trash' => null,
                'type' => null
            ])),
            'task' => 'get'
        ];
    }
    return $_;
}

function page($_) {
    // Allow to use `GET` request
    if ('GET' === $_SERVER['REQUEST_METHOD']) {
        // Fake `POST` request
        $_SERVER['REQUEST_METHOD'] = 'POST';
        foreach (['hash', 'kick', 'path', 'query', 'stack', 'tab', 'token', 'trash'] as $k) {
            if (isset($_GET[$k])) {
                $_POST[$k] = $_GET[$k];
            }
        }
    }
    // Method not allowed!
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    // Invalid token?
    if ($_['token'] !== ($_POST['token'] ?? \P)) {
        $_['alert']['error'][] = 'Invalid token.';
    }
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error']) || $_['status'] >= 400) {
        return $_;
    }
    if (\is_file($file = $_['file'])) {
        if (\is_dir($folder = \dirname($file) . \D . \pathinfo($file, \PATHINFO_FILENAME))) {
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
                    $_SESSION['_'][($k->isDir() ? 'folder' : 'file') . 's'][\rtrim($vv, \D)] = 1;
                } else {
                    if ($k->isDir()) {
                        \rmdir($v);
                    } else {
                        \unlink($v);
                    }
                }
            }
            \rmdir($folder);
        }
        $key = \ucfirst(\ltrim(\strtok($_['path'], '/'), '_.-'));
        $path = '<code>' . \x\panel\from\path($file) . '</code>';
        $_ = file($_); // Move to `file`
        $alter = [
            'File %s successfully deleted.' => ['%s %s successfully deleted.', [$key, $path]],
            'File %s successfully moved to trash.' => ['%s %s successfully moved to trash.', [$key, $path]]
        ];
        foreach ($_['alert'] as $k => &$v) {
            foreach ($v as $kk => &$vv) {
                if (\is_string($kk) && \is_file($kk) && false === \strpos(',archive,draft,page,', ',' . \pathinfo($kk, \PATHINFO_EXTENSION) . ',')) {
                    continue;
                }
                if (\is_array($vv)) {
                    if (isset($alter[$vv[0]])) {
                        $vv = \array_replace($vv, $alter[$vv[0]]);
                    }
                } else if (\is_string($vv)) {
                    $vv = $alter[$vv] ?? $vv;
                }
            }
        }
    }
    return $_;
}

function state($_) {
    // There is no such delete event for state(s)
    return $_;
}

return [];