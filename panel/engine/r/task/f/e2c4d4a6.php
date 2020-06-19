<?php /* dechex(crc32('trash.let')) */

// Invalid token
if (empty($_['form']['token']) || $_['form']['token'] !== $_['token']) {
    $_['alert']['error'][] = 'Invalid token.';
    return $_;
}

$_['kick'] = $_['form']['kick'] ?? $url;

foreach (g(LOT . DS . 'trash', null, true) as $k => $v) {
    0 === $v ? rmdir($k) : unlink($k);
}

$_['alert']['success'][] = 'Trash is now empty.';

return $_;
