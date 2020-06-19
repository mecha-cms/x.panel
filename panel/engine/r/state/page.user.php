<?php

$lot = require __DIR__ . DS . 'page.php';

// Sanitize form data
Hook::set(['do.page.get', 'do.page.set'], function($_) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    $_['form']['page']['email'] = _\lot\x\panel\h\w($_['form']['page']['email'] ?? "");
    // Encrypt password
    if (isset($_['form']['data']['pass'])) {
        $name = $_['form']['data']['name'] ?? $lot['page']['name'] ?? uniqid();
        $_['form']['data']['pass'] = P . password_hash($_['form']['data']['pass'] . '@' . $name, PASSWORD_DEFAULT);
    }
    return $_;
}, 9.9);

$lot = array_replace_recursive($lot, [
    'bar' => [
        // type: bar
        'lot' => [
            // type: bar/menu
            0 => [
                'lot' => [
                    's' => [
                        'icon' => 'M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M6,10V7H4V10H1V12H4V15H6V12H9V10M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12Z',
                        'description' => ['New %s', 'User'],
                        'url' => str_replace('::g::', '::s::', dirname($url->clean)) . $url->query('&', ['layout' => 'page.user', 'tab' => false]) . $url->hash
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
                'lot' => [
                    1 => [
                        // type: section
                        'lot' => [
                            'tabs' => [
                                // type: tabs
                                'lot' => [
                                    'page' => [
                                        'name' => 'user',
                                        'lot' => [
                                            'fields' => [
                                                // type: fields
                                                'lot' => [
                                                    'title' => ['hidden' => true],
                                                    'author' => [
                                                        'description' => 's' === $_['task'] ? 'Display name.' : null,
                                                        'type' => 'text',
                                                        'name' => 'page[author]',
                                                        'alt' => 'g' === $_['task'] ? ($page['author'] ?? To::title($page->name ?? i('John Doe'))) : To::title($page->name ?? i('John Doe')),
                                                        'value' => $page['author'],
                                                        'width' => true,
                                                        'stack' => 10
                                                    ],
                                                    'name' => [
                                                        'title' => 'User',
                                                        'type' => 'g' === $_['task'] ? 'hidden' : 'text',
                                                        'required' => true,
                                                        'before' => ['icon' => 'M12,15C12.81,15 13.5,14.7 14.11,14.11C14.7,13.5 15,12.81 15,12C15,11.19 14.7,10.5 14.11,9.89C13.5,9.3 12.81,9 12,9C11.19,9 10.5,9.3 9.89,9.89C9.3,10.5 9,11.19 9,12C9,12.81 9.3,13.5 9.89,14.11C10.5,14.7 11.19,15 12,15M12,2C14.75,2 17.1,3 19.05,4.95C21,6.9 22,9.25 22,12V13.45C22,14.45 21.65,15.3 21,16C20.3,16.67 19.5,17 18.5,17C17.3,17 16.31,16.5 15.56,15.5C14.56,16.5 13.38,17 12,17C10.63,17 9.45,16.5 8.46,15.54C7.5,14.55 7,13.38 7,12C7,10.63 7.5,9.45 8.46,8.46C9.45,7.5 10.63,7 12,7C13.38,7 14.55,7.5 15.54,8.46C16.5,9.45 17,10.63 17,12V13.45C17,13.86 17.16,14.22 17.46,14.53C17.76,14.84 18.11,15 18.5,15C18.92,15 19.27,14.84 19.57,14.53C19.87,14.22 20,13.86 20,13.45V12C20,9.81 19.23,7.93 17.65,6.35C16.07,4.77 14.19,4 12,4C9.81,4 7.93,4.77 6.35,6.35C4.77,7.93 4,9.81 4,12C4,14.19 4.77,16.07 6.35,17.65C7.93,19.23 9.81,20 12,20H17V22H12C9.25,22 6.9,21 4.95,19.05C3,17.1 2,14.75 2,12C2,9.25 3,6.9 4.95,4.95C6.9,3 9.25,2 12,2Z'],
                                                        'alt' => To::kebab('g' === $_['task'] ? ($page->name ?? 'John Doe') : 'John Doe'),
                                                        'focus' => true,
                                                        'hidden' => false,
                                                        'stack' => 11
                                                    ],
                                                    'pass' => [
                                                        'type' => 'pass',
                                                        'required' => true,
                                                        'before' => ['icon' => 'M12,17C10.89,17 10,16.1 10,15C10,13.89 10.89,13 12,13A2,2 0 0,1 14,15A2,2 0 0,1 12,17M18,20V10H6V20H18M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V10C4,8.89 4.89,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z'],
                                                        'name' => 'data[pass]',
                                                        'value' => "",
                                                        'width' => true,
                                                        'hidden' => 's' !== $_['task'],
                                                        'stack' => 12
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'data' => [
                                        'lot' => [
                                            'fields' => [
                                                'lot' => [
                                                    'email' => [
                                                        'type' => 'email',
                                                        'name' => 'page[email]',
                                                        'alt' => 'g' === $_['task'] ? $page['email'] : null,
                                                        'value' => $page['email'],
                                                        'width' => true,
                                                        'stack' => 11
                                                    ],
                                                    'status' => [
                                                        'type' => 'item',
                                                        'name' => 'page[status]',
                                                        'value' => 's' === $_['task'] ? 3 : $page['status'],
                                                        // Automatic sort by title is disabled because status order is more important to UX in this case
                                                        'sort' => false,
                                                        'lot' => [
                                                            1 => [
                                                                'title' => 'Admin',
                                                                'description' => 1
                                                            ],
                                                            2 => [
                                                                'title' => 'Editor',
                                                                'description' => 2
                                                            ],
                                                            3 => [
                                                                'title' => 'Member',
                                                                'description' => 3
                                                            ],
                                                            0 => [
                                                                'title' => 'Pending',
                                                                'description' => 0
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
                                                    'draft' => ['hidden' => 'draft' === $page->x || $page->name === $user->name],
                                                    'archive' => ['hidden' => true],
                                                    'l' => ['hidden' => 's' === $_['task'] || $page->name === $user->name]
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
        ]
    ]
]);

return $lot;
