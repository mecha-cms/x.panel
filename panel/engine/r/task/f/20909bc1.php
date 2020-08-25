<?php /* dechex(crc32('trash.restore')) */

// Invalid token
if (empty($_['form']['token']) || $_['form']['token'] !== $_['token']) {
    $_['alert']['error'][] = 'Invalid token.';
    return $_;
}

$_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::/trash/1';

foreach (g($d = $_['f'], null, true) as $k => $v) {
    $kk = strtr($k, [$d . DS => ""]);
    if (0 === $v) {
        $_SESSION['_']['folder'][LOT . DS . $kk] = 1;
        continue;
    }
    if (!is_dir($dd = dirname($ff = LOT . DS . $kk))) {
        mkdir($dd, 0775, true);
    }
    if (is_file($ff)) {
        // TODO: Add message if file already exists
        continue;
    }
    rename($k, $ff);
    $_SESSION['_']['file'][$ff] = 1;
}

// Remove empty folder(s)
foreach (g($d, 0, true) as $k => $v) {
    rmdir($k);
}

rmdir($d);

$_['alert']['success'][] = 'Files successfully restored.';

return $_;
