<?php namespace x\panel\task\get;

function blob($_) {
    // Blob is always new, so there is no such update event
    return $_;
}

function data($_) {
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
    $folder = isset($_POST['path']) && "" !== $_POST['path'] ? \LOT . \D . \trim(\strtr(\strip_tags((string) $_POST['path']), '/', \D), \D) : \dirname($_['file']);
    $content = $_POST['data']['content'] ?? "";
    $name = \basename((string) \To::file(\lcfirst($_POST['data']['name'] ?? ""), '#.@_~'));
    $x = \trim(\basename($_POST['data']['x'] ?? 'txt'), '.');
    // TODO: Validate content
    if ('json' === $x) {

    } else if ('yaml' === $x || 'yml' === $x) {

    }
    $_POST['file']['content'] = $content ?? "";
    $_POST['file']['name'] = "" !== $name ? $name . '.' . $x : "";
    $_ = file($_); // Move to `file`
    if (empty($_['alert']['error']) && $parent = \glob(\dirname($folder) . '.{' . \x\page\x() . '}', \GLOB_BRACE | \GLOB_NOSORT)) {
        $_['kick'] = $_POST['kick'] ?? [
            'hash' => $_POST['hash'] ?? null,
            'part' => 0,
            'path' => \strtr(\rawurlencode(\dirname($_['path'], 2) . '.' . \pathinfo($parent[0], \PATHINFO_EXTENSION)), ['%2F' => '/']),
            'query' => \x\panel\_query_set($_POST['query'] ?? []),
            'task' => 'get'
        ];
    }
    return $_;
}

function file($_) {
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
    $base = \basename((string) ($file = $_['file'])); // Old file name
    $name = \basename((string) \To::file(\lcfirst($_POST['file']['name'] ?? ""), '#.@_~'));
    $x = \pathinfo($name, \PATHINFO_EXTENSION);
    if ("" === $name) {
        $_['alert']['error'][$file] = ['Please fill out the %s field.', 'Name'];
    } else if (false === \strpos(',' . \implode(',', \array_keys(\array_filter((array) \State::get('x.panel.guard.file.x', true)))) . ',', ',' . $x . ',')) {
        $_['alert']['error'][$file] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
    } else if (\stream_resolve_include_path($self = \dirname($file) . \D . $name) && $name !== $base) {
        $_['alert']['error'][$self] = ['File %s already exists.', '<code>' . \x\panel\from\path($self) . '</code>'];
        $_['file'] = $self; // For hook(s)
    } else {
        if (\array_key_exists('content', $_POST['file'] ?? [])) {
            // Special case for PHP file(s)
            if ('php' === $x) {
                try {
                    \token_get_all($content = $_POST['file']['content'] ?? "", \TOKEN_PARSE);
                } catch (\Throwable $e) {
                    $_['alert']['error'][$self] = (string) $e;
                    unset($_POST['token']);
                    $_SESSION['form'] = $_POST;
                    return $_; // Skip!
                }
            }
            if (!\stream_resolve_include_path($self) || \is_writable($self)) {
                \file_put_contents($self, $_POST['file']['content']);
                if ($name !== $base) {
                    \unlink($file);
                }
            } else {
                $_['alert']['error'][$self] = ['File %s is not writable.', ['<code>' . \x\panel\from\path($self) . '</code>']];
            }
        } else if ($name !== $base) {
            \rename($file, $self);
        }
        $seal = \octdec($_POST['file']['seal'] ?? '0777');
        if ($seal < 0 || $seal > 0777) {
            $seal = 0777; // Invalid file permission, return default!
        }
        \chmod($self, $seal);
        $_['alert']['success'][$file] = ['File %s successfully ' . ($name !== $base ? 'rename' : 'update') . 'd.', '<code>' . \x\panel\from\path($file) . '</code>'];
        $_['kick'] = $_POST['kick'] ?? [
            'hash' => $_POST['hash'] ?? null,
            'part' => 0,
            'path' => \strtr(\rawurlencode(\dirname($_['path']) . '/' . $name), ['%2F' => '/']),
            'query' => \x\panel\_query_set($_POST['query'] ?? []),
            'task' => 'get'
        ];
        $_['file'] = $self; // For hook(s)
        $_SESSION['_']['files'][\rtrim($self, \D)] = 1;
    }
    if (!empty($_['alert']['error']) || $_['status'] >= 400) {
        unset($_POST['query'], $_POST['token']);
        $_SESSION['form'] = $_POST;
    }
    return $_;
}

