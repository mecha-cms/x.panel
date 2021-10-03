<?php namespace x\panel\task\l;

if ('post' === $_['form']['type'] || empty($_['form']['lot']['token'])) {
    // TODO: Show 404 page?
    \Guard::kick(\strtr($url->current, ['::l::' => '::g::']));
}

// Prevent user(s) from deleting file(s) above the `.\lot\*` level
if (false === strpos(strtr($_['f'], [\LOT . \DS => ""]), \DS)) {
    \Guard::abort('Could not delete <code>' . $_['f'] . '</code>.');
}

function blob($_) {
    return file($_);
}

function data($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $hash = $_['form']['lot']['hash'] ?? "";
    $e = \To::query(\array_replace([
        'stack' => $_['form']['lot']['stack'] ?? [],
        'tab' => $_['form']['lot']['tab'] ?? ['data']
    ], $_['form']['lot']['query'] ?? [])) . ("" !== $hash ? '#' . $hash : "");
    $_ = file($_); // Move to `file`
    if (empty($_['alert']['error']) && $parent = \glob(\dirname($_['f']) . '.{archive,draft,page}', \GLOB_BRACE | \GLOB_NOSORT)) {
        $_['kick'] = $_['form']['lot']['kick'] ?? $_['/'] . '/::g::/' . \dirname($_['path']) . '.' . \pathinfo($parent[0], \PATHINFO_EXTENSION) . $e;
    }
    return $_;
}

function file($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $hash = $_['form']['lot']['hash'] ?? "";
    $e = \To::query(\array_replace([
        'stack' => $_['form']['lot']['stack'] ?? [],
        'tab' => $_['form']['lot']['tab'] ?? []
    ], $_['form']['lot']['query'] ?? [])) . ("" !== $hash ? '#' . $hash : "");
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error'])) {
        return $_;
    }
    $trash = !empty($_['form']['lot']['trash']) ? (new \Time($_['form']['lot']['trash']))->name : false;
    if (\is_file($f = $_['f'])) {
        if ($trash) {
            $ff = \strtr($f, [\LOT . \DS => \LOT . \DS . 'trash' . \DS . $trash . \DS]);
            if (!\is_dir($dd = \dirname($ff))) {
                \mkdir($dd, 0775, true);
            }
            \rename($f, $ff);
            $_SESSION['_']['file'][\rtrim($ff, \DS)] = 1;
        } else {
            \unlink($f);
        }
        $_['alert']['success'][$f] = [$trash ? 'File %s successfully moved to trash.' : 'File %s successfully deleted.', '<code>' . \x\panel\from\path($f) . '</code>'];
        $_['kick'] = $_['form']['lot']['kick'] ?? $_['/'] . '/::g::/' . \dirname($_['path']) . '/1' . $e;
    }
    return $_;
}

function folder($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $hash = $_['form']['lot']['hash'] ?? "";
    $e = \To::query(\array_replace([
        'stack' => $_['form']['lot']['stack'] ?? [],
        'tab' => $_['form']['lot']['tab'] ?? []
    ], $_['form']['lot']['query'] ?? [])) . ("" !== $hash ? '#' . $hash : "");
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error'])) {
        return $_;
    }
    $trash = !empty($_['form']['lot']['trash']) ? (new \Time($_['form']['lot']['trash']))->name : false;
    if (\is_dir($f = $_['f'])) {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($f, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $k) {
            $v = $k->getPathname();
            if ($trash) {
                $vv = \strtr($v, [\LOT . \DS => \LOT . \DS . 'trash' . \DS . $trash . \DS]);
                if (!\is_dir($dd = \dirname($vv))) {
                    \mkdir($dd, 0775, true);
                }
                if (!\is_dir($vv) && !\is_file($vv)) {
                    \rename($v, $vv);
                }
                if ($k->isDir()) {
                    \rmdir($v);
                }
                $_SESSION['_'][$k->isDir() ? 'folder' : 'file'][\rtrim($vv, \DS)] = 1;
            } else {
                if ($k->isDir()) {
                    \rmdir($v);
                } else {
                    \unlink($v);
                }
            }
        }
        if ($trash) {
            $_SESSION['_']['folder'][\rtrim(\strtr($f, [\LOT . \DS => \LOT . \DS . 'trash' . \DS . $trash . \DS]), \DS)] = 1;
        }
        \rmdir($f);
        $_['alert']['success'][$f] = [$trash ? 'Folder %s successfully moved to trash.' : 'Folder %s successfully deleted.', '<code>' . \x\panel\from\path($f) . '</code>'];
        $_['kick'] = $_['form']['lot']['kick'] ?? $_['/'] . '/::g::/' . \dirname($_['path']) . '/1' . $e;
    }
    return $_;
}

function page($_) {
    extract($GLOBALS, \EXTR_SKIP);
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error'])) {
        return $_;
    }
    $trash = !empty($_['form']['lot']['trash']) ? (new \Time($_['form']['lot']['trash']))->name : false;
    if (\is_dir($d = \Path::F($_['f']))) {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($d, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $k) {
            $v = $k->getPathname();
            if ($trash) {
                $vv = \strtr($v, [\LOT . \DS => \LOT . \DS . 'trash' . \DS . $trash . \DS]);
                if (!\is_dir($dd = \dirname($vv))) {
                    \mkdir($dd, 0775, true);
                }
                if (!\is_dir($vv) && !\is_file($vv)) {
                    \rename($v, $vv);
                }
                if ($k->isDir()) {
                    \rmdir($v);
                }
                $_SESSION['_'][$k->isDir() ? 'folder' : 'file'][\rtrim($vv, \DS)] = 1;
            } else {
                if ($k->isDir()) {
                    \rmdir($v);
                } else {
                    \unlink($v);
                }
            }
        }
        \rmdir($d);
    }
    if (\is_file($f = $_['f'])) {
        $key = \ucfirst(\ltrim($_['id'], '_.-'));
        $path = '<code>' . \x\panel\from\path($f) . '</code>';
        $_ = file($_); // Move to `file`
        $alter = [
            'File %s successfully deleted.' => ['%s %s successfully deleted.', [$key, $path]],
            'File %s successfully moved to trash.' => ['%s %s successfully moved to trash.', [$key, $path]]
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
    // There is no such delete event for state(s)
    return $_;
}

foreach (['blob', 'data', 'file', 'folder', 'page', 'state'] as $v) {
    \Hook::set('do.' . $v . '.let', __NAMESPACE__ . "\\" . $v, 10);
}