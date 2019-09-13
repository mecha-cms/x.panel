<?php namespace _\lot\x\panel\task\get;

function blob($_, $form) {
    // Blob is always new, so no need to rename
}

function file($_, $var) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'tab' => false,
        'token' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($var['token']) || $var['token'] !== $_['token']) {
            $_['alert']['error'][] = 'Invalid token.';
            return $_;
        }
        $name = \basename(\To::file($var['file']['name'] ?? "")); // New file name
        $base = \basename($_['f']); // Old file name
        if ($name === "") {
            $_['alert']['error'][] = 'Please fill out the <em>Name</em> field.';
        } else if (\stream_resolve_include_path($f = \dirname($_['f']) . \DS . $name) && $name !== $base) {
            $_['alert']['error'][] = 'Path <code>' . $f . '</code> already exists.';
        } else {
            \file_put_contents($f, $var['file']['content'] ?? "");
            \chmod($f, \octdec($var['file']['seal'] ?? '0777'));
            if ($name !== $base) {
                \unlink($_['f']);
            }
            $_['alert']['success'][] = 'Path <code>' . $_['f'] . '</code> updated.';
            $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '/' . $name . $e;
            $_SESSION['_']['file'][$f] = 1;
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
        if (empty($form['token']) || $form['token'] !== $_['token']) {
            $_['alert']['error'][] = 'Invalid token.';
            return $_;
        }
        $name = \To::folder($form['folder']['name'] ?? ""); // New folder name
        $base = \basename($_['f']); // Old folder name
        if ($name === "") {
            $_['alert']['error'][] = 'Please fill out the <em>Name</em> field.';
        } else if (\stream_resolve_include_path($f = \dirname($_['f']) . \DS . $name) && $name !== $base) {
            $_['alert']['error'][] = 'Path <code>' . $f . '</code> already exists.';
        } else if ($name === $base) {
            // Do nothing
            $_['alert']['success'][] = 'Path <code>' . $_['f'] . '</code> updated.';
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
            $_['alert']['success'][] = 'Path <code>' . $_['f'] . '</code> updated.';
            if (!empty($form['folder']['kick'])) {
                $_['kick'] = $url . $_['//'] . '/::g::' . \strtr($f, [
                    \LOT => "",
                    \DS => '/'
                ]) . '/1' . $e;
            } else {
                $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '/1' . $e;
            }
            foreach (\step($f, \DS) as $v) {
                $_SESSION['_']['folder'][$v] = 1;
            }
        }
    }
    return $_;
}

foreach (['blob', 'file', 'folder'] as $v) {
    \Hook::set('on.' . $v . '.get', __NAMESPACE__ . "\\" . $v, 10);
}