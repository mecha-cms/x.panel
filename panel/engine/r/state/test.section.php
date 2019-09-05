<?php

return [
    'desk' => [
        // type: Desk
        'lot' => [
            'form' => [
                // type: Form.Post
                'lot' => [
                    0 => [
                        'type' => 'Section',
                        'title' => 'Section 1',
                        'stack' => 10
                    ],
                    1 => [
                        'type' => 'Section',
                        'title' => 'Section 2',
                        'description' => 'Lorem ipsum dolor sit amet.',
                        'stack' => 20
                    ],
                    2 => [
                        'type' => 'Section',
                        'title' => 'Section 3',
                        'description' => 'Lorem ipsum dolor sit amet.',
                        /*
                        'lot' => [
                            0 => [
                                'type' => 'Column',
                                'content' => '<div style="background:#def;height:100px;"></div>',
                                'stack' => 10
                            ],
                            2 => [
                                'type' => 'Column',
                                'content' => '<div style="background:#def;height:100px;"></div>',
                                'stack' => 20
                            ],
                            3 => [
                                'type' => 'Column',
                                'content' => '<div style="background:#def;height:100px;"></div>',
                                'stack' => 30
                            ]
                        ],
                        */
                        'stack' => 30
                    ],
                    3 => [
                        'type' => 'Section',
                        'title' => 'Section 4',
                        'description' => 'Lorem ipsum dolor sit amet.',
                        'content' => (new Page(PAGE . DS . 'layout.archive'))->content,
                        'stack' => 40
                    ]
                ]
            ]
        ]
    ]
];