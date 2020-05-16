<?php

if (Request::is('post')) {
    test(Post::get());
    exit;
}

return [
    'desk' => [
        // type: desk
        'lot' => [
            'form' => [
                // type: form/post
                'lot' => [
                    1 => [
                        // type: section
                        'lot' => [
                            'tabs' => [
                                // type: tabs
                                'lot' => [
                                    0 => [
                                        'title' => 'Test 1',
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'fields',
                                                'lot' => [
                                                    'to-sequence-array' => [
                                                        'title' => 'Items',
                                                        'description' => 'Output as sequence array.',
                                                        'type' => 'items',
                                                        'flat' => true,
                                                        'value' => ['red', 'green', 'blue'],
                                                        'lot' => [
                                                            'red' => 'Red',
                                                            'green' => 'Green',
                                                            'blue' => 'Blue',
                                                            'yellow' => [
                                                                'title' => 'Yellow',
                                                                'active' => false
                                                            ]
                                                        ]
                                                    ],
                                                    'to-associative-array' => [
                                                        'title' => 'Items',
                                                        'description' => 'Output as associative array.',
                                                        'type' => 'items',
                                                        'value' => [
                                                            'red' => 11,
                                                            'green' => 1,
                                                            'blue' => true
                                                        ],
                                                        'lot' => [
                                                            'red' => 'Red',
                                                            'green' => 'Green',
                                                            'blue' => 'Blue',
                                                            'yellow' => [
                                                                'title' => 'Yellow',
                                                                'active' => false
                                                            ]
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'stack' => 20
                    ],
                    2 => [
                        // type: section
                        'lot' => [
                            'fields' => [
                                'type' => 'fields',
                                'lot' => [
                                    0 => [
                                        'title' => "",
                                        'type' => 'field',
                                        'lot' => [
                                            'tasks' => [
                                                'type' => 'tasks/button',
                                                'lot' => [
                                                    0 => [
                                                        'title' => 'Test',
                                                        'type' => 'submit',
                                                        'name' => false,
                                                        'stack' => 10
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                'stack' => 10
                            ]
                        ]
                    ]
                ],
                'stack' => 10
            ]
        ],
        'stack' => 20
    ]
];
