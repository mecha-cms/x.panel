<?php

$lot = require __DIR__ . DS . 'page.php';

$lot = array_replace_recursive($lot, [
    'bar' => [
        // type: Bar
        'lot' => [
            // type: List
            0 => [
                'lot' => [
                    's' => ['hidden' => true]
                ]
            ]
        ]
    ],
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
                                    'page' => [
                                        'name' => 'comment',
                                        'lot' => [
                                            'fields' => [
                                                // type: Fields
                                                'lot' => [
                                                    'author' => [
                                                        'type' => 'Text',
                                                        'width' => true,
                                                        'stack' => 10
                                                    ],
                                                    'content' => ['stack' => 20],
                                                    'title' => ['hidden' => true],
                                                    'description' => ['hidden' => true],
                                                    'name' => ['type' => 'Hidden']
                                                ]
                                            ]
                                        ],
                                        'stack' => 10
                                    ],
                                    'data' => [
                                        'lot' => [
                                            'fields' => [
                                                'lot' => [
                                                    'link' => ['hidden' => true],
                                                    'status' => [
                                                        'type' => 'Item',
                                                        'name' => 'page[status]',
                                                        'value' => $_['task'] === 's' ? 3 : $page['status'],
                                                        // Automatic sort by title is disabled because status order is more important to UX in this case
                                                        'sort' => false,
                                                        'lot' => [
                                                           -1 => [
                                                               'title' => 'Banned',
                                                               'description' => -1
                                                           ],
                                                            1 => [
                                                               'title' => 'Author',
                                                               'description' => 1
                                                           ],
                                                            2 => [
                                                               'title' => 'Visitor',
                                                               'description' => 2
                                                           ]
                                                        ],
                                                        'stack' => 30
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    2 => [
                        // type: Section
                        'lot' => [
                            'fields' => [
                                // type: Fields
                                'lot' => [
                                    0 => [
                                        // type: Field
                                        'lot' => [
                                            'tasks' => [
                                                // type: Tasks.Button
                                                'lot' => [
                                                    's' => ['description' => ['Update as %s', [$page->x === 'page' ? 'Accepted' : 'Rejected']]],
                                                    'page' => ['title' => 'Accept'],
                                                    'draft' => ['title' => 'Reject'],
                                                    'archive' => ['hidden' => true]
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                'stack' => 10
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
]);

return $lot;
