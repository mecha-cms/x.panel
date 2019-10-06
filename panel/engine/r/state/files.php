<?php

$files = [[], []];
$count = 0;

$search = function($folder) {
    $q = strtolower($_GET['q'] ?? "");
    return $q ? k($folder, preg_split('/\s+/', $q), true) : g($folder);
};

if (is_dir($folder = LOT . strtr($_['path'], '/', DS))) {
    $before = $url . $_['/'] . '::';
    $guest = $user->status !== 1;
    foreach ($search($folder) as $k => $v) {
        $after = '::' . strtr($k, [
            LOT => "",
            DS => '/'
        ]);
        $files[$v][$k] = [
            'path' => $k,
            'type' => $v === 0 ? 'Folder' : 'File',
            'title' => $n = basename($k),
            'description' => $v === 0 ? $language->doEnter : (new File($k))->size,
            'hidden' => strpos('_.', $n[0]) !== false && $guest, // User(s) with status other than `1` cannot see hidden file(s)
            'url' => $before . 'g' . $after . ($v === 0 ? '/1' : "") . $url->query . $url->hash,
            'tasks' => [
                'g' => [
                    'title' => $language->doEdit,
                    'description' => $language->doEdit,
                    'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                    'url' => $before . 'g' . $after . $url->query('&', ['tab' => false]) . $url->hash,
                    'stack' => 10
                ],
                'l' => [
                    'hidden' => $guest, // User(s) with status other than `1` cannot delete file(s)
                    'title' => $language->doDelete,
                    'description' => $language->doDelete,
                    'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                    'url' => $before . 'l' . $after . $url->query('&', ['tab' => false, 'token' => $_['token']]),
                    'stack' => 20
                ]
            ]
        ];
        ++$count;
    }
    asort($files[0]);
    asort($files[1]);
    $files = array_merge($files[0], $files[1]);
}

if (count($_['chop']) > 1 && $_['i'] <= 1) {
    array_unshift($files, [
        'type' => 'Folder',
        'title' => '..',
        'description' => $language->doExit,
        'tags' => ['is:folder'],
        'url' => $url . $_['/'] . '::g::' . dirname($_['path']) . '/1' . $url->query . $url->hash
    ]);
}

return [
    'desk' => [
        // type: Desk
        'lot' => [
            'form' => [
                // type: Form.Post
                '0' => false, // Remove `<form>` wrapper by setting the node name to `false`
                'lot' => [
                    0 => [
                        // type: Section
                        'lot' => [
                            'tasks' => [
                                'type' => 'Tasks.Button',
                                'lot' => [
                                    'blob' => [
                                        'type' => 'Link',
                                        'title' => false,
                                        'description' => $language->doLoadUp,
                                        'icon' => 'M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z',
                                        'url' => $url . $_['/'] . '::s::' . $_['path'] . $url->query('&', ['content' => 'blob', 'tab' => false]) . $url->hash,
                                        'stack' => 10
                                    ],
                                    'file' => [
                                        'type' => 'Link',
                                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                        'url' => $url . $_['/'] . '::s::' . $_['path'] . $url->query('&', ['content' => 'file', 'tab' => false]) . $url->hash,
                                        'stack' => 20
                                    ],
                                    'folder' => [
                                        'type' => 'Link',
                                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                        'url' => $url . $_['/'] . '::s::' . $_['path'] . $url->query('&', ['content' => 'folder', 'tab' => false]) . $url->hash,
                                        'stack' => 30
                                    ]
                                ],
                                'stack' => 10
                            ]
                        ]
                    ],
                    1 => [
                        // type: Section
                        'lot' => [
                            'tabs' => [
                                // type: Tabs
                                'lot' => [
                                    'files' => [
                                        'title' => $language->file(2),
                                        'lot' => [
                                            'files' => [
                                                'type' => 'Files',
                                                'lot' => $files,
                                                'chunk' => $_['chunk'],
                                                'current' => $_['i'],
                                                'stack' => 10
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
                            'pager' => [
                                'type' => 'Pager',
                                'chunk' => $_['chunk'],
                                'count' => $count,
                                'current' => $_['i'],
                                'peek' => $_['peek'],
                                'stack' => 10
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];