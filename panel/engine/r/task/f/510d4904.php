<?php /* dechex(crc32('error.let')) */

// Invalid token
if (empty($_['form']['token']) || $_['form']['token'] !== $_['token']) {
    $_['alert']['error'][] = 'Invalid token.';
    return $_;
}

$_['alert'] = [];
$_['kick'] = $_['form']['kick'] ?? $url;

is_file($f = ENGINE . DS . 'log' . DS . 'error') && unlink($f);

return $_;
