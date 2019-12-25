<?php /* dechex(crc32('cache.let')) */

$_['kick'] = $lot['kick'] ?? $url;

// Invalid token
if (empty($lot['token']) || $lot['token'] !== $_['token']) {
    $_['alert']['error'][] = 'Invalid token.';
    return $_;
}

foreach (g(LOT . DS . 'cache', null, true) as $k => $v) {
    0 === $v ? rmdir($k) : unlink($k);
}

$_['alert']['success'][] = 'Cache successfully refreshed.';

return $_;