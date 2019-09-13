<?php namespace _\lot\x\panel\task;

if (empty($_GET['token'])) {
    // TODO: Show 404 page?
    \Guard::kick(\str_replace('::l::', '::g::', $url->current));
}

function let($_, $var) {
    global $url;
    $e = $url->query('&', [
        'tab' => false,
        'token' => false
    ]) . $url->hash;
    if (empty($var['token']) || $var['token'] !== $_['token']) {
        $_['alert']['error'][] = 'Invalid token.';
        $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '/1' . $e;
        return $_;
    }
    if (\is_file($_['f'])) {
        if (\unlink($_['f'])) {
            $_['alert']['success'][] = 'Path <code>' . $_['f'] . '</code> deleted.';
            $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '/1' . $e;
        }
    } else if (\is_dir($_['f'])) {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($_['f'], \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $k) {
            $v = $k->getPathname();
            if ($k->isDir()) {
                rmdir($v);
            } else {
                unlink($v);
            }
        }
        $_['alert']['success'][] = 'Path <code>' . $_['f'] . '</code> deleted.';
        $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '/1' . $e;
    }
    return $_;
}

\Hook::set('on.file.let', __NAMESPACE__ . "\\let", 10);