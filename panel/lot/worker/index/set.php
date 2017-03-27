<?php

if ($__sgr !== 's') {
    Shield::abort(PANEL_404);
}

// Once a user created, this page will only visible for logged in user with status `1`
if (glob(ENGINE . DS . 'log' . DS . 'user' . DS . '*.page') && User::current('status') !== 1) {
    Shield::abort(PANEL_404);
}

if (Request::is('post')) {
    $__user_key = Request::post('_key', "", false);
    if (Request::post('_author', "", false) === "") {
        Message::error('void_field', $language->name, true);
    }
    if ($__user_key === "") {
        Message::error('void_field', $language->user, true);
    } else if (!preg_match('#^' . x(User::ID) . '[a-z\d-]+$#', $__user_key)) {
        Message::error('pattern_field', $language->user);
    }
    $f = ENGINE . DS . 'log' . DS . 'user' . DS . substr($__user_key, 1) . '.page';
    Hook::NS('on.user.enter', [$f]);
    if (!Message::$x) {
        Page::data([
            'author' => Request::post('_author', false),
            'type' => Request::post('_type', 'HTML'),
            'link' => Request::post('_link', false),
            'email' => Request::post('_email', false),
            'status' => Request::post('_status', 2),
            'content' => Request::post('_description', false)
        ])->saveTo($f, 0600);
        Message::success('create', $language->user . ' <em>' . $__user_key . '</em>');
        Request::save('post', '_key', $__user_key);
        Request::save('post', '_pass_x', true);
        Cookie::reset('Mecha\Panel.user.key');
        Cookie::reset('Mecha\Panel.user.token');
        Guardian::kick($__state->path . '/::g::/enter');
    } else {
        Request::save('post');
        Guardian::kick($url->current);
    }
}

Hook::set('shield.path', function($__path) use($site) {
    $s = Path::N($__path);
    if ($s === $site->is) {
        return PANEL . DS . 'lot' . DS . 'worker' . DS . $s . DS . Path::B(__FILE__);
    }
    return $__path;
});