<?php /* dechex(crc32('alert.let')) */

$_['alert'] = [];
$_['kick'] = $lot['kick'] ?? $url;

// Invalid token
if (empty($lot['token']) || $lot['token'] !== $_['token']) {
    $_['alert']['error'][] = 'Invalid token.';
    return $_;
}

is_file($f = $_['f']) && unlink($f);

return $_;