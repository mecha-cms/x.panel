<?php

if (Request::is('post')) {
    test(Post::get());
    exit;
}

return [
    'desk' => [
        // type: Desk
        'lot' => [
            'form' => [
                // type: Form.Post
                'lot' => [
                    1 => [
                        // type: Section
                        'lot' => [
                            'tabs' => [
                                // type: Tabs
                                'lot' => [
                                    0 => [
                                        'title' => 'Test 1',
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => [
                                                    'to-sequence-array' => [
                                                        'title' => 'Items',
                                                        'description' => 'Output as sequence array.',
                                                        'type' => 'Items',
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
                                                        'type' => 'Items',
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
                        // type: Section
                        'lot' => [
                            'fields' => [
                                'type' => 'Fields',
                                'lot' => [
                                    0 => [
                                        'title' => "",
                                        'type' => 'Field',
                                        'lot' => [
                                            0 => [
                                                'type' => 'Tasks.Button',
                                                'lot' => [
                                                    0 => [
                                                        'title' => 'Test',
                                                        'type' => 'Submit',
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