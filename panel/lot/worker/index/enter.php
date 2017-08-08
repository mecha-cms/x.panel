<?php

if ($__command !== 'g') {
    Shield::abort(PANEL_404);
}

if ($__user_enter) {
    Guardian::kick($__state->path . '/::g::/' . $__state->kick('page'));
}

$__pass_x = Request::restore('post', 'pass_x');
Config::set([
    'is' => 'page',
    'panel' => [
        'layout' => 0,
        'c:f' => true,
        'm:f' => false,
        'm' => [
            't' => [
                'enter' => [
                    'legend' => $language->log_in,
                    'list' => [
                        'user' => [
                            'type' => 'text',
                            'placeholder' => $language->f_user,
                            'is' => [
                                'block' => true
                            ],
                            'stack' => 10
                        ],
                        'pass' => [
                            'type' => 'pass',
                            'placeholder' => $__pass_x ? l($language->new__($language->pass)) : null,
                            'is' => [
                                'block' => true
                            ],
                            'stack' => 20
                        ],
                        'kick' => [
                            'type' => 'hidden',
                            'value' => Request::get('f.kick', $__state->path . '/::g::/' . $__state->kick('page')),
                            'stack' => 30
                        ],
                        'x' => [
                            'key' => 'submit',
                            'type' => 'submit[]',
                            'values' => [
                                'page' => $language->enter
                            ],
                            'stack' => 0
                        ]
                    ],
                    'stack' => 10
                ],
                'file' => false,
                'folder' => false,
                'upload' => false
            ]
        ]
    ]
]);

if ($__is_post && !Message::$x) {
    $__user_key = Request::post('user');
    $__user_pass = Request::post('pass');
    $__user_token = Request::post('token');
    if (strpos($__user_key, '@') === 0) {
        $__user_key = substr($__user_key, 1); // remove the `@`
    }
    $f = USER . DS . $__user_key;
    if (!$__user_key) {
        Message::error('void_field', $language->user, true);
    } else if (!$__user_pass) {
        Message::error('void_field', $language->pass, true);
    } else if (file_exists($f . '.page')) {
        if (!file_exists($f . DS . 'pass.data')) {
            // Reset password by deleting `pass.data` manually, then log in!
            File::write(X . password_hash($__user_pass . ' ' . $__user_key, PASSWORD_DEFAULT))->saveTo($f . DS . 'pass.data');
            Message::success('create', $language->pass);
            Message::info('is', [$language->pass, '<em>' . $__user_pass . '</em>']);
        }
        $__pass = File::open($f . DS . 'pass.data')->get(0, "");
        if (strpos($__pass, X) === 0) {
            $__pass = substr($__pass, 1);
        } else {
            // TODO: (plain password)
        }
        if (password_verify($__user_pass . ' ' . $__user_key, $__pass)) {
            File::write($__user_token)->saveTo($f . DS . 'token.data');
            User::set($__user_key, $__user_token);
            Message::success('user_enter');
            Hook::fire('on.user.enter');
            Guardian::kick(Request::post('kick', ""));
        } else {
            Message::error('user_or_pass');
        }
    } else {
        Message::error('user_or_pass');
    }
    if (Message::$x) {
        Request::save('post', 'user', '@' . $__user_key);
    }
}