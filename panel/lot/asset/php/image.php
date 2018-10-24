<?php

header('Content-Type: image/png');
header('Pragma: private');
header('Cache-Control: private, max-age=' . 60 * 60 * 24 * 30 * 12); // 1 year
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $i) . ' GMT');
$image = imagecreate($_GET['w'] ?? 1, $_GET['h'] ?? 1);
$hash = str_split($_GET['c'] ?? substr(md5(microtime()), 0, 6), 2);
imagecolorallocate($image, hexdec($hash[0]), hexdec($hash[1]), hexdec($hash[2]));
imagepng($image);
imagedestroy($image);
exit;