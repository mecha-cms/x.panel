<?php namespace _\lot\x\panel\task\g;

function blob($_) {
    // Blob is always new, so there is no such update event
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
            $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::/' . \dirname($_['path']) . '.' . \pathinfo($parent[0], \PATHINFO_EXTENSION) . $e;
        }
    }
    return $_;
}

function file($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'token' => false,
        'trash' => false
    ]) . $url->hash;
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        $name = \basename(\To::file(\lcfirst($_['form']['file']['name'] ?? ""))); // New file name
        $base = \basename($_['f']); // Old file name
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
        } else if (\stream_resolve_include_path($f = \dirname($_['f']) . \DS . $name) && $name !== $base) {
            $_['alert']['error'][] = ['File %s already exists.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
            $_['f'] = $f;
        } else {
            if (\array_key_exists('content', $_['form']['file'] ?? [])) {
                // Use `$_POST['file']['content']` instead of `$_['form']['file']['content']` just to be sure
                // that the value will not be evaluated by the `e` function, especially for JSON-like value(s)
                $_['form']['file']['content'] = $_POST['file']['content'] ?? "";
                \file_put_contents($f, $_['form']['file']['content']);
                if ($name !== $base) {
                    \unlink($_['f']);
                }
            } else if ($name !== $base) {
                \rename($_['f'], $f);
            }
            @\chmod($f, \octdec($_['form']['file']['seal'] ?? '0777'));
            $_['alert']['success'][] = ['File %s successfully updated.', '<code>' . \_\lot\x\panel\h\path($_['f']) . '</code>'];
            $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::/' . \dirname($_['path']) . '/' . $name . $e;
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
        $name = \basename($_['form']['folder']['name'] ?? ""); // New folder name
        $base = \basename($_['f']); // Old folder name
        if ("" === $name) {
            $_['alert']['error'][] = ['Please fill out the %s field.', 'Name'];
        } else if (\stream_resolve_include_path($f = \dirname($_['f']) . \DS . $name) && $name !== $base) {
            $_['alert']['error'][] = ['Folder %s already exists.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
        } else if ($name === $base) {
            // Do nothing
            $_['alert']['success'][] = ['Folder %s successfully updated.', '<code>' . \_\lot\x\panel\h\path($f = $_['f']) . '</code>'];
            if (!empty($_['form']['o']['kick'])) {
                $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::' . \strtr($f, [
                    \LOT => "",
                    \DS => '/'
                ]) . '/1' . $e;
            } else {
                $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::/' . \dirname($_['path']) . '/1' . $e;
            }
            $_SESSION['_']['folder'][\rtrim($f, \DS)] = 1;
        } else {
            \mkdir($f, $seal = \octdec($_['form']['folder']['seal'] ?? '0755'), true);
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($_['f'], \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $k) {
                $v = $k->getPathname();
                if ($k->isDir()) {
                    \rmdir($v);
                } else {
                    $vv = \strtr($v, [$_['f'] => $f]);
                    if (!\is_dir($d = \dirname($vv))) {
                        \mkdir($d, $seal, true);
                    }
                    \rename($v, $vv);
                }
            }
            \rmdir($_['f']);
            $_['alert']['success'][] = ['Folder %s successfully updated.', '<code>' . \_\lot\x\panel\h\path($_['f']) . '</code>'];
            if (!empty($_['form']['o']['kick'])) {
                $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::' . \strtr($f, [
                    \LOT => "",
                    \DS => '/'
                ]) . '/1' . $e;
            } else {
                $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::/' . \dirname($_['path']) . '/1' . $e;
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
        $_f = $_['f']; // Get old file name
        $_ = file($_); // Move to `file`
        if (empty($_['alert']['error'])) {
            if (!\is_dir($d = \Path::F($_['f']))) {
                \mkdir($d, 0755, true);
            }
            if ($_['f'] !== $_f && \is_dir($_d = \Path::F($_f))) {
                \rename($_d, $d);
            }
            if (isset($_['form']['data'])) {
                foreach ((array) $_['form']['data'] as $k => $v) {
                    if ("" !== \trim($v)) {
                        \file_put_contents($ff = $d . \DS . $k . '.data', \is_array($v) ? \json_encode($v) : \s($v));
                        @\chmod($ff, 0600);
                    } else {
                        \is_file($ff = $d . \DS . $k . '.data') && \unlink($ff);
                    }
                }
            }
        }
    }
    if (\is_file($f = $_['f'])) {
        $key = \ucfirst(\ltrim($_['chops'][0], '_.-'));
        $path = '<code>' . \_\lot\x\panel\h\path($_f ?? $f) . '</code>';
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
        'layout' => false,
        'token' => false,
        'trash' => false
    ]) . $url->hash;
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
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        if (\is_file($f = \LOT . \DS . \trim(\strtr($_['form']['path'] ?? $_['path'], '/', \DS), \DS))) {
            $_['f'] = $f = \realpath($f);
            $v = \array_replace_recursive((array) require $f, $_['form']['state'] ?? []);
            $v = $nully($v);
            $_['form']['file']['content'] = $_POST['file']['content'] = '<?php return ' . \z($v) . ';';
            $_ = file($_); // Move to `file`
        }
        $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::/' . $_['path'] . $e;
        if (!empty($_['alert']['error'])) {
            unset($_POST['token']);
            $_SESSION['form'] = $_POST;
        }
    }
    return $_;
}

function _token($_) {
    if (empty($_['form']['token']) || $_['form']['token'] !== $_['token']) {
        $_['alert']['error'][] = 'Invalid token.';
    }
    return $_;
}

foreach (['blob', 'data', 'file', 'folder', 'page', 'state'] as $v) {
    \Hook::set('do.' . $v . '.get', __NAMESPACE__ . "\\_token", 0);
    \Hook::set('do.' . $v . '.get', __NAMESPACE__ . "\\" . $v, 10);
}
