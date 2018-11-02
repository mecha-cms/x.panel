<?php

require __DIR__ . DS . 'files.php';

Config::reset('panel.desk.header.tools.folder');
Config::set('panel.desk.header.tools', [
    'file' => [
        'title' => $language->{$id},
        'query' => [
            'tab' => false
        ]
    ],
    '+' => [
        '+' => [
            'blob' => [
                'query' => ['view' => 'file']
            ]
        ]
    ]
]);

Config::set('panel.$.page.tools.r.query.view', 'page');