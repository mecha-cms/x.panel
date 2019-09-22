<?php

return [
    'desk' => [
        // type: Desk
        'lot' => [
            'form' => [
                // type: Form.Post
                'lot' => [
                    0 => [
                        'type' => 'Pane',
                        'title' => 'Pane 1',
                        'description' => 'Lorem ipsum dolor sit amet.',
                        'lot' => [
                            0 => [
                                'type' => 'Section',
                                'lot' => [
                                    0 => [
                                        'type' => 'Menu',
                                        'static' => true,
                                        'lot' => [
                                            ['title' => 'Foo', 'url' => '/'],
                                            ['title' => 'Bar', 'url' => '/'],
                                            ['title' => 'Baz', 'url' => '/'],
                                            ['title' => 'Qux', 'url' => '/', 'lot' => [
                                                ['title' => 'Foo', 'url' => '/'],
                                                ['title' => 'Bar', 'url' => '/'],
                                                ['title' => 'Baz', 'url' => '/']
                                            ]]
                                        ]
                                    ]
                                ],
                                'stack' => 10
                            ],
                            1 => [
                                'type' => 'Section',
                                'content' => (new Page(PAGE . DS . 'layout.archive'))->content,
                                'stack' => 20
                            ]
                        ],
                        'stack' => 10
                    ]
                ]
            ]
        ]
    ]
];