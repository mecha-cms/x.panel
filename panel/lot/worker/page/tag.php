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

Config::set('panel.desk.footer.tool.page.title', $language->create);

Hook::set('on.ready', function() use($c, $page) {
    if ($c === 'g') {
        Config::set('panel.nav.s.icon', [['M5.5,7A1.5,1.5 0 0,1 4,5.5A1.5,1.5 0 0,1 5.5,4A1.5,1.5 0 0,1 7,5.5A1.5,1.5 0 0,1 5.5,7M21.41,11.58L12.41,2.58C12.05,2.22 11.55,2 11,2H4C2.89,2 2,2.89 2,4V11C2,11.55 2.22,12.05 2.59,12.41L11.58,21.41C11.95,21.77 12.45,22 13,22C13.55,22 14.05,21.77 14.41,21.41L21.41,14.41C21.78,14.05 22,13.55 22,13C22,12.44 21.77,11.94 21.41,11.58Z']]); // TODO
    }
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