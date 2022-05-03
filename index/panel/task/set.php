<?php namespace x\panel\task\set;

function blob($_) {
    // Method not allowed!
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    // Invalid token?
    if ($_['token'] !== ($_POST['token'] ?? \P)) {
        $_['alert']['error'][] = 'Invalid token.';
    }
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error'])) {
        return $_;
    }
    $folder = isset($_POST['path']) && "" !== $_POST['path'] ? \LOT . \D . \trim(\strtr(\strip_tags((string) $_POST['path']), '/', \D), \D) : $_['folder'];
    $test_size = (array) (\State::get('x.panel.guard.file.size', true) ?? [0, 0]);
    $test_type = \P . \implode(\P, \array_keys(\array_filter((array) (\State::get('x.panel.guard.file.type', true) ?? [])))) . \P;
    $test_x = \P . \implode(\P, \array_keys(\array_filter((array) (\State::get('x.panel.guard.file.x', true) ?? [])))) . \P;
    foreach ($_POST['blobs'] ?? [] as $k => $v) {
        // Check for status code
        if (!empty($v['status'])) {
            $_['alert']['error'][] = 'Failed to upload with status code: ' . $v['status'];
        } else {
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
                try {
                    \token_get_all($content = \file_get_contents($v['blob']), \TOKEN_PARSE);
                } catch (\Throwable $e) {
                    $_['alert']['error'][$blob] = '<b>' . \get_class($e) . ':</b> ' . $e->getMessage() . ' at <code>#' . ($l = $e->getLine()) . '</code><br><code>' . \htmlspecialchars(\explode("\n", $content)[$l - 1] ?? "") . '</code>';
                }
            }
            $v['name'] = $name; // Safe file name!
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
        if (\is_int($file = \store($folder, $v))) {
            if (0 === \q(\g($folder))) {
                \rmdir($folder);
            }
            $_['alert']['error'][] = 'Failed to upload with status code: ' . $file;
            continue;
        }
        $_['alert']['success'][$blob] = ['File %s successfully uploaded.', '<code>' . \x\panel\from\path($blob) . '</code>'];
        $_['file'] = $blob; // For hook(s)
        $_SESSION['_']['file'][\rtrim($blob, \D)] = 1;
        // Perform package “extract”
        if (!empty($_POST['options']['extract']) && \extension_loaded('zip') && ('zip' === $x || 'application/zip' === $type)) {
            $zip = new \ZipArchive;
            if (true === $zip->open($blob)) {
                for ($i = 0; $i < $zip->numFiles; ++$i) {
                    $x = \pathinfo($v = \strtr($zip->getNameIndex($i), '/', \D), \PATHINFO_EXTENSION);
                    if (\D === \substr($v, -1)) {
                        continue; // Skip folder!
                    }
                    $v = $folder . \D . $v;
                    // This prevents user(s) from uploading forbidden file(s)
                    if ($x && false === \strpos($test_x, \P . $x . \P)) {
                        $_['alert']['error'][$v] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
                    // This prevents user(s) from accidentally overwrite the existing file(s)
                    } else if (\is_file($v)) {
                        $_['alert']['error'][$v] = ['File %s already exists.', '<code>' . \x\panel\from\path($v) . '</code>'];
                    // This prevents user(s) from uploading PHP file(s) with syntax error in it (if file with `.php` extension is allowed to upload)
                    } else if ('php' === $x && $content = $zip->getFromIndex($i)) {
                        try {
                            \token_get_all($content, \TOKEN_PARSE);
                        } catch (\Throwable $e) {
                            $_['alert']['error'][$v] = '<b>' . \get_class($e) . ':</b> ' . $e->getMessage() . ' at <code>' . \x\panel\from\path($v) . '#' . ($l = $e->getLine()) . '</code><br><code>' . \htmlspecialchars(\explode("\n", $content)[$l - 1] ?? "") . '</code>';
                        }
                    } else {
                        $_SESSION['_']['file'][$v] = 1;
                        $_SESSION['_']['folder'][\rtrim(\dirname($v), \D)] = 1;
                    }
                }
                if (!empty($_['alert']['error'])) {
                    $_['alert']['error'][$blob] = ['Package %s could not be extracted due to the previous errors.', '<code>' . \x\panel\from\path($blob) . '</code>'];
                } else {
                    $zip->extractTo($folder);
                    $_['alert']['success'][$blob] = ['Package %s successfully extracted.', '<code>' . \x\panel\from\path($blob) . '</code>'];
                    // Delete package after “extract”
                    if (!empty($_POST['options']['let'])) {
                        if (\unlink($blob)) {
                            $_['alert']['success'][$blob] = ['Package %s successfully extracted and deleted.', '<code>' . \x\panel\from\path($blob) . '</code>'];
                        } else {
                            $_['alert']['error'][$blob] = ['Package %s could not be deleted. Please delete it manually.', '<code>' . \x\panel\from\path($blob) . '</code>'];
                        }
                    }
                }
            }
        }
    }
    if (!empty($_['alert']['error'])) {
        unset($_POST['token']);
        $_SESSION['form'] = $_POST;
    } else {
        $_['kick'] = $_POST['kick'] ?? [
            'hash' => $_POST['hash'] ?? null,
            'part' => 1,
            'query' => \array_replace_recursive([
                'stack' => $_POST['stack'] ?? null,
                'tab' => $_POST['tab'] ?? null,
                'trash' => null,
                'type' => null
            ], $_POST['query'] ?? []),
            'task' => 'get'
        ];
    }
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
    if (isset($_['kick']) || !empty($_['alert']['error'])) {
        return $_;
    }
    $name = \basename(\To::file(\lcfirst($_POST['data']['name'] ?? "")));
    $_POST['file']['name'] = "" !== $name ? $name . '.data' : "";
    $_ = file($_); // Move to `file`
    if (empty($_['alert']['error']) && $parent = \glob(\dirname($_['file']) . '.{archive,draft,page}', \GLOB_BRACE | \GLOB_NOSORT)) {
        $_['kick'] = $_POST['kick'] ?? [
            'hash' => $_POST['hash'] ?? null,
            'part' => 0,
            'path' => $_['path'] . '.' . \pathinfo($parent[0], \PATHINFO_EXTENSION),
            'query' => \array_replace_recursive([
                'query' => null,
                'stack' => $_POST['stack'] ?? null,
                'tab' => $_POST['tab'] ?? null,
                'type' => null
            ], $_POST['query'] ?? []),
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
    if (isset($_['kick']) || !empty($_['alert']['error'])) {
        return $_;
    }
    $folder = isset($_POST['path']) && "" !== $_POST['path'] ? \LOT . \D . \trim(\strtr(\strip_tags((string) $_POST['path']), '/', \D), \D) : $_['folder'];
    $name = \basename(\To::file(\lcfirst($_POST['file']['name'] ?? "")) ?? "");
    $x = \pathinfo($name, \PATHINFO_EXTENSION);
    if ("" === $name) {
        $_['alert']['error'][$folder] = ['Please fill out the %s field.', 'Name'];
    } else if (false === \strpos(',' . \implode(',', \array_keys(\array_filter((array) \State::get('x.panel.guard.file.x', true)))) . ',', ',' . $x . ',')) {
        $_['alert']['error'][$folder] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
    } else if (\stream_resolve_include_path($file = $folder . \D . $name)) {
        $_['alert']['error'][$file] = [(\is_dir($file) ? 'Folder' : 'File') . ' %s already exists.', '<code>' . \x\panel\from\path($file) . '</code>'];
        $_[\is_dir($file) ? 'folder' : 'file'] = $file; // For hook(s)
    } else {
        if (\array_key_exists('content', $_POST['file'] ?? [])) {
            // Special case for PHP file(s)
            if ('php' === $x) {
                try {
                    \token_get_all($content = $_POST['file']['content'] ?? "", \TOKEN_PARSE);
                } catch (\Throwable $e) {
                    $_['alert']['error'][$file] = '<b>' . \get_class($e) . ':</b> ' . $e->getMessage() . ' at <code>#' . ($l = $e->getLine()) . '</code><br><code>' . \htmlspecialchars(\explode("\n", $content)[$l - 1] ?? "") . '</code>';
                    unset($_POST['token']);
                    $_SESSION['form'] = $_POST;
                    return $_; // Skip!
                }
            }
            if (!\is_dir($folder = \dirname($file))) {
                \mkdir($folder, 0775, true);
                foreach (\step(\rtrim($folder, \D), \D) as $v) {
                    $_SESSION['_']['folder'][$v] = 1;
                }
            }
            if (\is_writable($folder)) {
                \file_put_contents($file, $_POST['file']['content']);
            } else {
                $_['alert']['error'][$folder] = ['Folder %s is not writable.', '<code>' . \x\panel\from\path($folder) . '</code>'];
            }
        }
        $seal = \octdec($_POST['file']['seal'] ?? '0777');
        if ($seal < 0 || $seal > 0777) {
            $seal = 0777; // Invalid file permission, return default!
        }
        \chmod($file, $seal);
        $_['alert']['success'][$file] = ['File %s successfully created.', '<code>' . \x\panel\from\path($file) . '</code>'];
        $_['kick'] = $_POST['kick'] ?? [
            'hash' => $_POST['hash'] ?? null,
            'part' => 1,
            'query' => \array_replace_recursive([
                'stack' => $_POST['stack'] ?? null,
                'tab' => $_POST['tab'] ?? null,
                'trash' => null,
                'type' => null
            ], $_POST['query'] ?? []),
            'task' => 'get'
        ];
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
    // Invalid token?
    if ($_['token'] !== ($_POST['token'] ?? \P)) {
        $_['alert']['error'][] = 'Invalid token.';
    }
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error'])) {
        return $_;
    }
    $folder = isset($_POST['path']) && "" !== $_POST['path'] ? \LOT . \D . \trim(\strtr(\strip_tags((string) $_POST['path']), '/', \D), \D) : $_['folder'];
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
            $_['kick'] = $_POST['kick'] ?? [
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
            ];
        } else {
            $_['kick'] = $_POST['kick'] ?? [
                'part' => 1,
                'query' => \array_replace_recursive([
                    'stack' => $_POST['stack'] ?? null,
                    'tab' => $_POST['tab'] ?? null,
                    'trash' => null,
                    'type' => null
                ], $_POST['query'] ?? []),
                'task' => 'get'
            ];
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
    // Method not allowed!
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    // Invalid token?
    if ($_['token'] !== ($_POST['token'] ?? \P)) {
        $_['alert']['error'][] = 'Invalid token.';
    }
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error'])) {
        return $_;
    }
    $file = $_['file'];
    $name = (string) \To::kebab($_POST['page']['name'] ?? $_POST['page']['title'] ?? "");
    $x = $_POST['page']['x'] ?? 'page';
    if ("" === $name) {
        $name = \date('Y-m-d-H-i-s');
    }
    unset($_POST['page']['name'], $_POST['page']['x']);
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
    $_POST['file']['content'] = \To::page($page);
    $_POST['file']['name'] = $name . '.' . $x;
    $_ = file($_); // Move to `file`
    $self = $_['file']; // Get file name
    if (empty($_['alert']['error'])) {
        if (!\is_dir($folder = \dirname($self) . \D . \pathinfo($self, \PATHINFO_FILENAME))) {
            \mkdir($folder, 0755, true);
        }
        if (isset($_POST['data'])) {
            foreach ((array) $_POST['data'] as $k => $v) {
                $f = $folder . \D . $k . '.data';
                if ((\is_array($v) && $v = \drop($v)) || "" !== \trim((string) $v)) {
                    if (\is_writable($d = \dirname($f))) {
                        \file_put_contents($f, \is_array($v) ? \json_encode($v) : \s($v));
                        \chmod($f, 0600);
                    } else {
                        $_['alert']['error'][$d] = ['Folder %s is not writable.', ['<code>' . \x\panel\from\path($d) . '</code>']];
                    }
                }
            }
        }
    }
    if (\is_file($self = $_['file'])) {
        $id = \strtok($_['path'], '/');
        $key = \ucfirst(\ltrim($id, '_.-'));
        $path = '<code>' . \x\panel\from\path($self) . '</code>';
        $alter = [
            'File %s already exists.' => ['%s %s already exists.', [$key, $path]],
            'File %s successfully created.' => ['%s %s successfully created.', [$key, $path]]
        ];
        foreach ($_['alert'] as $k => &$v) {
            foreach ($v as $kk => &$vv) {
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
    // State must exists, so there is no such create event, only update!
    return $_;
}

return [];