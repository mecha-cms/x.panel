<?php namespace _\lot\x\panel\task\get;

function blob($_, $lot) {
    // Blob is always new, so there is no such update event
    return $_;
}

function data($_, $lot) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'tab' => ['data'],
        'token' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = \basename(\To::file(\lcfirst($lot['data']['name'] ?? "")));
        $content = $lot['data']['content'] ?? "";
        $lot['file']['name'] = $name !== "" ? $name . '.data' : "";
        $lot['file']['content'] = \is_array($content) ? \json_encode($content) : \s($content);
        $_ = file($_, $lot); // Move to `file`
        if (empty($_['alert']['error']) && $parent = \glob(\dirname($_['f']) . '.{archive,draft,page}', \GLOB_BRACE | \GLOB_NOSORT)) {
            $_['kick'] = $url . $_['/'] . '::g::' . \dirname($_['path']) . '.' . \pathinfo($parent[0], \PATHINFO_EXTENSION) . $e;
        }
    }
    return $_;
}

function file($_, $lot) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'tab' => false,
        'token' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Abort by previous hook’s return value if any
        if (!empty($_['alert']['error'])) {
            return $_;
        }
        $name = \basename(\To::file(\lcfirst($lot['file']['name'] ?? ""))); // New file name
        $base = \basename($_['f']); // Old file name
        $x = \pathinfo($name, \PATHINFO_EXTENSION);
        // Special case for PHP file(s)
        if ($x === 'php' && isset($lot['file']['content'])) {
            // This must be enough to detect PHP syntax before saving
            \token_get_all($lot['file']['content'], \TOKEN_PARSE);
        }
        if ($name === "") {
            $_['alert']['error'][] = ['Please fill out the %s field.', 'Name'];
        } else if (\strpos(',' . \implode(',', \array_keys(\array_filter(\File::$state['x'] ?? $lot['x[]'] ?? []))) . ',', ',' . $x . ',') === false) {
            $_['alert']['error'][] = ['Extension %s is not allowed.', '<code>' . $x . '</code>'];
        } else if (\stream_resolve_include_path($f = \dirname($_['f']) . \DS . $name) && $name !== $base) {
            $_['alert']['error'][] = ['File %s already exists.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
        } else {
            if (isset($lot['file']['content'])) {
                \file_put_contents($f, $lot['file']['content']);
                if ($name !== $base) {
                    \unlink($_['f']);
                }
            } else if ($name !== $base) {
                \rename($_['f'], $f);
            }
            \chmod($f, \octdec($lot['file']['seal'] ?? '0777'));
            $_['alert']['success'][] = ['File %s successfully updated.', '<code>' . \_\lot\x\panel\h\path($_['f']) . '</code>'];
            $_['kick'] = $url . $_['/'] . '::g::' . \dirname($_['path']) . '/' . $name . $e;
            $_SESSION['_']['file'][$_['f'] = $f] = 1;
        }
    }
    if (!empty($_['alert']['error'])) {
        unset($lot['token']);
        $_SESSION['form'] = $lot;
    }
    return $_;
}

function folder($_, $lot) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'tab'=> false,
        'token' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Abort by previous hook’s return value if any
        if (!empty($_['alert']['error'])) {
            return $_;
        }
        $name = \To::folder($lot['folder']['name'] ?? ""); // New folder name
        $base = \basename($_['f']); // Old folder name
        if ($name === "") {
            $_['alert']['error'][] = ['Please fill out the %s field.', 'Name'];
        } else if (\stream_resolve_include_path($f = \dirname($_['f']) . \DS . $name) && $name !== $base) {
            $_['alert']['error'][] = ['Folder %s already exists.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
        } else if ($name === $base) {
            // Do nothing
            $_['alert']['success'][] = ['Folder %s successfully updated.', '<code>' . \_\lot\x\panel\h\path($f = $_['f']) . '</code>'];
            if (!empty($lot['folder']['kick'])) {
                $_['kick'] = $url . $_['/'] . '::g::' . \strtr($f, [
                    \LOT => "",
                    \DS => '/'
                ]) . '/1' . $e;
            } else {
                $_['kick'] = $url . $_['/'] . '::g::' . \dirname($_['path']) . '/1' . $e;
            }
            $_SESSION['_']['folder'][$f] = 1;
        } else {
            \mkdir($f, $seal = \octdec($lot['folder']['seal'] ?? '0755'), true);
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
            if (!empty($lot['folder']['kick'])) {
                $_['kick'] = $url . $_['/'] . '::g::' . \strtr($f, [
                    \LOT => "",
                    \DS => '/'
                ]) . '/1' . $e;
            } else {
                $_['kick'] = $url . $_['/'] . '::g::' . \dirname($_['path']) . '/1' . $e;
            }
            foreach (\step($_['f'] = $f, \DS) as $v) {
                $_SESSION['_']['folder'][$v] = 1;
            }
        }
    }
    if (!empty($_['alert']['error'])) {
        unset($lot['token']);
        $_SESSION['form'] = $lot;
    }
    return $_;
}

