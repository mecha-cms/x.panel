<?php /* dechex(crc32('alert.let')) */

// Invalid token
if (empty($lot['token']) || $lot['token'] !== $_['token']) {
    $_['alert']['error'][] = 'Invalid token.';
    return $_;
}

$_['alert'] = [];
$_['kick'] = $lot['kick'] ?? $url;

is_file($f = $_['f']) && unlink($f);

return $_;
