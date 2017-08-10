<?php

// Do not allow user to create page child(s)…
if ($__command === 's' && count($__chops) > 1) {
    if (isset($__chops[2]) && $__chops[2] === '+') {
        // But allow user to create custom field(s)…
    } else {
        Shield::abort(PANEL_404);
    }
}

// Preparation(s)…
Hook::set($__chops[0] . '.title', function($__content, $__lot) use($__chops) {
    $__s = Page::apart(file_get_contents($__lot['path']));
    return isset($__s['author']) ? $__s['author'] : (isset($__s['title']) ? $__s['title'] : "");
}, 0);
Hook::set($__chops[0] . '.url', function($__content, $__lot) use($__chops) {
    $__s = Page::apart(file_get_contents($__lot['path']));
    return isset($__s['link']) ? $__s['link'] : false;
}, 0);
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

// Redirect to the log in page if all user(s) were deleted!
Hook::set('on.' . $__chops[0] . '.reset', function() use($__chops, $__state) {
    if (!g(LOT . DS . $__chops[0], 'page', "", false)) {
        User::reset();
        Guardian::kick($__state->path . '/::g::/enter');
    }
});

// Set custom file manager layout
Config::set('panel.l', 'page');

// Set or modify the default panel content(s)…
Hook::set('shield.enter', function() {
    extract(Lot::get(null, []));
    $__u = $__page[0] ? $__page[0] : (object) [
        'email' => false,
        'link' => false,
        'state' => 'archive',
        'status' => 2
    ];
    $__x = $__u->state;
    $__o = (array) $language->o_user;
    $__user = User::get();
    if ($__user && $__user->status !== 1) {
        // Read only!
        $__o = ['.' . $__user->status => $__o[$__user->status]];
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
                        'draft' => $__x === 'draft' ? false : $language->save,
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