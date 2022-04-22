<?php

// Prevent directory traversal attack <https://en.wikipedia.org/wiki/Directory_traversal_attack>
$type = strtr($_['type'], [
    '/' => DS,
    '../' => ""
]);

if (is_file($f = __DIR__ . DS . '..' . DS . 'type' . DS . $type . '.php')) {
    $_ = (array) require $f;
}

return $_;