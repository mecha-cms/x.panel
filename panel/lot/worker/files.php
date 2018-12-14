<?php

$c = $panel->c;
$is_file = is_file($file) ? mime_content_type($file) : "";

Config::set('panel.desk', [
    'header' => [
        'tool' => [
            'file' => [
                'icon' => [['M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z']],
                'c' => 's',
                'query' => [
                    'q' => false,
                    'tab' => ['file'],
                    'tabs' => ['false'],
                    'token' => false
                ],
                'stack' => 10
            ],
            'folder' => [
                'icon' => [['M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z']],
                'c' => 's',
                'query' => [
                    'q' => false,
                    'tab' => ['folder'],
                    'tabs' => ['false'],
                    'token' => false
                ],
                'stack' => 10.1
            ],
            '+' => [
                'title' => false,
                'type' => 'button',
                'icon' => [['M16,12A2,2 0 0,1 18,10A2,2 0 0,1 20,12A2,2 0 0,1 18,14A2,2 0 0,1 16,12M10,12A2,2 0 0,1 12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12M4,12A2,2 0 0,1 6,10A2,2 0 0,1 8,12A2,2 0 0,1 6,14A2,2 0 0,1 4,12Z']],
                'kind' => ['text'],
                '+' => [
                    'blob' => [
                        'title' => $language->upload,
                        'icon' => [['M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z']],
                        'c' => 's',
                        'query' => [
                            'q' => false,
                            'tab' => ['blob'],
                            'tabs' => ['false'],
                            'token' => false
                        ],
                        'stack' => 10
                    ],
                    // Only user with status `1` that has delete access
                    'r' => $chops && $user->status === 1 ? [
                        'title' => $language->delete,
                        'description' => $language->do_empty_directory,
                        'icon' => [['M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z']],
                        'c' => 'r',
                        'query' => [
                            'a' => -2,
                            'q' => false,
                            'tab' => false,
                            'token' => $user->token
                        ],
                        'stack' => 10.3
                    ] : null
                ],
                'stack' => 10.2
            ]
        ]
    ],
    'body' => [
        'tab' => [
            'file' => [
                'title' => $language->files,
                'explore' => $file,
                'stack' => 10
            ]
        ]
    ],
    'footer' => [
        'pager' => true
    ]
]);