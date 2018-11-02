<?php

require __DIR__ . DS . 'file.php';

// Remove folder and blob tab(s)
Config::reset('panel.desk.body.tabs.folder');
Config::reset('panel.desk.body.tabs.blob');

Config::set('panel.desk.body.tabs.file.fields', [
    'name' => [
        'key' => 'key',
        'value' => $c === 'g' ? Path::N($path) : null,
        'pattern' => '^-?[a-z\\d]+(-[a-z\\d]+)*$'
    ],
    'x' => [
        'type' => 'hidden',
        'value' => 'data',
        'stack' => 0
    ]
]);

// Modify back menu destination
if ($x = HTTP::get('x')) {
    Hook::set('on.ready', function() use($panel, $x) {
        $path = rtrim($panel->id . '/' . $panel->path, '/') . '.' . $x;
        if (file_exists(LOT . DS . $path)) {
            Config::set('panel.nav.lot', [
                'c' => 'g',
                'path' => $path,
                'query' => [
                    'tab' => ['data'],
                    'view' => false,
                    'x' => false
                ]
            ]);
        }
    });
}