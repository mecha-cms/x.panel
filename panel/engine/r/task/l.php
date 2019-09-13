<?php namespace _\lot\x\panel\task\let;

if (empty($_GET['token'])) {
    // TODO: Show 404 page?
    \Guard::kick(\str_replace('::l::', '::g::', $url->current));
}

function blob($_, $form) {
    return file($_, $form);
}

function file($_, $form) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'token' => false
    ]) . $url->hash;
    if (empty($form['token']) || $form['token'] !== $_['token']) {
        $_['alert']['error'][] = 'Invalid token.';
        $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '/1' . $e;
        return $_;
    }
    if (\is_file($_['f'])) {
        \unlink($_['f']);
        $_['alert']['success'][] = 'Path <code>' . $_['f'] . '</code> deleted.';
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
    if (empty($form['token']) || $form['token'] !== $_['token']) {
        $_['alert']['error'][] = 'Invalid token.';
        $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '/1' . $e;
        return $_;
    }
    if (\is_dir($_['f'])) {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($_['f'], \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $k) {
            $v = $k->getPathname();
            if ($k->isDir()) {
                \rmdir($v);
            } else {
                \unlink($v);
            }
        }
        \rmdir($_['f']);
        $_['alert']['success'][] = 'Path <code>' . $_['f'] . '</code> deleted.';
        $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '/1' . $e;
    }
    return $_;
}

foreach (['blob', 'file', 'folder'] as $v) {
    \Hook::set('on.' . $v . '.let', __NAMESPACE__ . "\\" . $v, 10);
}