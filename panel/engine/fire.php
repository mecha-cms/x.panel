<?php

$__pass = false;

if (!$__user = Cookie::get('Mecha\Panel.user')) {
    // ...
}

if (!$__token = File::open(ENGINE . DS . 'log' . DS . 'user' . DS . $__user . DS . 'token.data')->get(0)) {
    // ...
}

if (Cookie::get('Mecha\Panel.token') === $__token) {
    $__pass = true;
}

if (
    (
        $url->path === $__state['path'] ||
        strpos($url->path . '/', $__state['path'] . '/') === 0
    ) &&
    (
        $__pass ||
        $url->path === $__state['path'] . '/::g::/enter'
    )
) {
    require PANEL . DS . 'lot' . DS . 'worker' . DS . 'worker' . DS . 'route.php';
}