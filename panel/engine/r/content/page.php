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
                            'page' => [
                                'title' => 'Page',
                                'lot' => [
                                    'field' => [
                                        'type' => 'fields',
                                        'lot' => [
                                            'page[title]' => [
                                                'type' => 'field.text',
                                                'title' => $language->title,
                                                'width' => true,
                                                'placeholder' => 'Page Title',
                                                'value' => "",
                                                'stack' => 10
                                            ],
                                            'page[content]' => [
                                                'type' => 'field.source',
                                                'title' => $language->content,
                                                'width' => true,
                                                'height' => true,
                                                'placeholder' => 'Page content goes here...',
                                                'value' => "",
                                                'stack' => 20
                                            ],
                                            'page[description]' => [
                                                'type' => 'field.content',
                                                'title' => $language->description,
                                                'width' => true,
                                                'placeholder' => 'Page description goes here... ',
                                                'value' => "",
                                                'stack' => 30
                                            ],
                                            'page[type]' => [
                                                'type' => 'field.item',
                                                'title' => $language->type,
                                                'value' => 'Markdown',
                                                'lot' => [
                                                    'HTML' => 'HTML',
                                                    'Markdown' => 'Markdown'
                                                ],
                                                'stack' => 40
                                            ],
                                            'page[category]' => [
                                                'type' => 'field.items',
                                                'title' => 'Categories',
                                                'value[]' => ['A', 'B', '1'],
                                                'lot' => [
                                                    '0' => 'Item 0',
                                                    '1' => 'Item 1',
                                                    '2' => 'Item 2',
                                                    '3' => 'Item 3',
                                                    '4' => 'Item 4',
                                                    '5' => 'Item 5',
                                                    '6' => 'Item 6',
                                                    '7' => 'Item 7',
                                                    '8' => 'Item 8',
                                                    '9' => 'Item 9',
                                                    'A' => 'Item A',
                                                    'B' => 'Item B',
                                                    'C' => 'Item C',
                                                    'D' => 'Item D',
                                                    'E' => 'Item E',
                                                    'F' => 'Item F'
                                                ],
                                                'stack' => 50
                                            ],
                                            'page[active]' => [
                                                'type' => 'field.toggle',
                                                'title' => 'Show in menu',
                                                'value' => true,
                                                'stack' => 60
                                            ],
                                            'page[foo]' => [
                                                'type' => 'field.combo',
                                                'title' => 'Combo',
                                                'lot' => [
                                                    'red' => 'Red',
                                                    'green' => 'Green',
                                                    'blue' => 'Blue'
                                                ],
                                                'stack' => 70
                                            ],
                                            'page[fooz]' => [
                                                'type' => 'field.combo',
                                                'title' => 'Combo',
                                                'lot' => [
                                                    'red' => 'Red',
                                                    'green' => 'Green',
                                                    'blue' => 'Blue',
                                                    'bw' => [
                                                        'title' => 'Black & White',
                                                        'lot' => [
                                                            'black' => 'Black',
                                                            'white' => 'White',
                                                            'gray' => 'Gray'
                                                        ]
                                                    ]
                                                ],
                                                'stack' => 70
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            'data' => [
                                'title' => 'Data',
                                'content' => 'Content for <em>Data</em> tab.'
                            ],
                            'any' => [
                                'title' => 'Others',
                                'content' => 'Content for <em>Others</em> tab.'
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