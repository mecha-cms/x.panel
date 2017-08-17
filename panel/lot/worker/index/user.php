<?php

// Do not allow user to create page child(s)…
if ($__command === 's' && count($__chops) > 1) {
    if (isset($__chops[2]) && $__chops[2] === '+') {
        // But allow user to create custom field(s)…
    } else {
        Shield::abort(404);
    }
}

// Prevent user to commit suicide…
if (isset($__chops[1]) && $__chops[1] === $__user_key) {
    if ($__command === 'r') {
        Shield::abort(406);
    }
} else {
    Hook::set('panel.a.' . $__chops[0], function($__a) use($__user_key) {
        if (isset($__a['edit'][1]) && basename($__a['edit'][1]) === $__user_key) {
            unset($__a['reset']);
        }
        return $__a;
    }, 0);
}

// Preparation(s)…
Hook::set($__chops[0] . '.title', function($__title, $__lot) {
    return Page::apart(file_get_contents($__lot['path']), 'author', $__title);
}, 0);
Hook::set($__chops[0] . '.url', function($__url, $__lot) {
    return Page::apart(file_get_contents($__lot['path']), 'link', false);
}, 0);
Config::set('panel.v.' . $__chops[0] . '.as', $__user_key);
Config::set('panel.x.s.data', Config::get('panel.x.s.data') . ',email,pass,status,token');

// Replace `title` field with `author` field on user create event…
Hook::set('on.' . $__chops[0] . '.set', function($__f) use($language, $__command, $__path, $__state) {
    if (!file_exists(Path::F($__f) . DS . 'pass.data')) {
        $__f = Path::N($__f);
        User::reset();
        Request::save('post', 'user', '@' . $__f);
        Request::save('post', 'pass_x', 1);
        Guardian::kick($__state->path . '/::g::/enter');
    }
});

// Fix for disabled `status` field
if ($__is_post && Request::post('status', "") === "") {
    Request::set('post', 'status', $__user_status ?: 0);
}

// User key cannot be changed after created!
if ($__is_post) {
    Request::set('post', 'slug', basename($__path));
}

// Set custom panel view
Config::set('panel.view', 'page');

// Set or modify the default panel content(s)…
if (!$__is_has_step) {
    Hook::set('shield.enter', function() use($__user_key, $__user_status) {
        extract(Lot::get(null, []));
        $__u = $__page[0] ? $__page[0] : (object) [
            'email' => false,
            'link' => false,
            'state' => 'archive',
            'status' => 2
        ];
        $__x = $__u->state;
        $__o = (array) $language->o_user;
        if ($__user && $__user_status !== 1) {
            // Read only!
            $__o = ['.' . $__user_status => $__o[$__user_status]];
        }
        asort($__o);
        Config::set('panel', [
            'f' => [
                'page' => [
                    'author' => [
                        'placeholder' => $language->user,
                        'is' => [
                            'hidden' => false
                        ],
                        'attributes' => [
                            'data' => [
                                'slug-i' => 'author'
                            ]
                        ],
                        'stack' => 10
                    ],
                    '*slug' => [
                        'type' => 'text',
                        'placeholder' => To::slug($language->user),
                        'title' => $language->key,
                        'description' => $__command === 's' ? $language->h_user : null,
                        'attributes' => [
                            'data' => [
                                'slug-o' => 'author'
                            ],
                            'readonly' => $__command === 's' ? null : true
                        ],
                        'expand' => false,
                        'stack' => 20
                    ],
                    'content' => [
                        'placeholder' => $language->f_description($language->user),
                        'expand' => false,
                        'stack' => 30
                    ],
                    'type' => [
                        'stack' => 40
                    ],
                    'status' => [
                        'key' => 'status',
                        'type' => 'toggle',
                        'description' => $language->h_user_o,
                        'value' => $__u->status,
                        'values' => $__o,
                        'stack' => 50
                    ],
                    'email' => [
                        'is' => [
                            'hidden' => false
                        ],
                        'stack' => 60
                    ],
                    'link' => [
                        'stack' => 70
                    ],
                    'x' => [
                        'values' => [
                            '*' . $__x => $__command === 's' ? false : $language->update,
                            'page' => $__x === 'page' ? false : $language->create,
                            'draft' => $__x === 'draft' || isset($__chops[1]) && $__chops[1] === $__user_key ? false : $language->save,
                            'trash' => $__command === 's' || isset($__chops[1]) && $__chops[1] === $__user_key ? false : $language->delete,
                            'archive' => false
                        ],
                        'order' => ['*' . $__x, 'page', 'draft', 'trash']
                    ],
                    '+[time]' => false,
                    'description' => false,
                    'tags' => false,
                    'title' => false
                ]
            ],
            'm' => [
                't' => [
                    'page' => [
                        'title' => $language->user
                    ]
                ]
            ],
            's' => [
                1 => [
                    'kin' => substr($url->path, -2) === '/+' || strpos($url->path, '/+/') !== false ? [] : [
                        'title' => $language->users,
                        'stack' => 10
                    ],
                    'author' => false,
                    'current' => false,
                    'parent' => false,
                    'setting' => false
                ],
                2 => [
                    'child' => false,
                    'id' => false
                ]
            ],
            'x' => [
                's' => [
                    'child' => true,
                    'current' => true,
                    'parent' => true
                ]
            ]
        ]);
    }, 0);
}