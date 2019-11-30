<?php /* dechex(crc32('alert.count')) */

// Invalid token
if (empty($lot['token']) || $lot['token'] !== $_['token']) {
    echo -1;
    exit;
}

http_response_code(200);
header('Content-Type: text/plain');
$i = q(g(LOT . DS . '.alert', 'page'));
echo $i > 0 ? $i : "";
exit;