<?php

$__user_enter = $__user_key = $__user_token = null;

if ($__user_key = Cookie::get('Mecha\Panel.user.key')) {
    if ($__user_token = File::open(ENGINE . DS . 'log' . DS . 'user' . DS . $__user_key . DS . 'token.data')->get(0)) {
        if (Cookie::get('Mecha\Panel.user.token') === $__user_token) {
            $__user_enter = true;
        }
    }
}

if (
    (
        $url->path === $__state->path ||
        strpos($url->path . '/', $__state->path . '/') === 0
    ) &&
    (
        $__user_enter ||
        $url->path === $__state->path . '/::g::/enter'
    )
) {
    require PANEL . DS . 'lot' . DS . 'worker' . DS . 'worker' . DS . 'route.php';
}