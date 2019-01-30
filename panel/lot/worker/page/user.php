<?php

if ($c === 'g' && !$panel->file || HTTP::get('view') === 'file') {
    return;
}

require __DIR__ . DS . 'page.php';

// Disable page children feature
if ($c === 's' && $chops && HTTP::get('view', $panel->view) !== 'data') {
    Config::set('panel.error', true);
}

// Hide the `art` and `image` tab
Config::set('panel.desk.body.tab.art.hidden', true);
Config::set('panel.desk.body.tab.image.hidden', true);

// Modify default page field(s)
$pass = $user->pass;
$email = l($language->email) . '@' . $url->host;
Config::set('panel.+.slug', ['page[$]:slug']);
Config::set('panel.desk.body.tab', [
    'file' => [
        'field' => [
            'page[title]' => null,
            'page[description]' => null,
            'page[author]' => null,
            'page[$]' => [
                'key' => 'name',
                'type' => 'text',
                'value' => $c === 's' ? null : $page->{'$'},
                'placeholder' => $c === 's' ? $language->field_hint_page_author : $page->{'$'} ?: $language->field_hint_page_author,
                'width' => true,
                'stack' => 10
            ],
            'slug' => [
                'key' => 'key',
                'type' => $c === 'g' ? 'hidden' : 'text',
                'placeholder' => $c === 's' ? strtr($language->field_hint_key, '_', '-') : null,
                'description' => $language->field_description_key_user,
                'stack' => 10.1
            ],
            '*data[pass]' => $c === 's' || !$pass ? [
                'key' => 'pass',
                'type' => 'pass',
                'width' => true,
                'stack' => 10.2
            ] : null,
            'page[email]' => [
                'key' => 'email',
                'type' => 'email',
                'value' => $page->email,
                'placeholder' => $c === 's' ? $email : $page->email ?: $email,
                'width' => true,
                'stack' => 10.3
            ],
            'page[content]' => [
                'placeholder' => $language->field_hint_page_description__($language->user),
                'stack' => 10.4
            ],
            'page[type]' => ['stack' => 10.5],
            'page[status]' => $pass ? null : [
                'key' => 'status',
                'type' => 'hidden',
                'value' => 1,
                'stack' => 0
            ],
            'tags' => ['hidden' => true]
        ]
    ],
    'status' => $pass ? [
        'field' => [
            'page[status]' => [
                'key' => 'status',
                'type' => 'radio[]',
                'block' => true,
                'value' => $c === 's' ? 2 : $page->status,
                'stack' => 10
            ]
        ],
        'stack' => 10.09
    ] : null
]);

if ($pass) {
    // Obfuscate pass value
    if (strpos($pass, X) !== 0) {
        File::put(X . password_hash($pass . ' ' . $user->slug, PASSWORD_DEFAULT))->saveTo(Path::F($user->path) . DS . 'pass.data', 0600);
    }
    Hook::set('on.ready', function() use($file, $language, $user) {
        $status = (array) $language->o_page_status;
        Config::set('panel.desk.body.tab.status.field.page[status].values', $user->status === 1 && !Is::user(Path::N($file)) ? $status : [
            $user->status => $status[$user->status]
        ]);
    }, .1);
}

if ($c === 'g') {
    if (Path::N($path) === $user->slug) {
        Config::reset([
            'panel.desk.footer.tool.archive',
            'panel.desk.footer.tool.draft',
            'panel.desk.footer.tool.trash'
        ]);
    }
} else {
    Config::set('panel.desk.footer.tool.page.title', $language->create);
}