<?php namespace x\panel\task\set;

function blob($_) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    test($_POST);
    exit;
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
            $seal = 0775; // Invalid file permission, return default!
        }
        \mkdir($self, $seal, true);
        $_['alert']['success'][$self] = ['Folder %s successfully created.', '<code>' . \x\panel\from\path($self) . '</code>'];
        if (!empty($_POST['options']['kick'])) {
            $_['kick'] = $_POST['kick'] ?? \x\panel\to\link([
                'hash' => $_POST['hash'] ?? null,
                'part' => 1,
                'path' => \strtr($self, [
                    \LOT => "",
                    \D => '/'
                ]),
                'query' => \array_replace_recursive([
                    'stack' => $_POST['stack'] ?? null,
                    'tab' => $_POST['tab'] ?? null,
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