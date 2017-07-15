<?php

// Preparation(s)…
Hook::set($__chops[0] . '.title', function($__content, $__lot) use($__chops) {
    $__s = Page::apart(file_get_contents($__lot['path']));
    return isset($__s['author']) ? $__s['author'] : (isset($__s['title']) ? $__s['title'] : "");
});

// Replace `title` field with `author` field on user create event…
Hook::set('on.' . $__chops[0] . '.set', function($__f) use($__path, $__state) {
    if (!file_exists(Path::F($__f . DS . 'pass.data'))) {
        $__f = Path::N($__f);
        User::reset($__f);
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

// Do not allow user to create page child(s)…
if ($__f && $__action === 's') {
    Shield::abort(PANEL_404);
}

// Set or modify the default panel content(s)…
$__u = $__page[0] ? $__page[0] : (object) [
    'email' => null,
    'link' => null,
    'state' => 'page',
    'status' => 2
];
$__x = $__u->state;
$__o = (array) $language->o_user;
$__z = !g(LOT . DS . $__path, 'page') && User::get('status') !== 1 ? '.' : "";
Config::set('panel.m.t.page.title', $language->user);
Config::set('panel.m.t.page.content', [
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
        'description' => $language->h_user,
        'attributes' => [
            'data' => [
                'slug-o' => 'author'
            ]
        ],
        'expand' => false,
        'stack' => 20
    ],
    'content' => [
        'placeholder' => $language->f_description($language->user),
        'expand' => false,
        'stack' => 30
    ],
    'email' => [
        'stack' => 40
    ],
    'link' => [
        'stack' => 50
    ],
    '+[status]' => [
        'key' => 'status',
        'type' => 'toggle',
        'value' => $__z ? 1 : $__u->status,
        'values' => [
            $__z . '-1' => $__o[-1],
            (User::get() ? $__z : "") . '1' => $__o[1],
            $__z . '2' => $__o[2]
        ],
        'stack' => 60
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

Config::set('panel.x.s.data', Config::get('panel.x.s.data') . ',email,pass,status,token');