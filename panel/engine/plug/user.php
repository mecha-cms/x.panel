<?php

$__user_key = Cookie::get('panel.c.user.key');
$__user_token = Cookie::get('panel.c.user.token');

User::plug('set', function($key = null, $token, $fail = false) use($__user_key, $__user_token) {
    if (!file_exists(USER . DS . $key . '.page')) {
        return $fail;
    }
    $c = [
        'expire' => 30,
        'http_only' => true
    ];
    Cookie::set('panel.c.user.key', $key, $c);
    Cookie::set('panel.c.user.token', $token, $c);
    return $key;
});

User::plug('reset', function($key = null) {
    if (isset($key)) {
        if ($f = File::exist(USER . DS . $key . DS . 'token.data')) {
            File::open($f)->delete();
            return $key;
        }
        return $fail;
    }
    if ($f = glob(USER . DS . '*' . DS . 'token.data', GLOB_NOSORT)) {
        $o = [];
        foreach ($f as $v) {
            $o[] = Path::N($v);
            File::open($v)->delete();
        }
        return $o;
    }
    return $fail;
});

User::plug('get', function($key = null, $fail = false) use($__user_key, $__user_token) {
    if (!$__user_key || !$__user_token) {
        return $fail;
    }
    if (file_exists(USER . DS . $__user_key . '.page')) {
        $__user = new User($__user_key);
        if (isset($key)) {
            return $__user->{$key}($fail);
        }
        return $__user;
    }
    return $fail;
});