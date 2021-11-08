<?php

$id = 0;

foreach (g($_['f'], 'archive,page') as $k => $v) {
    $v = From::tag(pathinfo($k, PATHINFO_FILENAME)) ?? 0;
    if ($v > $id) {
        $id = $v;
    }
}

++$id;

$_ = require __DIR__ . DS . '..' . DS . 'page.php';

$_['lot'] = array_replace_recursive($_['lot'] ?? [], [
    'bar' => [
        // type: bar
        'lot' => [
            // type: links
            0 => [
                'lot' => [
                    's' => [
                        'icon' => 'M21.41,11.58L12.41,2.58C12.04,2.21 11.53,2 11,2H4A2,2 0 0,0 2,4V11C2,11.53 2.21,12.04 2.59,12.41L3,12.81C3.9,12.27 4.94,12 6,12A6,6 0 0,1 12,18C12,19.06 11.72,20.09 11.18,21L11.58,21.4C11.95,21.78 12.47,22 13,22C13.53,22 14.04,21.79 14.41,21.41L21.41,14.41C21.79,14.04 22,13.53 22,13C22,12.47 21.79,11.96 21.41,11.58M5.5,7A1.5,1.5 0 0,1 4,5.5A1.5,1.5 0 0,1 5.5,4A1.5,1.5 0 0,1 7,5.5A1.5,1.5 0 0,1 5.5,7M10,19H7V22H5V19H2V17H5V14H7V17H10V19Z',
                        'description' => ['New %s', 'Tag'],
                        'url' => strtr(dirname($url->clean), ['::g::' => '::s::']) . $url->query('&', [
                            'tab' => false,
                            'type' => 'page/tag'
                        ]) . $url->hash
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
                    'data' => ['id' => 's' === $_['task'] ? $id : $page->id]
                ],
                'lot' => [
                    1 => [
                        // type: section
                        'lot' => [
                            'tabs' => [
                                // type: tabs
                                'lot' => [
                                    'page' => [
                                        'name' => 'tag',
                                        'lot' => [
                                            'fields' => [
                                                // type: fields
                                                'lot' => [
                                                    'content' => ['skip' => true]
                                                ]
                                            ]
                                        ],
                                        'stack' => 10
                                    ],
                                    'data' => [
                                        'lot' => [
                                            'fields' => [
                                                'lot' => [
                                                    'link' => ['skip' => true]
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
                                                    'l' => ['skip' => 's' === $_['task'] || $page->name === $user->name]
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