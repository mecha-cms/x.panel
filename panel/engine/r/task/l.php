<?php namespace _\lot\x\panel\task\let;

if (empty($_GET['token'])) {
    // TODO: Show 404 page?
    \Guard::kick(\str_replace('::l::', '::g::', $url->current));
}

// TODO: Add option to move file to `trash` folder.

function blob($_, $form) {
    return file($_, $form);
}

function file($_, $form) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'token' => false
    ]) . $url->hash;
    // Abort by previous hook’s return value if any
    if (!empty($_['alert']['error'])) {
        return $_;
    }
    if (\is_file($f = $_['f'])) {
        \unlink($f);
        $_['alert']['success'][] = ['file-let', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
        $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '/1' . $e;
    }
    return $_;
}

function folder($_, $form) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
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
        $_['alert']['success'][] = ['folder-let', '<code>' . \_\lot\x\panel\h\path($f) . '</code>'];
        $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '/1' . $e;
    }
    return $_;
}

function _token($_, $form) {
    if (empty($form['token']) || $form['token'] !== $_['token']) {
        extract($GLOBALS, \EXTR_SKIP);
        $_['alert']['error'][] = 'token';
        $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '/1' . $e;
    }
    return $_;
}

foreach (['blob', 'file', 'folder'] as $v) {
    \Hook::set('do.' . $v . '.let', __NAMESPACE__ . "\\_token", 0);
    \Hook::set('do.' . $v . '.let', __NAMESPACE__ . "\\" . $v, 10);
}