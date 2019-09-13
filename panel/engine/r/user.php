<?php

(function() {
    extract($GLOBALS);
    $state = state('user');
    $p = $state['//'] ?? $state['/'];
    if (trim($url->path, '/') === $p && empty($_GET['kick'])) {
        $_GET['kick'] = $url . $_['//'] . '/::g::/page/1';
    }
})();