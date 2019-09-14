<?php namespace _\lot\x\panel\task\get;

function blob($_, $form) {
    // Blob is always new, so there is no such update event
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
            $_['alert']['error'][] = ['void-field', '<em>' . $language->name . '</em>', true];
        } else if (\strpos(',' . \implode(',', \array_keys(\array_filter(\File::$config['x'] ?? $form['x[]'] ?? []))) . ',', ',' . $x . ',') === false) {
            $_['alert']['error'][] = ['file-x', '<code>' . $x . '</code>'];
        } else if (\stream_resolve_include_path($f = \dirname($_['f']) . \DS . $name) && $name !== $base) {
            $_['alert']['error'][] = ['file-exist', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
        } else {
            \file_put_contents($f, $form['file']['content'] ?? "");
            \chmod($f, \octdec($form['file']['seal'] ?? '0777'));
            if ($name !== $base) {
                \unlink($_['f']);
            }
            $_['alert']['success'][] = ['file-update', '<code>' . \_\lot\x\panel\h\path($_['f']) . '</code>'];
            $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '/' . $name . $e;
            $_SESSION['_']['file'][$_['f'] = $f] = 1;
        }
    }
    return $_;
}

function folder($_, $form) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
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
            $_['alert']['error'][] = ['void-field', '<em>' . $language->name . '</em>', true];
        } else if (\stream_resolve_include_path($f = \dirname($_['f']) . \DS . $name) && $name !== $base) {
            $_['alert']['error'][] = ['folder-exist', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
        } else if ($name === $base) {
            // Do nothing
            $_['alert']['success'][] = ['folder-update', '<code>' . $_['f'] . '</code>'];
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
            $_['alert']['success'][] = ['folder-update', '<code>' . \_\lot\x\panel\h\path($_['f']) . '</code>'];
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
    return $_;
}

function _token($_, $form) {
    if (empty($form['token']) || $form['token'] !== $_['token']) {
        $_['alert']['error'][] = 'token';
    }
    return $_;
}

foreach (['blob', 'file', 'folder'] as $v) {
    \Hook::set('do.' . $v . '.get', __NAMESPACE__ . "\\_token", 0);
    \Hook::set('do.' . $v . '.get', __NAMESPACE__ . "\\" . $v, 10);
}