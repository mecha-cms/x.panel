<?php

$lot = require __DIR__ . DS . 'page.php';

$parent = $_GET['parent'] ?? P;
$parent = is_file($f = LOT . $_['path'] . DS . $parent . '.page') ? new Comment($f) : new Comment;

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
                    0 => $parent->exist ? [
                        // type: Section
                        'title' => 'Reply to ' . $parent->author,
                        'content' => $parent->content
                    ] : [],
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
                                                        'type' => $_['task'] === 's' ? 'Hidden' : 'Text',
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
                                                    'time' => ['hidden' => true],
                                                    'link' => ['hidden' => true],
                                                    'status' => [
                                                        'type' => 'Item',
                                                        'name' => 'page[status]',
                                                        'value' => $_['task'] === 's' ? 1 : $page['status'],
                                                        // Automatic sort by title is disabled because status order is more important to UX in this case
                                                        'sort' => false,
                                                        'lot' => [
                                                            1 => [
                                                               'title' => 'Author',
                                                               'description' => 1
                                                           ],
                                                            2 => [
                                                               'title' => 'Visitor',
                                                               'description' => 2
                                                           ],
                                                           -1 => [
                                                               'title' => 'Banned',
                                                               'description' => -1
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
                                                    'page' => ['title' => $_['task'] === 's' ? ($parent->exist ? 'Reply' : 'Publish') : 'Accept'],
                                                    'draft' => ['title' => $_['task'] === 's' ? 'Save' : 'Reject'],
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
