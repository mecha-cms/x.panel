<?php

if ($__command !== 'g') {
    Shield::abort(404);
}

if ($__user_enter) {
    Guardian::kick($__state->path . '/::g::/' . $__state->kick('page'));
}

$__g = g(USER, 'page');
$__pass_x = Request::restore('post', 'pass_x');
$__user_x = $__pass_x || !$__g;
Config::set([
    'is' => 'page',
    'panel' => [
        'layout' => 0,
        'c:f' => true,
        'm:f' => false,
        'm' => [
            't' => [
                'enter' => [
                    'legend' => $__user_x ? $language->new__($language->user, true) : $language->log_in,
                    'list' => [
                        'user' => [
                            'type' => 'text',
                            'placeholder' => $language->f_user,
                            'width' => true,
                            'attributes' => [
                                'autofocus' => $__pass_x ? null : true
                            ],
                            'stack' => 10
                        ],
                        'pass' => [
                            'type' => 'pass',
                            'placeholder' => $__user_x ? l($language->new__($language->pass)) : null,
                            'width' => true,
                            'attributes' => [
                                'autofocus' => $__pass_x ? true : null
                            ],
                            'stack' => 20
                        ],
                        'kick' => [
                            'type' => 'hidden',
                            // Need to get the `$_GET` value manually, because `hidden` value(s)
                            // shouldn’t be accessible through URL query string!
                            'value' => Request::get('f.kick', $__state->path . '/::g::/' . $__state->kick('page')),
                            'stack' => 30
                        ],
                        'x' => [
                            'key' => 'submit',
                            'type' => 'submit[]',
                            'values' => [
                                'page' => $language->{$__user_x ? 'create' : 'enter'}
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
    $__user_key = Request::post('user', "", false);
    $__user_pass = Request::post('pass', "", false);
    $__user_token = Request::post('token', "", false);
    if (strpos($__user_key, '@') === 0) {
        $__user_key = substr($__user_key, 1); // remove the `@`
    }
    $__f = USER . DS . $__user_key;
    if (!$__user_key) {
        Message::error('void_field', $language->user, true);
    } else if (!$__user_pass) {
        Message::error('void_field', $language->pass, true);
    } else if (!$__g) {
        Page::data([
            'author' => '@' . $__user_key,
            'status' => 1
        ])->saveTo($__f . '.page', 0600);
        File::write($__user_token)->saveTo($__f . DS . 'token.data', 0600);
        File::write(X . password_hash($__user_pass . ' ' . $__user_key, PASSWORD_DEFAULT))->saveTo($__f . DS . 'pass.data', 0600);
        User::set($__user_key, $__user_token);
        Hook::fire('on.user.set', [$__f . '.page', false]);
        Message::success('create', [$language->user, '<em>@' . $__user_key . '</em>']);
        Message::success('create', [$language->pass, '<em>' . $__user_pass . '</em>']);
        Guardian::kick($__state->path . '/::g::/user/' . $__user_key);
    } else if (file_exists($__f . '.page')) {
        $__1 = false;
        if (!file_exists($__f . DS . 'pass.data')) {
            // Reset password by deleting `pass.data` manually, then log in!
            File::write(X . password_hash($__user_pass . ' ' . $__user_key, PASSWORD_DEFAULT))->saveTo($__f . DS . 'pass.data', 0600);
            Message::success('create', [$language->pass, '<em>' . $__user_pass . '</em>']);
            $__1 = true;
        }
        $__pass = File::open($__f . DS . 'pass.data')->get(0, "");
        if (strpos($__pass, X) === 0) {
            $__pass = substr($__pass, 1);
        } else {
            // TODO: (plain password)
        }
        if (password_verify($__user_pass . ' ' . $__user_key, $__pass)) {
            File::write($__user_token)->saveTo($__f . DS . 'token.data', 0600);
            User::set($__user_key, $__user_token);
            $__ff = $__f . '.page';
            if (!$__1) {
                $__s = (new Page($__ff, [], ['*', 'user']))->status;
                // `@pending` or `@member`, redirect to user manager!
                $__1 = $__s === 0 || $__s === 3;
            }
            Hook::fire('on.user.enter', [$__ff, $__1 ? null : $__ff]);
            Message::success('user_enter');
            Guardian::kick($__1 ? $__state->path . '/::g::/user/' . $__user_key : Request::post('kick', ""));
        } else {
            Message::error('user_or_pass');
        }
    } else {
        Message::error('user_or_pass');
    }
    if (Message::$x && $__s = ltrim($__user_key, '@')) {
        Request::save('post', 'user', '@' . $__s);
        Guardian::kick($url->current . HTTP::query());
    }
}