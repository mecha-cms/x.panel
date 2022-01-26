<?php namespace x\panel\task\get;

function blob($_) {
    // Blob is always new, so there is no such update event
    return $_;
}

function data($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $hash = $_['form']['lot']['hash'] ?? "";
    $e = \To::query(\array_replace([
        'stack' => $_['form']['lot']['stack'] ?? [],
        'tab' => $_['form']['lot']['tab'] ?? ['data']
    ], $_['form']['lot']['query'] ?? [])) . ("" !== $hash ? '#' . $hash : "");
    if ('post' === $_['form']['type']) {
        $name = \basename(\To::file(\lcfirst($_['form']['lot']['data']['name'] ?? "")));
        $_['form']['lot']['file']['name'] = "" !== $name ? $name . '.data' : "";
        $_ = file($_); // Move to `file`
        if (empty($_['alert']['error']) && $parent = \glob(\dirname($_['f']) . '.{archive,draft,page}', \GLOB_BRACE | \GLOB_NOSORT)) {
            $_['kick'] = $_['form']['lot']['kick'] ?? $_['/'] . '/::g::/' . \dirname($_['path']) . '.' . \pathinfo($parent[0], \PATHINFO_EXTENSION) . $e;
        }
    }
    return $_;
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
    $file = $_['file'];
    $name = \basename(\To::file(\lcfirst($_POST['file']['name'] ?? "")) ?? "");
    $base = \basename($file); // Old file name
    $x = \pathinfo($name, \PATHINFO_EXTENSION);
    // Special case for PHP file(s)
    if ('php' === $x && isset($_POST['file']['content'])) {
        // This should be enough to detect PHP syntax error before saving
        \token_get_all($_POST['file']['content'], \TOKEN_PARSE);
    }
    if ("" === $name) {
        $_['alert']['error'][$file] = ['Please fill out the %s field.', 'Name'];
    } else if (false === \strpos(',' . \implode(',', \array_keys(\array_filter((array) \State::get('x.panel.guard.file.x', true)))) . ',', ',' . $x . ',')) {
        $_['alert']['error'][$file] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
    } else if (\stream_resolve_include_path($self = \dirname($file) . \D . $name) && $name !== $base) {
        $_['alert']['error'][$self] = ['File %s already exists.', '<code>' . \x\panel\from\path($self) . '</code>'];
        $_['file'] = $self; // For hook(s)
    } else {
        if (\array_key_exists('content', $_POST['file'] ?? [])) {
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
        $_['kick'] = $_['form']['lot']['kick'] ?? $_['/'] . '/::g::/' . \dirname($_['path']) . '/' . $name . $e;
        $_['kick'] = $_POST['kick'] ?? \x\panel\to\link([
            'hash' => $_POST['hash'] ?? null,
            'part' => 0,
            'path' => \dirname($_['path']) . '/' . $name,
            'query' => \array_replace_recursive([
                'stack' => $_POST['stack'] ?? null,
                'tab' => $_POST['tab'] ?? null,
                'trash' => null,
                'type' => null
            ], $_POST['query'] ?? []),
            'task' => 'get'
        ]);
        $_['file'] = $self; // For hook(s)
        $_SESSION['_']['file'][\rtrim($self, \D)] = 1;
    }
    if (!empty($_['alert']['error'])) {
        unset($_POST['token']);
        $_SESSION['form'] = $_POST;
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
    $folder = $_['folder'];
    $name = (string) \To::folder(\basename($_POST['folder']['name'] ?? "")); // New folder name
    $base = \basename($folder); // Old folder name
    if ("" === $name) {
        $_['alert']['error'][$folder] = ['Please fill out the %s field.', 'Name'];
    } else if (\stream_resolve_include_path($self = \dirname($folder) . \D . $name) && $name !== $base) {
        $_['alert']['error'][$self] = [(\is_dir($self) ? 'Folder' : 'File') . ' %s already exists.', '<code>' . \x\panel\from\path($self) . '</code>'];
        $_[\is_dir($self) ? 'folder' : 'file'] = $self; // For hook(s)
    } else if ($name === $base) {
        // Do nothing
        $_['alert']['success'][$folder] = ['Folder %s successfully updated.', '<code>' . \x\panel\from\path($folder) . '</code>'];
        if (!empty($_POST['options']['kick'])) {
            $_['kick'] = $_POST['kick'] ?? \x\panel\to\link([
                'hash' => $_POST['hash'] ?? null,
                'part' => 1,
                'path' => \strtr($folder, [
                    \LOT . \D => "",
                    \D => '/'
                ]),
                'query' => \array_replace_recursive([
                    'stack' => $_POST['stack'] ?? null,
                    'tab' => $_POST['tab'] ?? null,
                    'trash' => null,
                    'type' => null
                ], $_POST['query'] ?? []),
                'task' => 'get'
            ]);
        } else {
            $_['kick'] = $_POST['kick'] ?? \x\panel\to\link([
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
        $_SESSION['_']['folder'][\rtrim($folder, \D)] = 1;
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
            $_['kick'] = $_POST['kick'] ?? \x\panel\to\link([
                'hash' => $_POST['hash'] ?? null,
                'part' => 1,
                'path' => \strtr($self, [
                    \LOT . \D => "",
                    \D => '/'
                ]),
                'query' => \array_replace_recursive([
                    'stack' => $_POST['stack'] ?? null,
                    'tab' => $_POST['tab'] ?? null,
                    'trash' => null,
                    'type' => null
                ], $_POST['query'] ?? []),
                'task' => 'get'
            ]);
        } else {
            $_['kick'] = $_POST['kick'] ?? \x\panel\to\link([
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
        $_['folder'] = $self; // For hook(s)
        foreach (\step(\rtrim($self, \D), \D) as $v) {
            $_SESSION['_']['folder'][$v] = 1;
        }
    }
    if (!empty($_['alert']['error'])) {
        unset($_POST['token']);
        $_SESSION['form'] = $_POST;
    }
    return $_;
}

function page($_) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    test($_POST);
    exit;
}

function state($_) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    test($_POST);
    exit;
}