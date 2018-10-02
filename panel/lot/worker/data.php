<?php

require __DIR__ . DS . 'file.php';

// TODO
$panel->view = $panel->v = ($view = 'data');

Session::set('panel.view', $view);

// Remove folder and blob tab(s)
Config::reset('panel.desk.body.tabs.folder');
Config::reset('panel.desk.body.tabs.blob');

Config::set('panel.desk.body.tabs.file.fields', [
    'name' => [
        'key' => 'key',
        'value' => $c === 'g' ? Path::N($path) : null,
        'pattern' => '^[a-z\\d]+(-[a-z\\d]+)*$'
    ],
    'x' => [
        'type' => 'hidden',
        'value' => 'data',
        'stack' => 0
    ]
]);