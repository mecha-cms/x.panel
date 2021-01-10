<?php /* dechex(crc32('trash.let')) */

$_['kick'] = $_['form']['kick'] ?? $url;

foreach (g(LOT . DS . 'trash', null, true) as $k => $v) {
    0 === $v ? rmdir($k) : unlink($k);
}

$_['alert']['success'][] = 'Trash is now empty.';

return $_;
