<?php namespace _\lot\x\panel\task\let;

// TODO: Add option to move file to `trash` folder.

if ($_SERVER['REQUEST_METHOD'] === 'POST' || empty($_GET['token'])) {
    // TODO: Show 404 page?
    \Guard::kick(\str_replace('::l::', '::g::', $url->current));
}

// Prevent user(s) from deleting file(s) above the `.\lot\*` level
if (strpos(strtr($_['f'], [\LOT . \DS => ""]), \DS) === false) {
    \Guard::abort('Cound not delete <code>' . $_['f'] . '</code>.');
}

function blob($_, $lot) {
    $_ = file($_, $lot);
}

function data($_, $lot) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'tab' => ['data'],
        'token' => false
    ]) . $url->hash;
    $_ = file($_, $lot); // Move to `file`
    if (empty($_['alert']['error']) && $parent = \glob(\dirname($_['f']) . '.{draft,page,archive}', \GLOB_BRACE | \GLOB_NOSORT)) {
        $_['kick'] = $url . $_['/'] . '::g::' . \dirname($_['path']) . '.' . \pathinfo($parent[0], \PATHINFO_EXTENSION) . $e;
    }
    return $_;
}

function file($_, $lot) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'tab'=> false,
        'token' => false
    ]) . $url->hash;
    // Abort by previous hook’s return value if any
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    if (\is_file($f = $_['f'])) {
        \unlink($f);
        $_['alert']['success'][] = ['File %s deleted.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
        $_['kick'] = $url . $_['/'] . '::g::' . \dirname($_['path']) . '/1' . $e;
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
    // Abort by previous hook’s return value if any
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    if (\is_dir($f = $_['f'])) {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($f, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $k) {
            $v = $k->getPathname();
            if ($k->isDir()) {
                \rmdir($v);
            } else {
                \unlink($v);
            }
        }
        \rmdir($f);
        $_['alert']['success'][] = ['Folder %s deleted.', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
        $_['kick'] = $url . $_['/'] . '::g::' . \dirname($_['path']) . '/1' . $e;
    }
    return $_;
}

function page($_, $lot) {
    extract($GLOBALS, \EXTR_SKIP);
    if (\is_dir($d = \Path::F($_['f']))) {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($d, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $k) {
            $v = $k->getPathname();
            if ($k->isDir()) {
                \rmdir($v);
            } else {
                \unlink($v);
            }
        }
        \rmdir($d);
    }
    if (\is_file($f = $_['f'])) {
        $key = \ucfirst(\ltrim($_['chop'][0], '_.-'));
        $path = '<code>' . \_\lot\x\panel\h\path($f) . '</code>';
        $_ = file($_, $lot); // Move to `file`
        $alter = [
            'File %s deleted.' => ['%s %s deleted.', [$key, $path]]
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
    // There is no such delete event for state(s)
    return $_;
}

function _token($_, $lot) {
    if (empty($lot['token']) || $lot['token'] !== $_['token']) {
        extract($GLOBALS, \EXTR_SKIP);
        $_['alert']['error'][] = 'token';
        $_['kick'] = $url . $_['/'] . '::g::' . \dirname($_['path']) . '/1' . $e;
    }
    return $_;
}

foreach (['blob', 'data', 'file', 'folder', 'page', 'state'] as $v) {
    \Hook::set('do.' . $v . '.let', __NAMESPACE__ . "\\_token", 0);
    \Hook::set('do.' . $v . '.let', __NAMESPACE__ . "\\" . $v, 10);
}