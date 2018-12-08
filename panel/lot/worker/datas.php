<?php

// Disable page children feature
Config::set('panel.error', !!$chops);

$c = $panel->c;
$is_file = is_file($file) ? mime_content_type($file) : "";

Config::set('panel.desk', [
    'header' => [
        'tool' => [
            'file' => [
                'title' => $language->{str_replace('.', "\\.", $id)},
                'icon' => [['M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z']],
                'c' => 's',
                'query' => [
                    'q' => false,
                    'tab' => ['file'],
                    'tabs' => ['false'],
                    'token' => false
                ],
                'stack' => 10
            ],
            'folder' => null,
            '+' => null
        ]
    ],
    'body' => [
        'tab' => [
            'file' => [
                'title' => $language->datas,
                'explore' => $file,
                'stack' => 10
            ]
        ]
    ],
    'footer' => [
        'pager' => true
    ]
]);