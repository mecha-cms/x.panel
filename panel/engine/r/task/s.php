<?php namespace _\lot\x\panel\task\s;

function blob($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'layout' => false,
        'tab'=> false,
        'token' => false,
        'trash' => false
    ]) . $url->hash;
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        $test_x = ',' . \implode(',', \array_keys(\array_filter(\File::$state['x'] ?? []))) . ',';
        $test_type = ',' . \implode(',', \array_keys(\array_filter(\File::$state['type'] ?? []))) . ',';
        $test_size = \File::$state['size'] ?? [0, 0];
        foreach ($_['form']['blob'] ?? [] as $k => $v) {
            // Check for error code
            if (!empty($v['error'])) {
                $_['alert']['error'][] = 'Failed to upload with error code: ' . $v['error'];
            } else {
                $name = \To::file(\lcfirst($v['name'])) ?? '0';
                $x = \pathinfo($name, \PATHINFO_EXTENSION);
                $type = $v['type'] ?? 'application/octet-stream';
                $size = $v['size'] ?? 0;
                // Check for file extension
                if ($x && false === \strpos($test_x, ',' . $x . ',')) {
                    $_['alert']['error'][] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
                // Check for file type
                } else if ($type && false === \strpos($test_type, ',' . $type . ',')) {
                    $_['alert']['error'][] = ['File type %s is not allowed.', '<code>' . $type . '</code>'];
                }
                // Check for file size
                if ($size < $test_size[0]) {
                    $_['alert']['error'][] = ['Minimum file size allowed to upload is %s.', '<code>' . \File::sizer($test_size) . '</code>'];
                } else if ($size > $test_size[1]) {
                    $_['alert']['error'][] = ['Maximum file size allowed to upload is %s.', '<code>' . \File::sizer($test_size) . '</code>'];
                }
            }
            if (!empty($_['alert']['error'])) {
                continue;
            } else {
                $folder = \LOT . \DS . \strtr(\trim($v['to'] ?? $_['path'], '/'), '/', \DS);
                if (\is_file($f = $folder . \DS . $name)) {
                    $_['alert']['error'][] = ['File %s already exists.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
                    continue;
                }
                if (!\is_dir($folder)) {
                    \mkdir($folder, \octdec($v['seal'] ?? '0775'), true);
                }
                if (\move_uploaded_file($v['tmp_name'], $f)) {
                    $_['alert']['success'][] = ['File %s successfully uploaded.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
                    $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::/' . $_['path'] . '/1' . $e;
                    $_['f'] = $f;
                    $_SESSION['_']['file'][\rtrim($f, \DS)] = 1;
                    $_['ff'][] = $f;
                    // Extract package
                    if (
                        !empty($_['form']['o']['extract']) &&
                        \extension_loaded('zip') &&
                        ('zip' === $x || 'application/zip' === $type)
                    ) {
                        $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::f::/de686795/' . \strtr($f, [
                            \LOT . \DS => "",
                            \DS => '/'
                        ]) . \To::query([
                            'kick' => \explode('?', \str_replace('::s::', '::g::', $url->current), 2)[0] . '/1',
                            'let' => !empty($_['form']['o']['let']) ? 1 : false,
                            'token' => $_['token']
                        ]);
                    }
                } else {
                    if (0 === \q(\g($folder))) {
                        \rmdir($folder);
                    }
                    $_['alert']['error'][] = 'Error.';
                    continue;
                }
            }
        }
        if (!empty($_['alert']['error'])) {
            unset($_POST['token']);
            $_SESSION['form'] = $_POST;
        }
    }
    return $_;
}

function data($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'layout' => false,
        'tab' => ['data'],
        'token' => false,
        'trash' => false
    ]) . $url->hash;
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        $name = \basename(\To::file(\lcfirst($_['form']['data']['name'] ?? "")));
        $_['form']['file']['name'] = "" !== $name ? $name . '.data' : "";
        // Use `$_POST['data']['content']` instead of `$_['form']['data']['content']` just to be sure
        // that the value will not be evaluated by the `e` function, especially for JSON-like value(s)
        $_['form']['file']['content'] = $_POST['data']['content'] ?? "";
        $_ = file($_); // Move to `file`
        if (empty($_['alert']['error']) && $parent = \glob(\dirname($_['f']) . '.{archive,draft,page}', \GLOB_BRACE | \GLOB_NOSORT)) {
            $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::/' . $_['path'] . '.' . \pathinfo($parent[0], \PATHINFO_EXTENSION) . $e;
        }
    }
    return $_;
}

