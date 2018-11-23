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
Config::set('panel.+.slug', ['page[$]:slug']);
Config::set('panel.desk.body.tab', [
    'file' => [
        'field' => [
            'page[title]' => null,
            'page[description]' => null,
            'page[$]' => [
                'key' => 'name',
                'type' => 'text',
                'value' => $page->{'$'},
                'placeholder' => $c === 's' ? $language->field_hint_page_title : null,
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
            '*data[pass]' => $c === 's' ? [
                'key' => 'pass',
                'type' => 'pass',
                'width' => true,
                'stack' => 10.2
            ] : null,
            'page[email]' => [
                'key' => 'email',
                'type' => 'email',
                'value' => $page->email,
                'placeholder' => $c === 's' ? l($language->email) . '@' . $url->host : null,
                'width' => true,
                'stack' => 10.3
            ],
            'page[content]' => ['stack' => 10.4],
            'page[type]' => ['stack' => 10.5],
            'tags' => ['hidden' => true]
        ]
    ],
    'status' => [
        'field' => [
            'page[status]' => [
                'key' => 'status',
                'type' => 'radio[]',
                'value' => $c === 's' ? 2 : $page->status,
                'view' => 'block',
                'stack' => 10
            ]
        ],
        'stack' => 10.09
    ]
]);

Hook::set('on.ready', function() use($language, $user) {
    $status = (array) $language->o_page_status;
    Config::set('panel.desk.body.tab.status.field.page[status].values', $user->status === 1 ? $status : [
        $user->status => $status[$user->status]
    ]);
}, .1);

if ($c === 'g') {
    Hook::set('on.ready', function() {
        Config::set('panel.nav.s.icon', [['M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M6,10V7H4V10H1V12H4V15H6V12H9V10M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12Z']]);
    }, .1);
    if (Path::N($path) === $user->slug) {
        Config::reset('panel.desk.footer.tool.draft');
        Config::reset('panel.desk.footer.tool.archive');
        Config::reset('panel.desk.footer.tool.trash');
    }
}