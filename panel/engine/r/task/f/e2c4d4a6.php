<?php /* dechex(crc32('trash.let')) */

$_['kick'] = $_['form']['lot']['kick'] ?? $url;

foreach (g($d = LOT . DS . 'trash', null, true) as $k => $v) {
    0 === $v ? rmdir($k) : unlink($k);
}

$_['alert']['success'][$d] = 'Trash is now empty.';

return $_;
