<?php /* dechex(crc32('cache.let')) */

// Invalid token
if (empty($_['form']['token']) || $_['form']['token'] !== $_['token']) {
    $_['alert']['error'][] = 'Invalid token.';
    return $_;
}

$_['kick'] = $_['form']['kick'] ?? $url;

foreach (g(LOT . DS . 'cache', null, true) as $k => $v) {
    0 === $v ? rmdir($k) : unlink($k);
}

$_['alert']['success'][] = 'Cache successfully refreshed.';

return $_;
