<?php

// Preparation(s)…
Hook::set($__chops[0] . '.title', function($__content, $__lot) use($__chops) {
    $__s = Page::apart(file_get_contents($__lot['path']));
    return isset($__s['author']) ? $__s['author'] : (isset($__s['title']) ? $__s['title'] : "");
});
Config::set('panel.x.s.data', Config::get('panel.x.s.data') . ',email,pass,status,token');

// Replace `title` field with `author` field on user create event…
Hook::set('on.' . $__chops[0] . '.set', function($__f) use($language, $__action, $__path, $__state) {
    Message::reset();
    Message::success($__action === 's' ? 'create' : 'update', [$language->user, '<strong>' . Request::post('author') . '</strong>']);
    if (!file_exists(Path::F($__f) . DS . 'pass.data')) {
        $__f = Path::N($__f);
        User::reset();
        Request::save('post', 'user', '@' . $__f);
        Request::save('post', 'pass_x', 1);
        Guardian::kick($__state->path . '/::g::/enter');
    }
});

// Load the main task(s)…
require __DIR__ . DS . '..' . DS . 'worker' . DS . 'page.php';

// Do not allow user to create page child(s)…
if ($__f && $__action === 's') {
    Shield::abort(PANEL_404);
}

// Set or modify the default panel content(s)…
$__u = $__page[0] ? $__page[0] : (object) [
    'email' => null,
    'link' => null,
    'state' => 'page',
    'status' => 1
];
$__x = $__u->state;
$__o = (array) $language->o_user;
$__z = !g(LOT . DS . $__path, 'page', "", false) && User::get('status') !== 1 ? '.' : "";
Config::set('panel.m.t.page.title', $language->user);
Config::set('panel.f.page', [
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
        'description' => $__action === 's' ? $language->h_user : null,
        'attributes' => [
            'data' => [
                'slug-o' => 'author'
            ],
            'readonly' => $__action === 's' ? null : true
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
    'email' => [
        'is' => [
            'hidden' => false
        ],
        'stack' => 50
    ],
    'link' => [
        'stack' => 60
    ],
    'status' => [
        'key' => 'status',
        'type' => 'toggle',
        'value' => $__u->status,
        'values' => [
            $__z . '-1' => $__o[-1],
            (User::get() ? $__z : "") . '1' => $__o[1],
            $__z . '2' => $__o[2]
        ],
        'stack' => 70
    ],
    'x' => [
        'values' => [
            '*' . $__x => $__action === 's' ? null : $language->update,
            'page' => $__x === 'page' ? null : $language->create,
            'draft' => $__x === 'draft' ? null : $language->save,
            'archive' => null
        ],
        'order' => ['*' . $__x, 'page', 'draft', 'trash']
    ],
    '+[time]' => null,
    'description' => null,
    'tags' => null,
    'title' => null
]);

Config::set('panel.s', [
    1 => [
        'source' => [
            'stack' => 10
        ],
        'kin' => [
            'title' => $language->users,
            'stack' => 20
        ],
        'author' => null,
        'current' => null,
        'parent' => null,
        'setting' => null
    ],
    2 => [
        'child' => null,
        'id' => null
    ]
]);