function folder($_) {
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
    $base = \basename((string) $folder); // Old folder name
    $name = \basename((string) \To::folder($_POST['folder']['name'] ?? "", '.@_~')); // New folder name
    if ("" === $name) {
        $_['alert']['error'][$folder] = ['Please fill out the %s field.', 'Name'];
    } else if (\stream_resolve_include_path($self = \dirname($folder) . \D . $name) && $name !== $base) {
        $_['alert']['error'][$self] = [(\is_dir($self) ? 'Folder' : 'File') . ' %s already exists.', '<code>' . \x\panel\from\path($self) . '</code>'];
        $_[\is_dir($self) ? 'folder' : 'file'] = $self; // For hook(s)
    } else if ($name === $base) {
        // Do nothing
        $_['alert']['success'][$folder] = ['Folder %s successfully updated.', '<code>' . \x\panel\from\path($folder) . '</code>'];
        if (!empty($_POST['options']['kick'])) {
            $_['kick'] = $_POST['kick'] ?? [
                'hash' => $_POST['hash'] ?? null,
                'part' => 1,
                'path' => \strtr(\rawurlencode(\strtr($folder, [
                    \LOT . \D => "",
                    \D => '/'
                ])), ['%2F' => '/']),
                'query' => \x\panel\_query_set($_POST['query'] ?? []),
                'task' => 'get'
            ];
        } else {
            $_['kick'] = $_POST['kick'] ?? [
                'hash' => $_POST['hash'] ?? null,
                'part' => 1,
                'path' => \strtr(\rawurlencode(\dirname($_['path'])), ['%2F' => '/']),
                'query' => \x\panel\_query_set($_POST['query'] ?? []),
                'task' => 'get'
            ];
        }
        $_SESSION['_']['folders'][\rtrim($folder, \D)] = 1;
    } else {
        $seal = \octdec($_POST['folder']['seal'] ?? '0775');
        if ($seal < 0 || $seal > 0777) {
            $seal = 0775; // Invalid folder permission, return default!
        }
        \mkdir($self, $seal, true);
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $k) {
            $v = $k->getPathname();
            if ($k->isDir()) {
                \rmdir($v);
            } else {
                $vv = \strtr($v, [$folder => $self]);
                if (!\is_dir($d = \dirname($vv))) {
                    \mkdir($d, $seal, true);
                }
                \rename($v, $vv);
            }
        }
        \rmdir($folder);
        $_['alert']['success'][$folder] = ['Folder %s successfully updated.', '<code>' . \x\panel\from\path($folder) . '</code>'];
        if (!empty($_POST['options']['kick'])) {
            $_['kick'] = $_POST['kick'] ?? [
                'hash' => $_POST['hash'] ?? null,
                'part' => 1,
                'path' => \strtr(\rawurlencode(\strtr($self, [
                    \LOT . \D => "",
                    \D => '/'
                ])), ['%2F' => '/']),
                'query' => \x\panel\_query_set($_POST['query'] ?? []),
                'task' => 'get'
            ];
        } else {
            $_['kick'] = $_POST['kick'] ?? [
                'hash' => $_POST['hash'] ?? null,
                'part' => 1,
                'path' => \strtr(\rawurlencode(\dirname($_['path'])), ['%2F' => '/']),
                'query' => \x\panel\_query_set($_POST['query'] ?? []),
                'task' => 'get'
            ];
        }
        $_['folder'] = $self; // For hook(s)
        foreach (\step(\rtrim($self, \D), \D) as $v) {
            $_SESSION['_']['folders'][$v] = 1;
        }
    }
    if (!empty($_['alert']['error']) || $_['status'] >= 400) {
        unset($_POST['query'], $_POST['token']);
        $_SESSION['form'] = $_POST;
    }
    return $_;
}

