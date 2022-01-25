<?php namespace x\panel\task\set;

function blob($_) {
    // Method not allowed!
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error'])) {
        return $_;
    }
    $test_size = (array) (\State::get('x.panel.guard.file.size', true) ?? [0, 0]);
    $test_type = \P . \implode(\P, \array_keys(\array_filter((array) (\State::get('x.panel.guard.file.type', true) ?? [])))) . \P;
    $test_x = \P . \implode(\P, \array_keys(\array_filter((array) (\State::get('x.panel.guard.file.x', true) ?? [])))) . \P;
    foreach ($_POST['blob'] ?? [] as $k => $v) {
        // Check for status code
        if (!empty($v['status'])) {
            $_['alert']['error'][] = 'Failed to upload with status code: ' . $v['status'];
        } else {
            $folder = \LOT . \D . \strtr(\trim($v['parent'] ?? $_['path'], '/'), '/', \D);
            $name = (string) (\To::file(\lcfirst($v['name'])) ?? \uniqid());
            $blob = $folder . \D . $name;
            $size = $v['size'] ?? 0;
            $type = $v['type'] ?? 'application/octet-stream';
            $x = \pathinfo($name, \PATHINFO_EXTENSION);
            // Check for file extension
            if ($x && false === \strpos($test_x, \P . $x . \P)) {
                $_['alert']['error'][$blob] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
            // Check for file type
            } else if ($type && false === \strpos($test_type, \P . $type . \P)) {
                $_['alert']['error'][$blob] = ['File type %s is not allowed.', '<code>' . $type . '</code>'];
            }
            // Check for file size
            if ($size < $test_size[0]) {
                $_['alert']['error'][$blob] = ['Minimum file size allowed to upload is %s.', '<code>' . \size($test_size) . '</code>'];
            } else if ($size > $test_size[1]) {
                $_['alert']['error'][$blob] = ['Maximum file size allowed to upload is %s.', '<code>' . \size($test_size) . '</code>'];
            }
            // Check for syntax error in PHP file
            if ('php' === $x) {
                // This should be enough to detect PHP syntax error before saving
                \token_get_all(\file_get_contents($v['blob']), \TOKEN_PARSE);
            }
        }
        if (!empty($_['alert']['error'])) {
            continue;
        }
        if (isset($blob) && \is_file($blob)) {
            $_['alert']['error'][$blob] = ['File %s already exists.', '<code>' . \x\panel\from\path($blob) . '</code>'];
            continue;
        }
        if (isset($folder) && !\is_dir($folder)) {
            \mkdir($folder, 0775, true);
        }
        if (\is_int($file = \store($v, $blob))) {
            if (0 === \q(\g($folder))) {
                \rmdir($folder);
            }
            $_['alert']['error'][] = 'Failed to upload with status code: ' . $file;
            continue;
        }
        $_['alert']['success'][$blob] = ['File %s successfully uploaded.', '<code>' . \x\panel\from\path($blob) . '</code>'];
        $_['kick'] = $_POST['kick'] ?? \x\panel\to\link([
            'hash' => $_POST['hash'] ?? null,
            'part' => 1,
            'query' => \array_replace_recursive([
                'stack' => $_POST['stack'] ?? null,
                'tab' => $_POST['tab'] ?? null,
                'trash' => null,
                'type' => null
            ], $_POST['query'] ?? []),
            'task' => 'get'
        ]);
        $_['file'] = $blob; // For hook(s)
        $_SESSION['_']['file'][\rtrim($blob, \D)] = 1;
        // Extract package
        if (!empty($_POST['options']['extract']) && \extension_loaded('zip') && ('zip' === $x || 'application/zip' === $type)) {
            $_['kick'] = $_POST['kick'] ?? \x\panel\to\link([
                'hash' => $_POST['hash'] ?? null,
                'part' => 0,
                'path' => strtr($blob, [
                    \LOT . \D => "",
                    \D => '/'
                ]),
                'query' => \array_replace_recursive([
                    'kick' => \x\panel\to\link([
                        'base' => null,
                        'part' => 0,
                        'query' => [
                            'kick' => null,
                            'let' => !empty($_POST['options']['let']) ? 1 : null
                        ],
                        'task' => 'get'
                    ]),
                    'stack' => $_POST['stack'] ?? null,
                    'tab' => $_POST['tab'] ?? null,
                    'token' => $_['token'],
                    'trash' => null,
                    'type' => null
                ], $_POST['query'] ?? []),
                'task' => 'fire/de686795'
            ]);
        }
    }
    if (!empty($_['alert']['error'])) {
        unset($_POST['token']);
        $_SESSION['form'] = $_POST;
    }
    return $_;
}

