<?php

$lot = require __DIR__ . DS . 'page.php';

$lot = array_replace_recursive($lot, [
    'bar' => [
        // type: bar
        'lot' => [
            // type: bar/menu
            0 => [
                'lot' => [
                    'link' => [
                        'url' => $url . $_['/'] . '/::g::/' . $_['chops'][0] . '/1' . $url->query('&', ['layout' => false, 'tab' => false]) . $url->hash
                    ],
                    's' => ['hidden' => true]
                ]
            ]
        ]
    ],
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
                                                    'title' => ['hidden' => true],
                                                    'description' => ['hidden' => true],
                                                    'name' => ['type' => 'hidden']
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
