<?php namespace x\panel\task\g;

function blob($_) {
    // Blob is always new, so there is no such update event
    return $_;
}

function data($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'q' => false,
        'tab' => ['data'],
        'token' => false,
        'trash' => false,
        'type' => false
    ]) . $url->hash;
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
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'q' => false,
        'tab' => $_['form']['lot']['tab'] ?? false,
        'token' => false,
        'trash' => false
    ]) . $url->hash;
    if ('post' === $_['form']['type']) {
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        $f = $_['f'];
        $name = \basename(\To::file(\lcfirst($_['form']['lot']['file']['name'] ?? ""))); // New file name
        $base = \basename($f); // Old file name
        $x = \pathinfo($name, \PATHINFO_EXTENSION);
        // Special case for PHP file(s)
        if ('php' === $x && isset($_['form']['lot']['file']['content'])) {
            // This should be enough to detect PHP syntax error before saving
            \token_get_all($_['form']['lot']['file']['content'], \TOKEN_PARSE);
        }
        if ("" === $name) {
            $_['alert']['error'][$f] = ['Please fill out the %s field.', 'Name'];
        } else if (false === \strpos(',' . \implode(',', \array_keys(\array_filter(\File::$state['x'] ?? $_['form']['lot']['x[]'] ?? []))) . ',', ',' . $x . ',')) {
            $_['alert']['error'][$f] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
        } else if (\stream_resolve_include_path($ff = \dirname($f) . \DS . $name) && $name !== $base) {
            $_['alert']['error'][$ff] = ['File %s already exists.', '<code>' . \x\panel\from\path($ff) . '</code>'];
            $_['f'] = $ff; // For hook(s)
        } else {
            if (\array_key_exists('content', $_['form']['lot']['file'] ?? [])) {
                // Use `$_POST['file']['content']` instead of `$_['form']['lot']['file']['content']` just to be sure
                // that the value will not be evaluated by the `e` function, especially for JSON-like value(s)
                $_['form']['lot']['file']['content'] = $_POST['file']['content'] ?? "";
                if (!\stream_resolve_include_path($ff) || \is_writable($ff)) {
                    \file_put_contents($ff, $_['form']['lot']['file']['content']);
                    if ($name !== $base) {
                        \unlink($f);
                    }
                } else {
                    $_['alert']['error'][$ff] = ['File %s is not writable.', ['<code>' . \x\panel\from\path($ff) . '</code>']];
                }
            } else if ($name !== $base) {
                \rename($f, $ff);
            }
            $seal = \octdec($_['form']['lot']['file']['seal'] ?? '0777');
            if ($seal < 0 || $seal > 0777) {
                $seal = 0777; // Invalid file permission, return default!
            }
            \chmod($ff, $seal);
            $_['alert']['success'][$f] = ['File %s successfully ' . ($name !== $base ? 'rename' : 'update') . 'd.', '<code>' . \x\panel\from\path($f) . '</code>'];
            $_['kick'] = $_['form']['lot']['kick'] ?? $_['/'] . '/::g::/' . \dirname($_['path']) . '/' . $name . $e;
            $_['f'] = $ff; // For hook(s)
            $_SESSION['_']['file'][\rtrim($ff, \DS)] = 1;
        }
        if (!empty($_['alert']['error'])) {
            unset($_POST['token']);
            $_SESSION['form'] = $_POST;
        }
    }
    return $_;
}

function folder($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'q' => false,
        'tab' => $_['form']['lot']['tab'] ?? false,
        'token' => false,
        'trash' => false,
        'type' => false
    ]) . $url->hash;
    if ('post' === $_['form']['type']) {
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        $f = $_['f'];
        $name = \basename($_['form']['lot']['folder']['name'] ?? ""); // New folder name
        $base = \basename($_['f']); // Old folder name
        if ("" === $name) {
            $_['alert']['error'][$f] = ['Please fill out the %s field.', 'Name'];
        } else if (\stream_resolve_include_path($ff = \dirname($f) . \DS . $name) && $name !== $base) {
            $_['alert']['error'][$ff] = [(\is_dir($ff) ? 'Folder' : 'File') . ' %s already exists.', '<code>' . \x\panel\from\path($ff) . '</code>'];
            $_['f'] = $ff; // For hook(s)
        } else if ($name === $base) {
            // Do nothing
            $_['alert']['success'][$f] = ['Folder %s successfully updated.', '<code>' . \x\panel\from\path($f) . '</code>'];
            if (!empty($_['form']['lot']['options']['kick'])) {
                $_['kick'] = $_['form']['lot']['kick'] ?? $_['/'] . '/::g::' . \strtr($f, [
                    \LOT => "",
                    \DS => '/'
                ]) . '/1' . $e;
            } else {
                $_['kick'] = $_['form']['lot']['kick'] ?? $_['/'] . '/::g::/' . \dirname($_['path']) . '/1' . $e;
            }
            $_SESSION['_']['folder'][\rtrim($f, \DS)] = 1;
        } else {
            $seal = \octdec($_['form']['lot']['folder']['seal'] ?? '0775');
            if ($seal < 0 || $seal > 0777) {
                $seal = 0775; // Invalid file permission, return default!
            }
            \mkdir($ff, $seal, true);
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($f, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $k) {
                $v = $k->getPathname();
                if ($k->isDir()) {
                    \rmdir($v);
                } else {
                    $vv = \strtr($v, [$f => $ff]);
                    if (!\is_dir($d = \dirname($vv))) {
                        \mkdir($d, $seal, true);
                    }
                    \rename($v, $vv);
                }
            }
            \rmdir($f);
            $_['alert']['success'][$f] = ['Folder %s successfully updated.', '<code>' . \x\panel\from\path($f) . '</code>'];
            if (!empty($_['form']['lot']['options']['kick'])) {
                $_['kick'] = $_['form']['lot']['kick'] ?? $_['/'] . '/::g::' . \strtr($ff, [
                    \LOT => "",
                    \DS => '/'
                ]) . '/1' . $e;
            } else {
                $_['kick'] = $_['form']['lot']['kick'] ?? $_['/'] . '/::g::/' . \dirname($_['path']) . '/1' . $e;
            }
            $_['f'] = $ff; // For hook(s)
            foreach (\step(\rtrim($ff, \DS), \DS) as $v) {
                $_SESSION['_']['folder'][$v] = 1;
            }
        }
        if (!empty($_['alert']['error'])) {
            unset($_POST['token']);
            $_SESSION['form'] = $_POST;
        }
    }
    return $_;
}

