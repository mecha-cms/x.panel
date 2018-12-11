<?php

$c = $panel->c;
$is_file = is_file($file) ? mime_content_type($file) : "";
$package_feature = false; // Extend::exist('package'); // TODO

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
                    ] : null,
                    '_0' => $package_feature ? [
                        'type' => '|',
                        'stack' => 20
                    ] : null,
                    'package' => $package_feature ? [
                        'title' => $language->do_backup_folder,
                        'icon' => [['M12,3A9,9 0 0,0 3,12H0L4,16L8,12H5A7,7 0 0,1 12,5A7,7 0 0,1 19,12A7,7 0 0,1 12,19C10.5,19 9.09,18.5 7.94,17.7L6.5,19.14C8.04,20.3 9.94,21 12,21A9,9 0 0,0 21,12A9,9 0 0,0 12,3M14,12A2,2 0 0,0 12,10A2,2 0 0,0 10,12A2,2 0 0,0 12,14A2,2 0 0,0 14,12Z']],
                        'path' => $id,
                        'active' => false,
                        'task' => '421d9546',
                        '+' => [
                            'not' => [
                                'title' => 'Any But Public Data',
                                'path' => $id,
                                'active' => false,
                                'stack' => 10
                            ],
                            'is' => [
                                'title' => 'Public Data Only',
                                'path' => $id,
                                'active' => false,
                                'stack' => 10
                            ]
                        ],
                        'stack' => 20.1
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