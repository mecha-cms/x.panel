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
        $lot['file']['name'] = $name !== "" ? $name . '.data' : "";
        $lot['file']['content'] = $lot['data']['content'] ?? "";
        $_ = file($_, $lot); // Move to `file`
        $p = \dirname($_['f']);
        if (empty($_['alert']['error']) && $parent = \File::exist([
            $p . '.draft',
            $p . '.page',
            $p . '.archive'
        ])) {
            $_['kick'] = $url . $_['/'] . '/::g::' . \dirname($_['path']) . '.' . \pathinfo($parent, \PATHINFO_EXTENSION) . $e;
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
        if ($name === "") {
            $_['alert']['error'][] = ['void-field', '<strong>' . $language->name . '</strong>', true];
        } else if (\strpos(',' . \implode(',', \array_keys(\array_filter(\File::$state['x'] ?? $lot['x[]'] ?? []))) . ',', ',' . $x . ',') === false) {
            $_['alert']['error'][] = ['file-x', '<code>' . $x . '</code>', true];
        } else if (\stream_resolve_include_path($f = \dirname($_['f']) . \DS . $name) && $name !== $base) {
            $_['alert']['error'][] = ['file-exist', '<code>' . \_\lot\x\panel\h\path($f) . '</code>', true];
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
            $_['alert']['success'][] = ['file-update', '<code>' . \_\lot\x\panel\h\path($_['f']) . '</code>', true];
            $_['kick'] = $url . $_['/'] . '/::g::' . \dirname($_['path']) . '/' . $name . $e;
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
            $_['alert']['error'][] = ['void-field', '<strong>' . $language->name . '</strong>', true];
        } else if (\stream_resolve_include_path($f = \dirname($_['f']) . \DS . $name) && $name !== $base) {
            $_['alert']['error'][] = ['folder-exist', '<code>' . \_\lot\x\panel\h\path($f) . '</code>', true];
        } else if ($name === $base) {
            // Do nothing
            $_['alert']['success'][] = ['folder-update', '<code>' . \_\lot\x\panel\h\path($f = $_['f']) . '</code>', true];
            if (!empty($lot['folder']['kick'])) {
                $_['kick'] = $url . $_['/'] . '/::g::' . \strtr($f, [
                    \LOT => "",
                    \DS => '/'
                ]) . '/1' . $e;
            } else {
                $_['kick'] = $url . $_['/'] . '/::g::' . \dirname($_['path']) . '/1' . $e;
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
            $_['alert']['success'][] = ['folder-update', '<code>' . \_\lot\x\panel\h\path($_['f']) . '</code>', true];
            if (!empty($lot['folder']['kick'])) {
                $_['kick'] = $url . $_['/'] . '/::g::' . \strtr($f, [
                    \LOT => "",
                    \DS => '/'
                ]) . '/1' . $e;
            } else {
                $_['kick'] = $url . $_['/'] . '/::g::' . \dirname($_['path']) . '/1' . $e;
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
        if (isset($lot['page']['time'])) {
            $lot['page']['time'] = (new \Date($lot['page']['time']))->format('Y-m-d H:i:s');
        }
        if (isset($lot['page']['update'])) {
            $lot['page']['update'] = (new \Date($lot['page']['update']))->format('Y-m-d H:i:s');
        }
        unset($lot['page']['name'], $lot['page']['x']);
        $lot['file']['content'] = \To::page(\array_filter($lot['page'] ?? []));
        $lot['file']['name'] = $name . '.' . $x;
        $_ = file($_, $lot); // Move to `file`
        if (empty($_['alert']['error'])) {
            if (!\is_dir($d = \Path::F($_['f']))) {
                \mkdir($d, 0755, true); // TODO: Also rename folder on file name change
            }
            if (isset($lot['data'])) {
                if (isset($lot['data']['time'])) {
                    $lot['data']['time'] = (new \Date($lot['data']['time']))->format('Y-m-d H:i:s');
                } else {
                    $lot['data']['time'] = \date('Y-m-d H:i:s', $t); // Force
                }
                if (isset($lot['data']['update'])) {
                    $lot['data']['update'] = (new \Date($lot['data']['time']))->format('Y-m-d H:i:s');
                }
                foreach ((array) $lot['data'] as $k => $v) {
                    \file_put_contents($ff = $d . \DS . $k . '.data', \is_array($v) ? \json_encode($v) : \s($v));
                    \chmod($ff, 0600);
                }
            }
        }
    }
    if (\is_file($f = $_['f'])) {
        $key = $language->{\ltrim($_['chop'][0], '_.-')};
        $path = '<code>' . \_\lot\x\panel\h\path($f) . '</code>';
        $alter = [
            'file-exist' => ['*-exist', [$key, $path]],
            'file-update' => ['*-update', [$key, $path]]
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

function _token($_, $lot) {
    if (empty($lot['token']) || $lot['token'] !== $_['token']) {
        $_['alert']['error'][] = 'token';
    }
    return $_;
}

foreach (['blob', 'data', 'file', 'folder', 'page'] as $v) {
    \Hook::set('do.' . $v . '.get', __NAMESPACE__ . "\\_token", 0);
    \Hook::set('do.' . $v . '.get', __NAMESPACE__ . "\\" . $v, 10);
}