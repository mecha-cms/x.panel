<?php

require __DIR__ . DS . 'before.php';

echo _\lot\x\panel(['lot' => [
    'desk' => [
        'type' => 'desk.form.post',
        '/' => '/foo/bar',
        2 => ['enctype' => 'multipart/form-data'],
        'lot' => [
            /*
            'header' => [
                'type' => 'desk.header',
                'content' => 'Header goes here.',
                'stack' => 10
            ],
            */
            'body' => [
                'type' => 'desk.body',
                'lot' => [
                    'tab' => [
                        'type' => 'tab',
                        'lot' => [
                            'file' => [
                                'title' => 'File',
                                'lot' => [
                                    'field' => [
                                        'type' => 'fields',
                                        'lot' => [
                                            'file[content]' => [
                                                'type' => 'field.source',
                                                'title' => $language->content,
                                                'width' => true,
                                                'height' => true,
                                                'placeholder' => 'File content goes here...',
                                                'value' => "",
                                                'stack' => 20
                                            ],
                                            'file[name]' => [
                                                'type' => 'field.text',
                                                'title' => $language->name,
                                                'width' => true,
                                                'placeholder' => 'name.txt',
                                                'value' => "",
                                                'stack' => 30
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            'folder' => [
                                'title' => 'Folder',
                                'content' => 'Content for <em>Folder</em> tab.'
                            ],
                            'blob' => [
                                'title' => 'Upload',
                                'content' => 'Content for <em>Upload</em> tab.'
                            ]
                        ],
                        'name' => 0
                    ]
                ],
                'stack' => 20
            ],
            'footer' => [
                'type' => 'desk.footer',
                'lot' => [
                    'task' => [
                        'type' => 'task',
                        'lot' => [
                            0 => [
                                'type' => 'button',
                                'title' => 'Publish',
                                'name' => 'x',
                                'value' => 'page',
                                'stack' => 10
                            ],
                            1 => [
                                'type' => 'button',
                                'title' => 'Save',
                                'name' => 'x',
                                'value' => 'draft',
                                'stack' => 20
                            ],
                            2 => [
                                'active' => false,
                                'type' => 'button',
                                'title' => 'Archive',
                                'name' => 'x',
                                'value' => 'archive',
                                'stack' => 30
                            ]
                        ]
                    ]
                ],
                'stack' => 30
            ]
        ]
    ]
]], 0, '#');

require __DIR__ . DS . 'after.php';