function page($_, $lot) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'tab'=> false,
        'token' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Abort by previous hook’s return value if any
        if (!empty($_['alert']['error'])) {
            return $_;
        }
        $t = \time();
        $name = \To::kebab($lot['page']['name'] ?? $lot['page']['title'] ?? "");
        $x = $lot['page']['x'] ?? 'page';
        if ($name === "") {
            $name = \date('Y-m-d-H-i-s', $t);
        }
        unset($lot['page']['name'], $lot['page']['x']);
        $page = [];
        $p = (array) ($state->x->page->page ?? []);
        foreach ($lot['page'] as $k => $v) {
            if (
                // Skip empty value
                \trim($v) === "" ||
                // Skip default value
                isset($p[$k]) && $p[$k] === $v
            ) {
                continue;
            }
            $page[$k] = $v;
        }
        $lot['file']['content'] = \To::page($page);
        $lot['file']['name'] = $name . '.' . $x;
        $_f = $_['f']; // Get old file name
        $_ = file($_, $lot); // Move to `file`
        if (empty($_['alert']['error'])) {
            $d = \Path::F($_['f']);
            if ($_['f'] !== $_f && \is_dir($_d = \Path::F($_f))) {
                \rename($_d, $d);
            }
            if (isset($lot['data'])) {
                foreach ((array) $lot['data'] as $k => $v) {
                    if (\trim($v) !== "") {
                        \file_put_contents($ff = $d . \DS . $k . '.data', \is_array($v) ? \json_encode($v) : \s($v));
                        \chmod($ff, 0600);
                    }
                }
            }
        }
    }
    if (\is_file($f = $_['f'])) {
        $key = \ucfirst(\ltrim($_['chop'][0], '_.-'));
        $path = '<code>' . \_\lot\x\panel\h\path($_f) . '</code>'; // Use old file name
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

function state($_, $lot) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'tab'=> false,
        'token' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Abort by previous hook’s return value if any
        if (!empty($_['alert']['error'])) {
            return $_;
        }
        if (\is_file($source = \LOT . \strtr($lot['path'] ?? $_['path'], '/', \DS))) {
            $source = \realpath($source);
            \file_put_contents($source, '<?php return ' . \z(\array_replace_recursive((array) require $source, $lot['state'] ?? [])) . ';');
            $_['alert']['success'][] = ['File %s successfully updated.', ['<code>' . \_\lot\x\panel\h\path($source) . '</code>']];
        }
        $_['kick'] = $url . $_['/'] . '::g::' . $_['path'] . $e;
    }
    if (!empty($_['alert']['error'])) {
        unset($lot['token']);
        $_SESSION['form'] = $lot;
    }
    return $_;
}

function _token($_, $lot) {
    if (empty($lot['token']) || $lot['token'] !== $_['token']) {
        $_['alert']['error'][] = 'Invalid token.';
    }
    return $_;
}

foreach (['blob', 'data', 'file', 'folder', 'page', 'state'] as $v) {
    \Hook::set('do.' . $v . '.get', __NAMESPACE__ . "\\_token", 0);
    \Hook::set('do.' . $v . '.get', __NAMESPACE__ . "\\" . $v, 10);
}
