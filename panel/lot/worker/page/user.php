<?php

require __DIR__ . DS . 'page.php';

// Hide the `art` tab
Config::set('panel.desk.body.tabs.art.hidden', true);

// Modify default page field(s)
Config::set('panel.desk.body.tabs', [
    'file' => [
        'fields' => [
            'page[title]' => null,
            'page[$]' => [
                'key' => 'name',
                'type' => 'text',
                'value' => $page->{'$'},
                'width' => true,
                'stack' => 10
            ],
            'page[email]' => [
                'key' => 'email',
                'type' => 'email',
                'value' => $page->email,
                'width' => true,
                'stack' => 10.1
            ],
            'page[description]' => [
                'stack' => 10.2
            ],
            'page[content]' => [
                'title' => 'Biography',
                'stack' => 10.3
            ],
            'page[type]' => [
                'stack' => 10.4
            ],
            'tags' => ['hidden' => true],
            'slug' => ['hidden' => true]
        ]
    ]
]);

if ($c === 'g') {
    Hook::set('on.ready', function() {
        Config::set('panel.nav.s.icon', [['M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M6,10V7H4V10H1V12H4V15H6V12H9V10M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12Z']]);
    });
}