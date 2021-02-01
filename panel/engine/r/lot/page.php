<?php

// Prevent directory traversal attack <https://en.wikipedia.org/wiki/Directory_traversal_attack>
$type = strtr($_['type'], [
    '/' => DS,
    '../' => ""
]);

if (is_file($lot = __DIR__ . DS . '..' . DS . 'type' . DS . $type . '.php')) {
    return require $lot;
}

return [ /* TODO: Error type does not exist */ ];
