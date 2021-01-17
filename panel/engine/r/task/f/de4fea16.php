<?php /* dechex(crc32('cache.let')) */

$_['kick'] = $_['form']['lot']['kick'] ?? $url;

foreach (g(LOT . DS . 'cache', null, true) as $k => $v) {
    0 === $v ? rmdir($k) : unlink($k);
}

$_['alert']['success'][] = 'Cache successfully refreshed.';

return $_;
