<?php

// Sanitize the form data
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    // Generate author name if author field is empty
    if (empty($_POST['page']['author'])) {
        $_POST['page']['author'] = To::title($_POST['page']['name'] ?? i('User') . ' ' . (q(g(LOT . D . 'user', 'page'))) + 1);
    }
    // Remove all possible HTML tag(s) from the `email` data if any
    if (isset($_POST['page']['email'])) {
        $_POST['page']['email'] = strip_tags($_POST['page']['email']);
    }
    // Encrypt the `pass` data if any
    if (isset($_POST['data']['pass'])) {
        $name = $_POST['data']['name'] ?? $_POST['page']['name'] ?? uniqid();
        $_POST['data']['pass'] = P . password_hash($_POST['data']['pass'] . '@' . $name, PASSWORD_DEFAULT);
    }
}

$_['lot'] = array_replace_recursive($_['lot'] ?? [], [
    'bar' => [
        // `bar`
        'lot' => [
            // `links`
            0 => [
                'lot' => [
                    'set' => [
                        'description' => ['New %s', 'User'],
                        'icon' => 'M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M6,10V7H4V10H1V12H4V15H6V12H9V10M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12Z',
                        'url' => [
                            'part' => 0,
                            'path' => dirname($_['path']),
                            'query' => [
                                'query' => null,
                                'stack' => null,
                                'tab' => null,
                                'type' => 'page/user'
                            ],
                            'task' => 'set'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'desk' => [
        // `desk`
        'lot' => [
            'form' => [
                // `form/post`
                'lot' => [
                    1 => [
                        // `section`
                        'lot' => [
                            'tabs' => [
                                // `tabs`
                                'lot' => [
                                    'page' => [
                                        'name' => 'user',
                                        'lot' => [
                                            'fields' => [
                                                // `fields`
                                                'lot' => [
                                                    'author' => [
                                                        'description' => 'set' === $_['task'] ? 'Display name.' : null,
                                                        'hint' => 'get' === $_['task'] ? ($page['author'] ?? To::title($page->name ?? i('John Doe'))) : To::title($page->name ?? i('John Doe')),
                                                        'name' => 'page[author]',
                                                        'stack' => 10,
                                                        'type' => 'title',
                                                        'value' => $page['author'],
                                                        'width' => true
                                                    ],
                                                    'flex' => [
                                                        'lot' => [
                                                            'name' => [
                                                                'focus' => true,
                                                                'hint' => To::kebab('get' === $_['task'] ? ($page->name ?? 'John Doe') : 'John Doe'),
                                                                'name' => 'page[name]',
                                                                'stack' => 10,
                                                                'title' => 'User',
                                                                'type' => 'name',
                                                                'value-before' => ['icon' => 'M12,15C12.81,15 13.5,14.7 14.11,14.11C14.7,13.5 15,12.81 15,12C15,11.19 14.7,10.5 14.11,9.89C13.5,9.3 12.81,9 12,9C11.19,9 10.5,9.3 9.89,9.89C9.3,10.5 9,11.19 9,12C9,12.81 9.3,13.5 9.89,14.11C10.5,14.7 11.19,15 12,15M12,2C14.75,2 17.1,3 19.05,4.95C21,6.9 22,9.25 22,12V13.45C22,14.45 21.65,15.3 21,16C20.3,16.67 19.5,17 18.5,17C17.3,17 16.31,16.5 15.56,15.5C14.56,16.5 13.38,17 12,17C10.63,17 9.45,16.5 8.46,15.54C7.5,14.55 7,13.38 7,12C7,10.63 7.5,9.45 8.46,8.46C9.45,7.5 10.63,7 12,7C13.38,7 14.55,7.5 15.54,8.46C16.5,9.45 17,10.63 17,12V13.45C17,13.86 17.16,14.22 17.46,14.53C17.76,14.84 18.11,15 18.5,15C18.92,15 19.27,14.84 19.57,14.53C19.87,14.22 20,13.86 20,13.45V12C20,9.81 19.23,7.93 17.65,6.35C16.07,4.77 14.19,4 12,4C9.81,4 7.93,4.77 6.35,6.35C4.77,7.93 4,9.81 4,12C4,14.19 4.77,16.07 6.35,17.65C7.93,19.23 9.81,20 12,20H17V22H12C9.25,22 6.9,21 4.95,19.05C3,17.1 2,14.75 2,12C2,9.25 3,6.9 4.95,4.95C6.9,3 9.25,2 12,2Z'],
                                                                'vital' => true,
                                                                'width' => true,
                                                                'x' => false
                                                            ],
                                                            'pass' => [
                                                                'name' => 'data[pass]',
                                                                'stack' => 20,
                                                                'type' => 'pass',
                                                                'value' => "",
                                                                'value-before' => ['icon' => 'M12,17C10.89,17 10,16.1 10,15C10,13.89 10.89,13 12,13A2,2 0 0,1 14,15A2,2 0 0,1 12,17M18,20V10H6V20H18M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V10C4,8.89 4.89,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z'],
                                                                'vital' => true,
                                                                'width' => true
                                                            ]
                                                        ],
                                                        'skip' => 'set' !== $_['task'],
                                                        'stack' => 20,
                                                        'type' => 'flex'
                                                    ],
                                                    'name' => ['skip' => true],
                                                    'title' => ['skip' => true]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'data' => [
                                        'lot' => [
                                            'fields' => [
                                                'lot' => [
                                                    'email' => [
                                                        'name' => 'page[email]',
                                                        'stack' => 11,
                                                        'type' => 'email',
                                                        'value' => $page['email'],
                                                        'width' => true
                                                    ],
                                                    'status' => [
                                                        'lot' => [
                                                            1 => [
                                                                'description' => 1,
                                                                'title' => 'Admin'
                                                            ],
                                                            2 => [
                                                                'description' => 2,
                                                                'title' => 'Editor'
                                                            ],
                                                            3 => [
                                                                'description' => 3,
                                                                'title' => 'Member'
                                                            ],
                                                            0 => [
                                                                'description' => 0,
                                                                'title' => 'Pending'
                                                            ],
                                                            -1 => [
                                                                'description' => -1,
                                                                'title' => 'Banned'
                                                            ]
                                                        ],
                                                        'name' => 'page[status]',
                                                        // Automatic sort by title is disabled because status order is more important to UX in this case
                                                        'sort' => false,
                                                        'stack' => 30,
                                                        'type' => 'item',
                                                        'value' => 'set' === $_['task'] ? 3 : $page['status']
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
                        // `section`
                        'lot' => [
                            'fields' => [
                                // `fields`
                                'lot' => [
                                    0 => [
                                        // `field`
                                        'lot' => [
                                            'tasks' => [
                                                // `tasks/button`
                                                'lot' => [
                                                    'draft' => ['skip' => 'draft' === $page->x || $page->name === $user->name],
                                                    'archive' => ['skip' => true],
                                                    'let' => ['skip' => 'set' === $_['task'] || $page->name === $user->name]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'values' => [
                    // These were added only to hide the `pass.data` and `token.data` file item(s) in the Data tab
                    'data' => [
                        'pass' => false,
                        'token' => false
                    ],
                    'page' => 'set' !== $_['task'] ? ['name' => $page->name] : []
                ]
            ]
        ]
    ]
]);

return $_;