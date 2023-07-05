<?php namespace x\panel\type\pages;

function page(array $_ = []) {
    $type = $_['type'] ?? 'pages/page';
    return \x\panel\type\pages(\array_replace_recursive([
        'lot' => [
            'desk' => [
                // `desk`
                'lot' => [
                    'form' => [
                        // `form/post`
                        'lot' => [
                            0 => [
                                // `section`
                                'lot' => [
                                    'tasks' => [
                                        // `tasks/button`
                                        'lot' => [
                                            'page' => [
                                                'url' => [
                                                    'query' => \x\panel\_query_set(['type' => 'page/page'])
                                                ]
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
        'type' => $type
    ], $_));
}

function user(array $_ = []) {
    $type = $_['type'] ?? 'pages/user';
    return \x\panel\type\pages(\array_replace_recursive([
        'lot' => [
            'desk' => [
                // `desk`
                'lot' => [
                    'form' => [
                        // `form/post`
                        'lot' => [
                            0 => [
                                // `section`
                                'lot' => [
                                    'tasks' => [
                                        // `tasks/button`
                                        'lot' => [
                                            'page' => [
                                                'description' => [1 => 'User'],
                                                'skip' => true,
                                                'title' => 'User',
                                                'url' => [
                                                    'part' => 0,
                                                    'query' => \x\panel\_query_set(['type' => 'page/user']),
                                                    'task' => 'set'
                                                ]
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
        'type' => $type
    ], $_));
}

function x(array $_ = []) {
    $type = $_['type'] ?? 'pages/x';
    return \x\panel\type\pages(\array_replace_recursive([
        'lot' => [
            'desk' => [
                // `desk`
                'lot' => [
                    'form' => [
                        // `form/post`
                        'lot' => [
                            0 => [
                                // `section`
                                'lot' => [
                                    'tasks' => [
                                        // `tasks/button`
                                        'lot' => [
                                            'blob' => [
                                                'description' => false,
                                                'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                                'skip' => false,
                                                'title' => 'Add',
                                                'url' => [
                                                    'part' => 0,
                                                    'query' => \x\panel\_query_set(['type' => 'blob/x']),
                                                    'task' => 'set'
                                                ]
                                            ],
                                            'page' => ['skip' => true]
                                        ]
                                    ]
                                ]
                            ],
                            1 => [
                                // `section`
                                'lot' => [
                                    'tabs' => [
                                        // `tabs`
                                        'lot' => [
                                            'pages' => [
                                                'title' => 'Extensions'
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
        'type' => $type
    ], $_));
}

function y(array $_ = []) {
    $type = $_['type'] ?? 'pages/y';
    return \x\panel\type\pages\x(\array_replace_recursive([
        'lot' => [
            'desk' => [
                // `desk`
                'lot' => [
                    'form' => [
                        // `form/post`
                        'lot' => [
                            0 => [
                                // `section`
                                'lot' => [
                                    'tasks' => [
                                        // `tasks/button`
                                        'lot' => [
                                            'blob' => [
                                                'url' => [
                                                    'query' => \x\panel\_query_set(['type' => 'blob/y'])
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            1 => [
                                // `section`
                                'lot' => [
                                    'tabs' => [
                                        // `tabs`
                                        'lot' => [
                                            'pages' => [
                                                'title' => 'Layouts'
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
        'type' => $type
    ], $_));
}