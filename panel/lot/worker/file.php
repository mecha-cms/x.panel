<?php

$c = $panel->c;
$path = LOT . DS . $panel->id . DS . $panel->path;
$is_file = is_file($path) ? mime_content_type($path) : "";
$is_file_text = $is_file && (strpos($is_file, 'text/') === 0 || strpos($is_file, 'application/') === 0);

Config::set('panel.desk', [
    'header' => null,
    'body' => [
        'tabs' => [
            'file' => [
                'fields' => [
                    'file[content]' => $c === 's' || $is_file_text ? [
                        'key' => 'content',
                        'type' => 'source',
                        'value' => $is_file_text ? file_get_contents($path) : null,
                        'placeholder' => 'Content goes here…',
                        'width' => true,
                        'height' => true,
                        'stack' => 10
                    ] : [
                        'content' => '<div class="field p"><label>' . $language->{$is_file ? 'content' : ""} . '</label><div><div class="input plain code">' . ($is_file && strpos($is_file, 'image/') === 0 ? HTML::img($path) : str_replace('/', DS, $path)) . '</div></div></div>',
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
                'fields' => [
                    'directory' => [
                        'title' => $language->path,
                        'type' => 'text',
                        'value' => null,
                        'width' => true,
                        'stack' => 10
                    ]
                ],
                'stack' => 10.1
            ]
        ]
    ],
    'footer' => [
        'tools' => [
            0 => [
                'title' => $language->{$c === 's' ? 'create' : 'update'},
                'name' => 'a',
                'value' => 1,
                'stack' => 10
            ],
            1 => $c === 'g' ? [
                'title' => $language->delete,
                'name' => 'a',
                'value' => -1,
                'stack' => 10.1
            ] : null
        ]
    ]
]);