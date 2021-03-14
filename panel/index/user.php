<?php

(static function() {
    extract($GLOBALS);
    $path = strtr($url->path, ['/index.php' => ""]);
    $p = $_['user']['guard']['path'] ?? $_['user']['path'];
    if ($path === $p && empty($_GET['kick'])) {
        $_GET['kick'] = $_['/'] . '/::g::' . $_['state']['path'] . '/1';
    }
})();
