<?php

User::plug('current', function($key = null, $fail = false) {
    $__user_key = Cookie::get('panel.c.user.key');
    $__user_token = Cookie::get('panel.c.user.token');
    if (!$__user_key || !$__user_token) return $fail;
    if (file_exists(USER . DS . $__user_key . '.page')) {
        $__user = new User($__user_key);
        if (isset($key)) {
            return $__user->{$key}($fail);
        }
        return $__user;
    }
    return $fail;
});