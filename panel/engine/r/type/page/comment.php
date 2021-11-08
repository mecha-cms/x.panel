<?php

$_ = require __DIR__ . DS . '..' . DS . 'page.php';

if ($parent = Get::get('parent')) {
    if (is_file($f = $_['f'] . DS . $parent . '.page')) {
        $parent = new Comment($f);
    } else {
        $parent = null;
        $_['alert']['error'][] = ['%s %s does not exist.', ['Comment', '<code>' . x\panel\from\path($f) . '</code>']];
        $_['kick'] = $_['/'] . '/::g::/' . $_['path'] . '/1' . $url->query('&', [
            'parent' => false,
            'q' => false,
            'tab' => false,
            'type' => false
        ]) . $url->hash;
    }
}

// Trigger native comment creation hook ;)
if ('s' === $_['task']) {
    Hook::set('do.page.set', function($_) {
        Hook::fire('on.comment.set', [$_['f']]);
    }, 10.1);
}

$_['lot'] = array_replace_recursive($_['lot'] ?? [], [
    'bar' => [
        // type: bar
        'lot' => [
            // type: links
            0 => [
                'lot' => [
                    'link' => $parent ? [
                        'url' => $_['/'] . '/::g::/' . ('g' === $_['task'] ? (0 === q(g(Path::F($f), 'archive,draft,page')) ? dirname($_['path']) : Path::F($_['path'])) : $_['path']) . '/1' . $url->query('&', [
                            'parent' => false,
                            'q' => false,
                            'tab' => false,
                            'type' => false
                        ]) . $url->hash
                    ] : [],
                    's' => [
                        'icon' => 'M9,22A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V4C2,2.89 2.9,2 4,2H20A2,2 0 0,1 22,4V16A2,2 0 0,1 20,18H13.9L10.2,21.71C10,21.9 9.75,22 9.5,22H9M11,6V9H8V11H11V14H13V11H16V9H13V6H11Z',
                        'description' => ['New %s', 'Comment'],
                        'url' => strtr(dirname($url->clean), ['::g::' => '::s::']) . $url->query('&', [
                            'parent' => false,
                            'tab' => false,
                            'type' => 'page/comment'
                        ]) . $url->hash,
                        'skip' => true // Hide “New Comment” button
                    ]
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
                    'data' => $parent ? ['parent' => $parent->name] : [],
                    'page' => ['name' => $page->name]
                ],
                'lot' => [
                    0 => $parent ? [
                        // type: section
                        'title' => ['Reply to %s', '<a href="' . $_['/'] . '/::g::/' . $_['path'] . '/' . $parent->name . '.page">' . $parent->author . '</a>'],
                        'description' => $parent->time->{r('-', '_', $state->language)},
                        'content' => $parent->content
                    ] : [],
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
                                                        'type' => 'text',
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