function page($_) {
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
    $base = \basename((string) ($file = $_['file'])); // Old file name
    $name = (string) \To::kebab($_POST['page']['name'] ?? $_POST['page']['title'] ?? "");
    $name_prefix = \trim(\basename($_POST['page']['name-prefix'] ?? ""), '.');
    $x = $_POST['page']['x'] ?? \pathinfo($name, \PATHINFO_EXTENSION);
    if ("" === $name) {
        $name = \date('Y-m-d-H-i-s');
    }
    unset($_POST['page']['name'], $_POST['page']['name-prefix'], $_POST['page']['x']);
    $page = [];
    $p = (array) ($state->x->page->page ?? []);
    foreach ($_POST['page'] as $k => $v) {
        if (
            // Skip `null` value
            null === $v ||
            // Skip empty value
            \is_array($v) && !$v ||
            \is_string($v) && "" === \trim($v) ||
            // Skip default value
            isset($p[$k]) && $p[$k] === $v
        ) {
            continue;
        }
        if (\is_array($v)) {
            if ($v = \drop(\array_replace_recursive($page[$k] ?? [], $v))) {
                $page[$k] = $v;
            }
        } else {
            $page[$k] = $v;
        }
    }
    $_POST['file']['content'] = \function_exists($task = "\\x\\page\\to\\x\\" . $x) ? $task($page) : \To::page($page, 2);
    $_POST['file']['name'] = $name_prefix . $name . '.' . $x;
    $_ = file($_); // Move to `file`
    $self = $_['file']; // Get new file name
    if (empty($_['alert']['error'])) {
        if (!\is_dir($folder = \dirname($self) . \D . \pathinfo($self, \PATHINFO_FILENAME))) {
            \mkdir($folder, 0755, true);
        }
        if ($self !== $file && \is_dir($d = \dirname($file) . \D . \pathinfo($file, \PATHINFO_FILENAME))) {
            \rename($d, $folder);
        }
        if (isset($_POST['data'])) {
            if (!\is_dir($folder .= \D . '+')) {
                \mkdir($folder, 0755, true);
            }
            foreach ((array) $_POST['data'] as $k => $v) {
                $f = $folder . \D . $k . '.json';
                if ((\is_array($v) && $v = \drop($v)) || "" !== \trim((string) $v)) {
                    if (!\stream_resolve_include_path($f) || \is_writable($f)) {
                        \file_put_contents($f, \json_encode($v));
                        \chmod($f, 0600);
                    } else {
                        $_['alert']['error'][$f] = ['File %s is not writable.', '<code>' . \x\panel\from\path($f) . '</code>'];
                    }
                } else {
                    \is_file($f) && \unlink($f);
                }
            }
        }
    }
    if (\is_file($self = $_['file'])) {
        $id = \strtok($p = $_['path'] ?? "", '/');
        $key = \ucfirst(\ltrim($id, '_.-'));
        $path = '<code>' . \x\panel\from\path($file ?? $self) . '</code>';
        $alter = [
            'File %s already exists.' => ['%s %s already exists.', [$key, $path]],
            'File %s successfully renamed.' => ['%s %s successfully renamed.', [$key, $path]],
            'File %s successfully updated.' => ['%s %s successfully updated.', [$key, $path]]
        ];
        $x = \pathinfo($self, \PATHINFO_EXTENSION);
        if ('#' === $name_prefix) {
            $alter['File %s successfully renamed.'] = ['%s %s successfully converted to archive.', [$key, $path]];
        } else if ('~' === $name_prefix) {
            $alter['File %s successfully renamed.'] = ['%s %s successfully reverted to draft.', [$key, $path]];
        } else if ("" === $name_prefix) {
            $alter['File %s successfully renamed.'] = ['%s %s successfully published.', [$key, $path]];
        }
        foreach ($_['alert'] as $k => &$v) {
            foreach ($v as $kk => &$vv) {
                if (\is_string($kk) && \is_file($kk) && false === \strpos(',' . \x\page\x() . ',', ',' . \pathinfo($kk, \PATHINFO_EXTENSION) . ',')) {
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
    $folder = isset($_POST['path']) && "" !== $_POST['path'] ? \LOT . \D . \trim(\strtr(\strip_tags((string) $_POST['path']), '/', \D), \D) : \dirname($_['file']);
    if (\is_file($file = $folder . \D . \basename($_POST['file']['name'] ?? 'state.php'))) {
        foreach ($state = (array) ($_POST['state'] ?? []) as &$v) {
            if (\Is::JSON($v)) {
                $v = \json_decode($v, true);
            }
        }
        unset($v);
        $_POST['file']['content'] = '<?php return' . \z((array) \drop($state)) . ';';
        $_['file'] = \stream_resolve_include_path($file) ?: null; // For hook(s)
        $_ = file($_); // Move to `file`
    }
    $_['kick'] = $_POST['kick'] ?? [
        'hash' => $_POST['hash'] ?? null,
        'part' => $_['file'] ? 0 : 1,
        'query' => \x\panel\_query_set($_POST['query'] ?? []),
        'task' => 'get'
    ];
    if (!empty($_['alert']['error']) || $_['status'] >= 400) {
        unset($_POST['query'], $_POST['token']);
        $_SESSION['form'] = $_POST;
    }
    return $_;
}

return [];