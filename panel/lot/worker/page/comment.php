<?php

require __DIR__ . DS . 'page.php';

// Hide the `art` tab
Config::set('panel.desk.body.tabs.art.hidden', true);

// Modify default page field(s)
$parent = HTTP::get('f.data.parent', "", false);
Config::set('panel.desk.body.tabs', [
    'file' => [
        'fields' => [
            'page[title]' => null,
            'page[description]' => null,
            'page[author]' => [
                'key' => 'name',
                'type' => $parent ? 'hidden' : 'text',
                'width' => true,
                'stack' => 10
            ],
            'page[email]' => [
                'key' => 'email',
                'type' => $parent ? 'hidden' : 'email',
                'value' => $page->email,
                'width' => true,
                'stack' => 10.3
            ],
            'page[content]' => [
                'key' => $parent ? 'reply' : 'comment',
                'stack' => 10.4
            ],
            'page[type]' => ['stack' => 10.5],
            'page[status]' => [
                'type' => 'hidden',
                'value' => 1 // User that can reply from control panel is always administrator
            ],
            'data[parent]' => [
                'type' => 'hidden',
                'value' => $page->parent
            ],
            'slug' => ['hidden' => true],
            'tags' => ['hidden' => true]
        ]
    ]
]);

Hook::set('on.ready', function() use($user) {
    Config::set('panel.desk.body.tabs.file.fields.page[author].value', $user->key);
});