<?php /* dechex(crc32('trash.restore')) */

$_['kick'] = $_['form']['kick'] ?? $url . $_['/'] . '/::g::/trash/1';

foreach (g($d = $_['f'], null, true) as $k => $v) {
    $ff = LOT . DS . ($kk = strtr($k, [$d . DS => ""]));
    if (0 === $v) {
        $_SESSION['_']['folder'][$ff] = 1;
        continue;
    }
    if (!is_dir($dd = dirname($ff))) {
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
