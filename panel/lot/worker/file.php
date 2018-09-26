<?php

Config::set('panel.desk.form', true);

$path = LOT . DS . $panel->id . DS . $panel->path;
$mime = is_file($path) ? mime_content_type($path) : "";
$is_text = $mime && (strpos($mime, 'text/') === 0 || strpos($mime, 'application/') === 0);

Config::set('panel.desk', [
    'header' => null,
    'body' => [
        'tab[]' => [
            'file' => [
                'field[]' => [
                    'file[content]' => $panel->{'>>'} === 's' || $is_text ? [
                        'key' => 'content',
                        'type' => 'source',
                        'value' => $is_text ? file_get_contents($path) : null,
                        'placeholder' => 'Content goes here…',
                        'width' => true,
                        'height' => true,
                        'stack' => 10
                    ] : [
                        'content' => '<div class="field p"><label>' . $language->{$mime ? 'content' : 'path'} . '</label><div><div class="input plain">' . ($mime && strpos($mime, 'image/') === 0 ? HTML::img($path) : '<ul class="traces"><li>' . implode('</li><li>', explode('/', Path::F($path, LOT, '/'))) . '</li></ul>') . '</div></div></div>',
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
                'field[]' => [
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
        'tool[]' => [
            '+' => [
                'title' => $language->update,
                'stack' => 10
            ]
        ]
    ]
]);