function file($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'layout' => false,
        'token' => false,
        'trash' => false
    ]) . $url->hash;
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        $name = \basename(\To::file(\lcfirst($_['form']['file']['name'] ?? "")));
        $x = \pathinfo($name, \PATHINFO_EXTENSION);
        // Special case for PHP file(s)
        if ('php' === $x && isset($_['form']['file']['content'])) {
            // This should be enough to detect PHP syntax error before saving
            \token_get_all($_['form']['file']['content'], \TOKEN_PARSE);
        }
        if ("" === $name) {
            $_['alert']['error'][] = ['Please fill out the %s field.', 'Name'];
        } else if (false === \strpos(',' . \implode(',', \array_keys(\array_filter(\File::$state['x'] ?? $_['form']['x[]'] ?? []))) . ',', ',' . $x . ',')) {
            $_['alert']['error'][] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
        } else if (\stream_resolve_include_path($f = $_['f'] . \DS . $name)) {
            $_['alert']['error'][] = [(\is_dir($f) ? 'Folder' : 'File') . ' %s already exists.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
            $_['f'] = $f;
        } else {
            if (\array_key_exists('content', $_['form']['file'] ?? [])) {
                // Use `$_POST['file']['content']` instead of `$_['form']['file']['content']` just to be sure
                // that the value will not be evaluated by the `e` function, especially for JSON-like value(s)
                $_['form']['file']['content'] = $_POST['file']['content'] ?? "";
                \file_put_contents($f, $_['form']['file']['content']);
            }
            @\chmod($f, \octdec($_['form']['file']['seal'] ?? '0777'));
            $_['alert']['success'][] = ['File %s successfully created.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
            $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::/' . $_['path'] . '/1' . $e;
            $_['f'] = $f;
            $_SESSION['_']['file'][\rtrim($f, \DS)] = 1;
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
        'layout' => false,
        'token' => false,
        'trash' => false
    ]) . $url->hash;
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        $name = \To::folder($_['form']['folder']['name'] ?? "");
        if ("" === $name) {
            $_['alert']['error'][] = ['Please fill out the %s field.', 'Name'];
        } else if (\stream_resolve_include_path($f = $_['f'] . \DS . $name)) {
            $_['alert']['error'][] = [(\is_dir($f) ? 'Folder' : 'File') . ' %s already exists.', '<code>' . $f . '</code>'];
        } else {
            \mkdir($f, \octdec($_['form']['folder']['seal'] ?? '0755'), true);
            $_['alert']['success'][] = ['Folder %s successfully created.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
            if (!empty($_['form']['o']['kick'])) {
                $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::' . \strtr($f, [
                    \LOT => "",
                    \DS => '/'
                ]) . '/1' . $e;
            } else {
                $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::/' . $_['path'] . '/1' . $e;
            }
            $_['f'] = $f;
            foreach (\step(\rtrim($f, \DS), \DS) as $v) {
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
        'layout' => false,
        'token' => false,
        'trash' => false
    ]) . $url->hash;
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        $name = \To::kebab($_['form']['page']['name'] ?? $_['form']['page']['title'] ?? "");
        $x = $_['form']['page']['x'] ?? 'page';
        if ("" === $name) {
            $name = \date('Y-m-d-H-i-s');
        }
        unset($_['form']['page']['name'], $_['form']['page']['x']);
        $page = [];
        $p = (array) ($state->x->page->page ?? []);
        // Remove array item(s) with `null` value
        $nully = function($v) use(&$nully) {
            foreach ($v as $kk => $vv) {
                if (\is_array($vv) && !empty($vv)) {
                    if ($vv = $nully($vv)) {
                        $v[$kk] = $vv;
                    } else {
                        unset($v[$kk]);
                    }
                } else if ("" === $vv || null === $vv || [] === $vv) {
                    unset($v[$kk]);
                }
            }
            return [] !== $v ? $v : null;
        };
        foreach ($_['form']['page'] as $k => $v) {
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
                if ($v = $nully(\array_replace_recursive($page[$k] ?? [], $v))) {
                    $page[$k] = $v;
                }
            } else {
                $page[$k] = $v;
            }
        }
        $_['form']['file']['content'] = $_POST['file']['content'] = \To::page($page);
        $_['form']['file']['name'] = $name . '.' . $x;
        $_ = file($_); // Move to `file`
        if (empty($_['alert']['error'])) {
            if (!\is_dir($d = \Path::F($_['f']))) {
                \mkdir($d, 0755, true);
            }
            if (isset($_['form']['data'])) {
                foreach ((array) $_['form']['data'] as $k => $v) {
                    if ("" !== \trim($v)) {
                        \file_put_contents($ff = $d . \DS . $k . '.data', \is_array($v) ? \json_encode($v) : \s($v));
                        @\chmod($ff, 0600);
                    }
                }
            }
        }
    }
    if (\is_file($f = $_['f'])) {
        $key = \ucfirst(\ltrim($_['chops'][0], '_.-'));
        $path = '<code>' . \_\lot\x\panel\h\path($f) . '</code>';
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
    // State must exists, so there is no such create event, only update
    return $_;
}

function _token($_) {
    if (empty($_['form']['token']) || $_['form']['token'] !== $_['token']) {
        $_['alert']['error'][] = 'Invalid token.';
    }
    return $_;
}

foreach (['blob', 'data', 'file', 'folder', 'page', 'state'] as $v) {
    \Hook::set('do.' . $v . '.set', __NAMESPACE__ . "\\_token", 0);
    \Hook::set('do.' . $v . '.set', __NAMESPACE__ . "\\" . $v, 10);
}
