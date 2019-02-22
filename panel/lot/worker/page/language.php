<?php

if ($c === 'g' && !$panel->file || HTTP::get('view') === 'file') {
    return;
}

require __DIR__ . DS . 'page.php';

// Disable page children feature
if ($c === 's' && $chops && (HTTP::get('view') ?? $panel->view) !== 'data') {
    Config::set('panel.error', true);
}

// Hide the `art` and `image` tab
Config::set('panel.desk.body.tab.art.hidden', true);
Config::set('panel.desk.body.tab.image.hidden', true);

// Hide `archive` button
Config::set('panel.desk.footer.tool.archive.hidden', true);

// Modify default page field(s)
$default = new Page(LANGUAGE . DS . 'en-us.page', [], false);
Config::set('panel.desk.body.tab.file.field', [
    'page[content]' => [
        'syntax' => 'text/x-yaml',
        'value' => $c === 's' ? $default->content : $page->content
    ],
    'page[type]' => [
        'type' => 'hidden',
        'value' => 'YAML'
    ],
    'slug' => [
        'key' => 'key',
        'placeholder' => strtr($language->field_hint_key, '_', '-')
    ],
    'tags' => ['hidden' => true]
]);

Config::set('panel.desk.body.tab.data.field', [
    'page[version]' => [
        'key' => 'version',
        'type' => 'text',
        'pattern' => '^\\d+(\\.\\d+)*$',
        'value' => $c === 's' ? '0.0.1' : $page->version,
        'stack' => 10
    ],
    'data[time]' => null,
    '!:' => ['hidden' => true],
    '!+' => ['hidden' => true]
]);

// You should not delete the `en-us` language
if ($c === 'g' && $page->slug === 'en-us') {
    Config::set('panel.desk.footer.tool.trash.x', true);
}