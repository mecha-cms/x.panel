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
Config::set('panel.desk.body.tab.file.field', [
    'page[content]' => null,
    'tags' => ['hidden' => true]
]);

Hook::set('on.ready', function() use($c, $page) {
    if ($c === 's' || $page->state === 'draft') {
        $i = Get::tags(TAG, 'page,archive', [-1, 'id'], 'id')->first();
        $i += 1;
    } else {
        $i = $page->id;
    }
    Config::set('panel.desk.body.tab.data.field', [
        'page[link]' => null,
        '!data[id]' => [
            'key' => 'id',
            'type' => 'text',
            'value' => $i,
            'stack' => 10
        ],
        'data[time]' => ['type' => 'hidden']
    ]);
}, .1);

if ($c === 'g') {
    Hook::set('on.ready', function() {
        Config::set('panel.nav.s.icon', [['M21.41,11.58L12.41,2.58C12.04,2.21 11.53,2 11,2H4A2,2 0 0,0 2,4V11C2,11.53 2.21,12.04 2.59,12.41L3,12.81C3.9,12.27 4.94,12 6,12A6,6 0 0,1 12,18C12,19.06 11.72,20.09 11.18,21L11.58,21.4C11.95,21.78 12.47,22 13,22C13.53,22 14.04,21.79 14.41,21.41L21.41,14.41C21.79,14.04 22,13.53 22,13C22,12.47 21.79,11.96 21.41,11.58M5.5,7A1.5,1.5 0 0,1 4,5.5A1.5,1.5 0 0,1 5.5,4A1.5,1.5 0 0,1 7,5.5A1.5,1.5 0 0,1 5.5,7M10,19H7V22H5V19H2V17H5V14H7V17H10V19Z']]);
    }, .2);
} else {
    Config::set('panel.desk.footer.tool.page.title', $language->create);
}