<?php

require __DIR__ . DS . 'files.php';

Config::set('panel.desk.header.tool', [
    'previous' => $chops ? [
        'title' => false,
        'description' => $language->do_up_directory,
        'icon' => [['M13,20H11V8L5.5,13.5L4.08,12.08L12,4.16L19.92,12.08L18.5,13.5L13,8V20Z']],
        'path' => Path::R(dirname($file), LOT, '/') . '/1',
        'stack' => 9.9
    ] : null,
    'file' => [
        'title' => $language->{str_replace('.', "\\.", $id)},
        'query' => [
            'tab' => false,
            'tabs' => false
        ]
    ],
    'folder' => null,
    '+' => [
        '+' => [
            'blob' => [
                'query' => ['view' => 'file']
            ]
        ]
    ]
]);

// Force `view` value to `page`
Config::set('panel.+.page.tool.r.query.view', 'page');