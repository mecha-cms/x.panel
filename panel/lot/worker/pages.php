<?php

require __DIR__ . DS . 'files.php';

Config::set('panel.desk.header.tools', [
    'previous' => strpos($path, '/') !== false ? [
        'title' => false,
        'description' => 'Go to the parent pages.',
        'icon' => [['M13,20H11V8L5.5,13.5L4.08,12.08L12,4.16L19.92,12.08L18.5,13.5L13,8V20Z']],
        'path' => dirname($path),
        'stack' => 9.9
    ] : null,
    'file' => [
        'title' => $language->{$id},
        'query' => [
            'tab' => false
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

Config::set('panel.$.page.tools.r.query.view', 'page');