function page($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'q' => false,
        'tab' => $_['form']['lot']['tab'] ?? false,
        'token' => false,
        'trash' => false,
        'type' => false
    ]) . $url->hash;
    if ('post' === $_['form']['type']) {
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        $f = $_['f'];
        $name = (string) \To::kebab($_['form']['lot']['page']['name'] ?? $_['form']['lot']['page']['title'] ?? "");
        $x = $_['form']['lot']['page']['x'] ?? 'page';
        if ("" === $name) {
            $name = \date('Y-m-d-H-i-s');
        }
        unset($_['form']['lot']['page']['name'], $_['form']['lot']['page']['x']);
        $page = [];
        $p = (array) ($state->x->page->page ?? []);
        foreach ($_['form']['lot']['page'] as $k => $v) {
            if (
                // Skip `null` value
                null === $v ||
                // Skip empty value
                \is_array($v) && 0 === \count($v) ||
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
        $_['form']['lot']['file']['content'] = $_POST['file']['content'] = \To::page($page);
        $_['form']['lot']['file']['name'] = $name . '.' . $x;
        $_ = file($_); // Move to `file`
        $ff = $_['f']; // Get new file name
        if (empty($_['alert']['error'])) {
            if (!\is_dir($dd = \Path::F($ff))) {
                \mkdir($dd, 0755, true);
            }
            if ($ff !== $f && \is_dir($d = \Path::F($f))) {
                \rename($d, $dd);
            }
            if (isset($_['form']['lot']['data'])) {
                foreach ((array) $_['form']['lot']['data'] as $k => $v) {
                    $fff = $dd . \DS . $k . '.data';
                    if ((\is_array($v) && $v = \drop($v)) || "" !== \trim($v)) {
                        if (!\stream_resolve_include_path($fff) || \is_writable($fff)) {
                            \file_put_contents($fff, \is_array($v) ? \json_encode($v) : \s($v));
                            \chmod($fff, 0600);
                        } else {
                            $_['alert']['error'][$fff] = ['File %s is not writable.', ['<code>' . \x\panel\from\path($fff) . '</code>']];
                        }
                    } else {
                        \is_file($fff) && \unlink($fff);
                    }
                }
            }
        }
    }
    if (\is_file($ff = $_['f'])) {
        $key = \ucfirst(\ltrim($_['id'], '_.-'));
        $path = '<code>' . \x\panel\from\path($f ?? $ff) . '</code>';
        $alter = [
            'File %s already exists.' => ['%s %s already exists.', [$key, $path]],
            'File %s successfully updated.' => ['%s %s successfully updated.', [$key, $path]]
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
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'q' => false,
        'tab' => $_['form']['lot']['tab'] ?? false,
        'token' => false,
        'trash' => false,
        'type' => false
    ]) . $url->hash;
    if ('post' === $_['form']['type']) {
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        if (\is_file($f = \LOT . \DS . \trim(\strtr($_['form']['lot']['path'] ?? $_['path'], '/', \DS), \DS))) {
            $_['f'] = $f = \realpath($f); // For hook(s)
            $v = \drop($_['form']['lot']['state'] ?? []);
            $_['form']['lot']['file']['content'] = $_POST['file']['content'] = '<?php return ' . \z($v) . ';';
            $_ = file($_); // Move to `file`
        }
        $_['kick'] = $_['form']['lot']['kick'] ?? $_['/'] . '/::g::/' . $_['path'] . $e;
        if (!empty($_['alert']['error'])) {
            unset($_POST['token']);
            $_SESSION['form'] = $_POST;
        }
    }
    return $_;
}

foreach (['blob', 'data', 'file', 'folder', 'page', 'state'] as $v) {
    \Hook::set('do.' . $v . '.get', __NAMESPACE__ . "\\" . $v, 10);
}
