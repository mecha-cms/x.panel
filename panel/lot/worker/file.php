<?php

Config::set('panel.desk.form', true);

Config::set('panel.desk', [
    'header' => null,
    'body' => [
        'tabs' => [
            'file' => [
                'fields' => [
                    'file[content]' => [
                        'key' => 'content',
                        'type' => 'source',
                        'value' => file_get_contents(LOT . DS . $panel->id . DS . $panel->path),
                        'placeholder' => 'Content goes here&hellip;',
                        'width' => true,
                        'height' => true,
                        'stack' => 10
                    ],
                    'name' => [
                        'type' => 'text',
                        'value' => basename($panel->path),
                        'width' => true,
                        'stack' => 10.1
                    ]
                ],
                'stack' => 10
            ],
            'folder' => [
                'stack' => 10.1
            ]
        ]
    ],
    'footer' => [
        'tools' => [
            '+' => [
                'title' => $language->update,
                'stack' => 10
            ]
        ]
    ]
]);