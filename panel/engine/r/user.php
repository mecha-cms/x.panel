<?php

(function() {
    extract($GLOBALS);
    $state = State::get('x.user', true);
    $p = $state['_path'] ?? $state['path'];
    if ($url->path === $p && empty($_GET['kick'])) {
        $_GET['kick'] = $url . $_['/'] . '/::g::/page/1';
    }
})();