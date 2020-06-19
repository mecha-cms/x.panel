<?php namespace _\lot\x\panel\task\l;

if ('POST' === $_SERVER['REQUEST_METHOD'] || empty($_GET['token'])) {
    // TODO: Show 404 page?
    \Guard::kick(\str_replace('::l::', '::g::', $url->current));
}

// Prevent user(s) from deleting file(s) above the `.\lot\*` level
if (false === strpos(strtr($_['f'], [\LOT . \DS => ""]), \DS)) {
    \Guard::abort('Cound not delete <code>' . $_['f'] . '</code>.');
}

function blob($_) {
    $_ = file($_);
}

function data($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'layout' => false,
        'tab' => ['data'],
        'token' => false,
        'trash' => false
    ]) . $url->hash;
    $_ = file($_); // Move to `file`
    if (empty($_['alert']['error']) && $parent = \glob(\dirname($_['f']) . '.{archive,draft,page}', \GLOB_BRACE | \GLOB_NOSORT)) {
        $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::/' . \dirname($_['path']) . '.' . \pathinfo($parent[0], \PATHINFO_EXTENSION) . $e;
    }
    return $_;
}

function file($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'layout' => false,
        'tab'=> false,
        'token' => false,
        'trash' => false
    ]) . $url->hash;
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error'])) {
        return $_;
    }
    $trash = !empty($_['form']['trash']) ? (new \Time($_['form']['trash']))->name : false;
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
        $_['alert']['success'][] = [$trash ? 'File %s successfully moved to trash.' : 'File %s successfully deleted.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
        $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::/' . \dirname($_['path']) . '/1' . $e;
    }
    return $_;
}

function folder($_) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'layout' => false,
        'tab'=> false,
        'token' => false,
        'trash' => false
    ]) . $url->hash;
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error'])) {
        return $_;
    }
    $trash = !empty($_['form']['trash']) ? (new \Time($_['form']['trash']))->name : false;
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
                $_SESSION['_'][$k->isDir() ? 'folder' : 'file'][\trim($vv, \DS)] = 1;
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
        $_['alert']['success'][] = [$trash ? 'Folder %s successfully moved to trash.' : 'Folder %s successfully deleted.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
        $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::/' . \dirname($_['path']) . '/1' . $e;
    }
    return $_;
}

function page($_) {
    extract($GLOBALS, \EXTR_SKIP);
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error'])) {
        return $_;
    }
    $trash = !empty($_['form']['trash']) ? (new \Time($_['form']['trash']))->name : false;
    if (\is_dir($d = \Path::F($_['f']))) {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($d, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $k) {
            $v = $k->getPathname();
            if ($trash) {
                $vv = \strtr($v, [\LOT . \DS => \LOT . \DS . 'trash' . \DS . $trash . \DS]);
                if (!\is_dir($dd = \dirname($vv))) {
                    \mkdir($dd, 0775, true);
                }
                \rename($v, $vv);
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
        $key = \ucfirst(\ltrim($_['chops'][0], '_.-'));
        $path = '<code>' . \_\lot\x\panel\h\path($f) . '</code>';
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

function _token($_) {
    if (empty($_['form']['token']) || $_['form']['token'] !== $_['token']) {
        extract($GLOBALS, \EXTR_SKIP);
        $_['alert']['error'][] = 'Invalid token.';
        $_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::/' . \dirname($_['path']) . '/1' . $e;
    }
    return $_;
}

foreach (['blob', 'data', 'file', 'folder', 'page', 'state'] as $v) {
    \Hook::set('do.' . $v . '.let', __NAMESPACE__ . "\\_token", 0);
    \Hook::set('do.' . $v . '.let', __NAMESPACE__ . "\\" . $v, 10);
}
