<?php

if ($__sgr !== 'g') {
    Shield::abort(PANEL_404);
}

if (Request::is('post')) {
    $__user = Request::post('user');
    $__pass = Request::post('pass');
    $__token = Request::post('token');
    $f = ENGINE . DS . 'log' . DS . 'user' . DS . $__user;
    if (!$__user) {
        Message::error('void_field', $language->user, true);
    } else if (!$__pass) {
        Message::error('void_field', $language->pass, true);
    } else if (file_exists($f . '.page')) {
        if (!file_exists($f . DS . 'pass.data')) {
            // Reset password by deleting `pass.data` manually
            File::write(password_hash($__pass . $__user, PASSWORD_DEFAULT))->saveTo($f . DS . 'pass.data');
        }
        if (password_verify($__pass . $__user, File::open($f . DS . 'pass.data')->get(0, ""))) {
            File::write($__token)->saveTo($f . DS . 'token.data');
            $c = [
                'expire' => 30,
                'http_only' => true
            ];
            Cookie::set('Mecha\Panel.user', $__user, $c);
            Cookie::set('Mecha\Panel.token', $__token, $c);
            Message::success('user_enter');
            Guardian::kick($__state->path . '/::g::/page');
        } else {
            Message::error('user_or_pass');
        }
    } else {
        Message::error('user_or_pass');
    }
    if (Message::$x) {
        Request::save('post', 'user', $__user);
    }
}

if (Route::is($__state->path . '/::g::/enter')) {
    Hook::set('shield.path', function($path) {
        $base = Path::B($path);
        if ($base === 'page.php') {
            return PANEL . DS . 'lot' . DS . 'worker' . DS . 'page' . DS . 'enter.php';
        }
        return $path;
    });
}