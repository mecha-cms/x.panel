<?php

if ($__user) {

    // The common user role: create/update their own user data
    $__fn = function() use($__chops, $__user_key) {
        return isset($__chops[1]) && $__chops[1] === $__user_key;
    };

    // Default feature(s) for each user(s)…
    $__roles = (array) a(Config::get('panel.v.user', []));
    $__roles = array_replace_recursive([
        // `@banned` → Cannot access any.
       -1 => false,
        // `@pending` → Cannot acces any but their account.
        0 => [
            'user' => $__fn
        ],
        // `@primary` → Can access any.
        1 => true,
        // `@secondary` → Cannot access any but `user`, `session`, `comment`, and `page` with limitation…
        2 => [
            'comment' => true,
            'page' => function() use($__command, $__path) {
                // [1]. Cannot change the page key/slug
                if ($__command !== 's') {
                    Config::set('panel.f.page', [
                        'key' => [
                            'attributes' => [
                                'readonly' => true
                            ]
                        ],
                        'slug' => [
                            'attributes' => [
                                'readonly' => true
                            ]
                        ]
                    ]);
                    Request::set('post', 'slug', basename($__path));
                }
                // [2]. Cannot delete the page
                Hook::set('panel.a.page', function($__a) {
                    unset($__a['reset']);
                    return $__a;
                }, 0);
                Config::set('panel.f.page.x.values.trash', false);
                return $__command !== 'r';
            },
            'session' => true,
            'user' => $__fn
        ],
        // `@member` → Cannot access any but their account.
        3 => [
            'user' => $__fn
        ]
    ], $__roles);

    // Restore for external usage!
    Config::set('panel.v.user', $__roles);

    if (isset($__roles[$__user_status])) {
        $__role = $__roles[$__user_status];
        if ($__role === false) {
            User::reset($__user_key);
            Shield::abort(403);
        } else if (is_array($__role)) {
            foreach ((array) a(Config::get('panel.v.n', [])) as $__k => $__v) {
                if (!isset($__role[$__k]) || !(is_callable($__role[$__k]) ? $__role[$__k]($__user) : $__role[$__k])) {
                    Config::set('panel.v.n.' . $__k, false);
                }
            }
            if (isset($__role[$__chops[0]])) {
                $__if = is_callable($__role[$__chops[0]]) ? $__role[$__chops[0]]($__user) : $__role[$__chops[0]];
                if (!$__if) {
                    Shield::abort(403);
                }
            } else if ($__chops[0] !== 'enter' && $__chops[0] !== 'exit') {
                Shield::abort(403);
            }
        } else if ($__role === true) {
            // Do nothing!
        }
    }

}