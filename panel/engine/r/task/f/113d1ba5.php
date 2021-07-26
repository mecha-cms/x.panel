<?php /* dechex(crc32('x.toggle')) */

$_['kick'] = $_['form']['lot']['kick'] ?? $url;

$n = basename($_['f']);

if (is_file($f = $_['f'] . DS . 'index.php')) {
    if (rename($f, $_['f'] . DS . 'index.x')) {
        $_['alert']['success'][$f] = ['Extension %s successfully deactivated.', ['<code>' . $n . '</code>']];
    }
} else if (is_file($f = $_['f'] . DS . 'index.x')) {
    if (rename($f, $_['f'] . DS . 'index.php')) {
        $_['alert']['success'][$f] = ['Extension %s successfully activated.', ['<code>' . $n . '</code>']];
    }
} else {
    $_['alert']['error'][$f] = ['Extension %s could not be toggled.', ['<code>' . $n . '</code>']];
}

$_SESSION['_']['folder'][$_['f']] = 1;

return $_;

