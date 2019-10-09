<?php

(function() {
    extract($GLOBALS);
    $p = $_['user']['guard']['path'] ?? $_['user']['path'];
    if ($url->path === $p && empty($_GET['kick'])) {
        $_GET['kick'] = $url . $_['/'] . '::g::' . $_['state']['path'] . '/1';
    }
})();