<?php

$__user_enter = $__user_key = $__user_token = null;

if ($__user_key = Cookie::get('panel.c.user.key')) {
    if ($__user_token = File::open(USER . DS . $__user_key . DS . 'token.data')->get(0)) {
        if (Cookie::get('panel.c.user.token') === $__user_token) {
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
        $url->path === $__state->path . '/::s::/user' ||
        $url->path === $__state->path . '/::g::/enter'
    )
) {
    if (
        $url->path === $__state->path . '/::g::/enter' &&
        file_exists(PANEL . DS . 'lot' . DS . 'worker' . DS . 'index' . DS . 'user.php') &&
        !g(USER, 'page')
    ) {
        Message::info('void', $language->users);
        Guardian::kick($__state->path . '/::s::/user');
    }
    require PANEL . DS . 'lot' . DS . 'worker' . DS . 'worker' . DS . 'route.php';
}