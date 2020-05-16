<?php

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
                                        'title' => 'Tab 1',
                                        'content' => '<p>Content for the first tab.</p>',
                                        'stack' => 10
                                    ],
                                    1 => [
                                        'title' => 'Tab 2',
                                        'description' => 'Description for the second tab.',
                                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                        'content' => '<p>Content for the second tab.</p>',
                                        'stack' => 20
                                    ],
                                    2 => [
                                        'title' => 'Tab 3',
                                        'active' => false,
                                        'content' => '<p>Content for the third tab.</p>',
                                        'stack' => 30
                                    ],
                                    3 => [
                                        'title' => 'Tab 4',
                                        'description' => 'This tab is clickable.',
                                        'url' => '/',
                                        'stack' => 40
                                    ],
                                    4 => [
                                        'title' => 'Tab 5',
                                        'description' => 'This tab is also clickable.',
                                        'link' => '/',
                                        'stack' => 50
                                    ]
                                ]
                            ]
                        ],
                        'stack' => 20
                    ]
                ],
                'stack' => 10
            ]
        ],
        'stack' => 20
    ]
];
