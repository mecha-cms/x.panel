<?php

if ($__action !== 's') {
    Shield::abort(PANEL_404);
}

// Once a user created, this page will be visible only for logged in user(s) with status `1`
if (g(USER, 'page') && User::current('status') !== 1) {
    Shield::abort(PANEL_404);
}

Config::set([
    'is' => 'page',
    'panel' => [
        'layout' => 2,
        'c:f' => 'editor',
        'm' => [
            't' => [
                'user' => [
                    'stack' => 10
                ]
            ]
        ]
    ]
]);

Hook::set('__user.url', function($__content) use($__state, $__chops) {
    return $__state->path . '/::g::/' . $__chops[0] . '/' . Path::N($__content);
});

Hook::set('user.title', function($__content, $__lot) {
    return (isset($__lot['author']) ? $__lot['author'] : null);
});

if (Request::is('post')) {
    $__user_key = Request::post('user', "", false);
    if (Request::post('author', "", false) === "") {
        Message::error('void_field', $language->name, true);
    }
    if ($__user_key === "") {
        Message::error('void_field', $language->user, true);
    } else if (!preg_match('#^' . x(User::ID) . '[a-z\d-]+$#', $__user_key)) {
        Message::error('pattern_field', $language->user);
    }
    $f = USER . DS . substr($__user_key, 1) . '.page';
    if (file_exists($f)) {
        Message::error('exist', [$language->user, '<em>' . $__user_key . '</em>']);
    }
    Hook::NS('on.user.enter', [$f]);
    if (!Message::$x) {
        Page::data([
            'author' => Request::post('author', false),
            'type' => Request::post('type', 'HTML'),
            'link' => Request::post('link', false),
            'email' => Request::post('email', false),
            'status' => Request::post('status', 2),
            'content' => Request::post('description', false)
        ])->saveTo($f, 0600);
        Message::success('create', $language->user . ' <em>' . $__user_key . '</em>');
        Request::save('post', 'user', $__user_key);
        Request::save('post', 'pass_x', true);
        Cookie::reset('panel.c.user.key');
        Cookie::reset('panel.c.user.token');
        Guardian::kick($__state->path . '/::g::/enter');
    } else {
        Request::save('post');
        Guardian::kick($url->current);
    }
}

if ($__files = g(USER, 'page')) {
    foreach ($__files as $__v) {
        $__v = Path::N($__v);
        $__kins[0][] = new User($__v, [], '__user');
        $__kins[1][] = new User($__v, []);
    }
}

$__is_has_step_kin = count($__files) > $__chunk ? true : false;

Lot::set([
    '__kins' => $__kins,
    '__is_has_step_kin' => $__is_has_step_kin
]);