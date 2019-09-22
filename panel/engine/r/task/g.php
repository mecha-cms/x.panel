<?php namespace _\lot\x\panel\task\get;

function blob($_, $form) {
    // Blob is always new, so there is no such update event
    return $_;
}

function data($_, $form) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'tab' => ['data'],
        'token' => false,
        'x' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = \basename(\To::file($form['data']['name'] ?? ""));
        $form['file']['name'] = $name !== "" ? $name . '.data' : "";
        $form['file']['content'] = $form['data']['content'] ?? "";
        $_ = file($_, $form); // Move to `file`
        if (!empty($_GET['x'])) {
            $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '.' . $_GET['x'] . $e;
        }
    }
    return $_;
}

function file($_, $form) {
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
        $name = \basename(\To::file($form['file']['name'] ?? "")); // New file name
        $base = \basename($_['f']); // Old file name
        $x = \pathinfo($name, \PATHINFO_EXTENSION);
        if ($name === "") {
            $_['alert']['error'][] = ['void-field', '<strong>' . $language->name . '</strong>', true];
        } else if (\strpos(',' . \implode(',', \array_keys(\array_filter(\File::$config['x'] ?? $form['x[]'] ?? []))) . ',', ',' . $x . ',') === false) {
            $_['alert']['error'][] = ['file-x', '<code>' . $x . '</code>', true];
        } else if (\stream_resolve_include_path($f = \dirname($_['f']) . \DS . $name) && $name !== $base) {
            $_['alert']['error'][] = ['file-exist', '<code>' . \_\lot\x\panel\h\path($f) . '</code>', true];
        } else {
            if (isset($form['file']['content'])) {
                \file_put_contents($f, $form['file']['content']);
                if ($name !== $base) {
                    \unlink($_['f']);
                }
            } else if ($name !== $base) {
                \rename($_['f'], $f);
            }
            \chmod($f, \octdec($form['file']['seal'] ?? '0777'));
            $_['alert']['success'][] = ['file-update', '<code>' . \_\lot\x\panel\h\path($_['f']) . '</code>', true];
            $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '/' . $name . $e;
            $_SESSION['_']['file'][$_['f'] = $f] = 1;
        }
    }
    if (!empty($_['alert']['error'])) {
        unset($form['token']);
        $_SESSION['form'] = $form;
    }
    return $_;
}

function folder($_, $form) {
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
        $name = \To::folder($form['folder']['name'] ?? ""); // New folder name
        $base = \basename($_['f']); // Old folder name
        if ($name === "") {
            $_['alert']['error'][] = ['void-field', '<strong>' . $language->name . '</strong>', true];
        } else if (\stream_resolve_include_path($f = \dirname($_['f']) . \DS . $name) && $name !== $base) {
            $_['alert']['error'][] = ['folder-exist', '<code>' . \_\lot\x\panel\h\path($f) . '</code>', true];
        } else if ($name === $base) {
            // Do nothing
            $_['alert']['success'][] = ['folder-update', '<code>' . \_\lot\x\panel\h\path($_['f']) . '</code>', true];
            $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '/1' . $e;
            $_SESSION['_']['folder'][$f] = 1;
        } else {
            \mkdir($f, $seal = \octdec($form['folder']['seal'] ?? '0755'), true);
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
            if (!empty($form['folder']['kick'])) {
                $_['kick'] = $url . $_['//'] . '/::g::' . \strtr($f, [
                    \LOT => "",
                    \DS => '/'
                ]) . '/1' . $e;
            } else {
                $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '/1' . $e;
            }
            foreach (\step($_['f'] = $f, \DS) as $v) {
                $_SESSION['_']['folder'][$v] = 1;
            }
        }
    }
    if (!empty($_['alert']['error'])) {
        unset($form['token']);
        $_SESSION['form'] = $form;
    }
    return $_;
}

function page($_, $form) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'tab'=> false,
        'token' => false
    ]) . $url->hash;
    $title = '<strong>' . (new \Page($_['f']))->title . '</strong>'; // Get old page `title`
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Abort by previous hook’s return value if any
        if (!empty($_['alert']['error'])) {
            return $_;
        }
        $t = \time();
        $name = \To::kebab($form['page']['name'] ?? $form['page']['title'] ?? "");
        $x = $form['page']['x'] ?? 'page';
        if ($name === "") {
            $name = \date('Y-m-d-H-i-s', $t);
        }
        if (isset($form['page']['time'])) {
            $form['page']['time'] = (new \Date($form['page']['time']))->format('Y-m-d H:i:s');
        }
        if (isset($form['page']['update'])) {
            $form['page']['update'] = (new \Date($form['page']['update']))->format('Y-m-d H:i:s');
        }
        $form['file']['content'] = \To::page(\array_filter($form['page'] ?? []));
        $form['file']['name'] = $name . '.' . $x;
        $_ = file($_, $form); // Move to `file`
        if (empty($_['alert']['error'])) {
            if (!\is_dir($d = \Path::F($_['f']))) {
                \mkdir($d, 0755, true); // TODO: Also rename folder on file name change
            }
            if (isset($form['data'])) {
                if (isset($form['data']['time'])) {
                    $form['data']['time'] = (new \Date($form['data']['time']))->format('Y-m-d H:i:s');
                } else {
                    $form['data']['time'] = \date('Y-m-d H:i:s', $t); // Force
                }
                if (isset($form['data']['update'])) {
                    $form['data']['update'] = (new \Date($form['data']['time']))->format('Y-m-d H:i:s');
                }
                foreach ((array) $form['data'] as $k => $v) {
                    \file_put_contents($ff = $d . \DS . $k . '.data', \is_array($v) ? \json_encode($v) : \s($v));
                    \chmod($ff, 0600);
                }
            }
        }
    }
    if (\is_file($f = $_['f'])) {
        $alter = [
            'file-exist' => 'page-exist',
            'file-update' => ['page-update', $title]
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

function _token($_, $form) {
    if (empty($form['token']) || $form['token'] !== $_['token']) {
        $_['alert']['error'][] = 'token';
    }
    return $_;
}

foreach (['blob', 'data', 'file', 'folder', 'page'] as $v) {
    \Hook::set('do.' . $v . '.get', __NAMESPACE__ . "\\_token", 0);
    \Hook::set('do.' . $v . '.get', __NAMESPACE__ . "\\" . $v, 10);
}