function data($_) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    test($_POST);
    exit;
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
    $folder = $_['folder'];
    $name = \basename(\To::file(\lcfirst($_POST['file']['name'] ?? "")) ?? "");
    $x = \pathinfo($name, \PATHINFO_EXTENSION);
    // Special case for PHP file(s)
    if ('php' === $x && isset($_POST['file']['content'])) {
        // This should be enough to detect PHP syntax error before saving
        \token_get_all($_POST['file']['content'], \TOKEN_PARSE);
    }
    if ("" === $name) {
        $_['alert']['error'][$folder] = ['Please fill out the %s field.', 'Name'];
    } else if (false === \strpos(',' . \implode(',', \array_keys(\array_filter((array) \State::get('x.panel.guard.file.x', true)))) . ',', ',' . $x . ',')) {
        $_['alert']['error'][$folder] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
    } else if (\stream_resolve_include_path($file = $folder . \D . $name)) {
        $_['alert']['error'][$file] = [(\is_dir($file) ? 'Folder' : 'File') . ' %s already exists.', '<code>' . \x\panel\from\path($file) . '</code>'];
        $_[\is_dir($file) ? 'folder' : 'file'] = $file; // For hook(s)
    } else {
        if (\array_key_exists('content', $_POST['file'] ?? [])) {
            if (\is_writable($folder = \dirname($file))) {
                \file_put_contents($file, $_POST['file']['content']);
            } else {
                $_['alert']['error'][$folder] = ['Folder %s is not writable.', ['<code>' . \x\panel\from\path($folder) . '</code>']];
            }
        }
        $seal = \octdec($_POST['file']['seal'] ?? '0777');
        if ($seal < 0 || $seal > 0777) {
            $seal = 0777; // Invalid file permission, return default!
        }
        \chmod($file, $seal);
        $_['alert']['success'][$file] = ['File %s successfully created.', '<code>' . \x\panel\from\path($file) . '</code>'];
        $_['kick'] = $_POST['kick'] ?? \x\panel\to\link([
            'hash' => $_POST['hash'] ?? null,
            'part' => 1,
            'query' => \array_replace_recursive([
                'stack' => $_POST['stack'] ?? null,
                'tab' => $_POST['tab'] ?? null,
                'trash' => null,
                'type' => null
            ], $_POST['query'] ?? []),
            'task' => 'get'
        ]);
        $_['file'] = $file; // For hook(s)
        $_SESSION['_']['file'][\rtrim($file, \D)] = 1;
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
    $name = (string) \To::folder($_POST['folder']['name'] ?? "");
    if ("" === $name) {
        $_['alert']['error'][$folder] = ['Please fill out the %s field.', 'Name'];
    } else if (\stream_resolve_include_path($self = $folder . \D . $name)) {
        $_['alert']['error'][$self] = [(\is_dir($self) ? 'Folder' : 'File') . ' %s already exists.', '<code>' . \x\panel\from\path($self) . '</code>'];
        $_['folder'] = $self; // For hook(s)
    } else {
        $seal = \octdec($_POST['folder']['seal'] ?? '0775');
        if ($seal < 0 || $seal > 0777) {
            $seal = 0775; // Invalid folder permission, return default!
        }
        \mkdir($self, $seal, true);
        $_['alert']['success'][$self] = ['Folder %s successfully created.', '<code>' . \x\panel\from\path($self) . '</code>'];
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