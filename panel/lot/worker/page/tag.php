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
        $i = Get::tags(TAG, 'page,archive', [-1, 'id'])->first();
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

if ($c === 's') {
    Config::set('panel.desk.footer.tool.page.title', $language->create);
}