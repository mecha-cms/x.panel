<?php

User::plug('current', function($key = null, $fail = false) {
    $__user_key = Cookie::get('Mecha\Panel.user.key');
    $__user_token = Cookie::get('Mecha\Panel.user.token');
    if (!$__user_key || !$__user_token) return $fail;
    if (file_exists(ENGINE . DS . 'log' . DS . 'user' . DS . $__user_key . '.page')) {
        $__user = new User($__user_key);
        if (isset($key)) {
            return $__user->{$key}($fail);
        }
        return $__user;
    }
    return $fail;
});