<?php

$_ = require __DIR__ . DS . '..' . DS . 'page.php';

$_['lot'] = array_replace_recursive($_['lot'] ?? [], [
    'bar' => [
        // type: bar
        'lot' => [
            // type: links
            0 => [
                'lot' => [
                    's' => ['skip' => true]
                ]
            ]
        ]
    ],
    'desk' => [
        // type: desk
        'lot' => [
            'form' => [
                // type: form/post
                'data' => [
                    'file' => ['name' => $page->name]
                ],
                'lot' => [
                    1 => [
                        // type: section
                        'lot' => [
                            'tabs' => [
                                // type: tabs
                                'lot' => [
                                    'page' => [
                                        'name' => 'comment',
                                        'lot' => [
                                            'fields' => [
                                                // type: fields
                                                'lot' => [
                                                    'author' => [
                                                        'type' => 's' === $_['task'] ? 'hidden' : 'text',
                                                        'width' => true,
                                                        'stack' => 10
                                                    ],
                                                    'content' => ['stack' => 20],
                                                    'title' => ['skip' => true],
                                                    'description' => ['skip' => true],
                                                    'name' => ['skip' => true]
                                                ]
                                            ]
                                        ],
                                        'stack' => 10
                                    ],
                                    'data' => [
                                        'lot' => [
                                            'fields' => [
                                                'lot' => [
                                                    'time' => ['skip' => true],
                                                    'email' => [
                                                        'type' => 'email',
                                                        'name' => 'page[email]',
                                                        'value' => $page['email'],
                                                        'stack' => 20
                                                    ],
                                                    'status' => [
                                                        'type' => 'item',
                                                        'name' => 'page[status]',
                                                        'value' => 's' === $_['task'] ? 1 : $page['status'],
                                                        // Automatic sort by title is disabled because status order is more important to UX in this case
                                                        'sort' => false,
                                                        'lot' => [
                                                            1 => [
                                                               'title' => 'Author',
                                                               'description' => 1
                                                           ],
                                                            2 => [
                                                               'title' => 'Reader',
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
                        // type: section
                        'lot' => [
                            'fields' => [
                                // type: fields
                                'lot' => [
                                    0 => [
                                        // type: field
                                        'lot' => [
                                            'tasks' => [
                                                // type: tasks/button
                                                'lot' => [
                                                    's' => ['description' => ['Update as %s', ['page' === $page->x ? 'Accepted' : 'Rejected']]],
                                                    'page' => ['title' => 's' === $_['task'] ? 'Publish' : 'Accept'],
                                                    'draft' => ['title' => 's' === $_['task'] ? 'Save' : 'Reject'],
                                                    'archive' => ['skip' => true]
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

return $_;
