<?php

$__user_key = Cookie::get('panel.c.user.key');
$__user_token = Cookie::get('panel.c.user.token');

User::_('set', function($id = null, $token, $fail = false) {
    if (!file_exists(USER . DS . $id . '.page')) {
        return $fail;
    }
    $c = [
        'expire' => 30,
        'http_only' => true
    ];
    Cookie::set('panel.c.user.key', $id, $c);
    Cookie::set('panel.c.user.token', $token, $c);
    return $id;
});

User::_('reset', function($id = null, $key = null, $fail = false) {
    if (isset($id)) {
        if ($f = File::exist(USER . DS . $id . DS . 'token.data')) {
            Cookie::reset('panel.c.user.key');
            Cookie::reset('panel.c.user.token');
            File::open($f)->delete();
            return $id;
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

User::_('get', function($id = null, $key = null, $fail = false) use($__user_key, $__user_token) {
    $id = $id ?: $__user_key;
    if (!$id || !$__user_token) {
        return $fail;
    }
    if (file_exists(USER . DS . $id . '.page')) {
        $__user = new User($id);
        if (isset($key)) {
            return $__user->{$key}($fail);
        }
        return $__user;
    }
    return $fail;
});