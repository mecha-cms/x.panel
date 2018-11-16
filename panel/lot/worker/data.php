<?php

require __DIR__ . DS . 'file.php';

$file = $panel->file ?: $panel->folder;
if ($c === 's' && is_file($file)) {
    Guardian::kick(str_replace('::s::', '::g::', $url->current . $url->query));
}

// Remove folder and blob tab(s)
Config::reset('panel.desk.body.tab.folder');
Config::reset('panel.desk.body.tab.blob');

Config::set('panel.desk.body.tab.file.field', [
    'name' => [
        'key' => 'key',
        'value' => $c === 'g' ? Path::N($file) : null,
        'placeholder' => $c === 's' ? strtr($language->field_hint_key, '_', '-') : null,
        'pattern' => '^-?[a-z\\d]+(-[a-z\\d]+)*$'
    ],
    'x' => [
        'type' => 'hidden',
        'value' => 'data',
        'stack' => 0
    ],
    'file[consent]' => [
        'type' => 'hidden',
        'value' => '0600'
    ]
]);

// Modify back menu destination
if ($x = HTTP::get('x')) {
    Hook::set('on.ready', function() use($c, $file, $language, $panel, $x) {
        if ($c === 'g') {
            Config::set('panel.nav.s', [
                'description' => $language->new__($language->data, true),
                'icon' => [['M2,16H10V14H2M18,14V10H16V14H12V16H16V20H18V16H22V14M14,6H2V8H14M14,10H2V12H14V10Z']]
            ]);
            $file = dirname($file);
        }
        $file .= '.' . $x;
        if (file_exists($file)) {
            Config::set('panel.nav.lot', [
                'c' => 'g',
                'path' => Path::R($file, LOT, '/'),
                'query' => [
                    'tab' => ['data'],
                    'view' => false,
                    'x' => false
                ]
            ]);
        }
    }, .1);
}