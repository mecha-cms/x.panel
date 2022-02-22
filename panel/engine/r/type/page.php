<?php

if (is_dir($f = $_['f']) && 'g' === $_['task']) {
    $_['alert']['error'][] = ['Path %s is not a %s.', ['<code>' . x\panel\from\path($f) . '</code>', 'file']];
    $_['kick'] = $_['/'] . '/::g::/' . $_['path'] . $url->query('&', [
        'q' => false,
        'tab' => false,
        'type' => false
    ]) . $url->hash;
    return $_;
}

$page = is_file($f) ? new Page($f) : new Page;

$trash = $_['trash'] ? date('Y-m-d-H-i-s') : false;

// Sanitize the form data
if ('post' === $_['form']['type']) {
    // Get current time
    $time = $_SERVER['REQUEST_TIME'] ?? time();
    // Set `time` data based on the current `time` data or use the `$time` value
    $_['form']['lot']['data']['time'] = (string) new Time($_['form']['lot']['data']['time'] ?? $time);
    // Remove all possible HTML tag(s) from the `author` data
    $_['form']['lot']['page']['author'] = strip_tags($_['form']['lot']['page']['author'] ?? "");
    // Remove all possible HTML tag(s) from the `id` data if any
    if (isset($_['form']['lot']['page']['id'])) {
        $_['form']['lot']['page']['id'] = strip_tags($_['form']['lot']['page']['id']);
    }
    // Remove all possible HTML tag(s) from the `link` data if any
    if (isset($_['form']['lot']['page']['link'])) {
        $_['form']['lot']['page']['link'] = strip_tags($_['form']['lot']['page']['link']);
    }
    // Remove all possible block HTML tag(s) from the `description` data if any
    if (isset($_['form']['lot']['page']['description'])) {
        $_['form']['lot']['page']['description'] = x\panel\to\w($_['form']['lot']['page']['description'], 'a');
        // Limit `description` data value to 1275 character(s) length
        $_['form']['lot']['page']['description'] = To::description($_['form']['lot']['page']['description'], true, 1275);
    }
    // Remove all possible block HTML tag(s) from the `title` data if any
    if (isset($_['form']['lot']['page']['title'])) {
        $_['form']['lot']['page']['title'] = x\panel\to\w($_['form']['lot']['page']['title']);
        // Limit `title` data value to 255 character(s) length
        $_['form']['lot']['page']['title'] = To::description($_['form']['lot']['page']['title'], true, 255);
    }
    // Make sure to have a file extension
    $_['form']['lot']['page']['x'] = strip_tags($_['form']['lot']['page']['x'] ?? 'page');
    // Make sure to have a file name
    if (empty($_['form']['lot']['page']['name'])) {
        $name = To::kebab($_['form']['lot']['page']['title'] ?? "");
        $_['form']['lot']['page']['name'] = "" !== $name ? $name : date('Y-m-d-H-i-s', $time);
    }
    // Detect `time` pattern in the page’s file name and remove the `time` data if matched
    $n = $_['form']['lot']['page']['name'];
    if (
        is_string($n) && (
            // `2017-04-21.page`
            2 === substr_count($n, '-') ||
            // `2017-04-21-14-25-00.page`
            5 === substr_count($n, '-')
        ) &&
        is_numeric(strtr($n, ['-' => ""])) &&
        preg_match('/^[1-9]\d{3,}-(0\d|1[0-2])-(0\d|[1-2]\d|3[0-1])(-([0-1]\d|2[0-4])(-([0-5]\d|60)){2})?$/', $n)
    ) {
        unset($_['form']['lot']['data']['time']);
    }
}

$bar = [
    // type: bar
    'lot' => [
        // type: links
        0 => [
            'lot' => [
                'folder' => ['skip' => true],
                'link' => [
                    'url' => $_['/'] . '/::g::/' . ('g' === $_['task'] ? (0 === q(g(Path::F($f), 'archive,draft,page')) ? dirname($_['path']) : Path::F($_['path'])) : $_['path']) . '/1' . $url->query('&', [
                        'q' => false,
                        'tab' => false,
                        'type' => false
                    ]) . $url->hash,
                    'skip' => false
                ],
                's' => [
                    'icon' => 'M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z',
                    'title' => false,
                    'description' => ['New %s', 'Page'],
                    'url' => strtr(dirname($url->clean), ['::g::' => '::s::']) . $url->query('&', [
                        'q' => false,
                        'tab' => false,
                        'type' => 'page'
                    ]) . $url->hash,
                    'skip' => 's' === $_['task'],
                    'stack' => 10.5
                ]
            ]
        ]
    ]
];

$desk = [
    // type: desk
    'lot' => [
        'form' => [
            // type: form/post
            'data' => [
                'file' => ['seal' => '0600'],
                'token' => $_['token'],
                'type' => $_['type']
            ],
            'lot' => [
                1 => [
                    // type: section
                    'lot' => [
                        'tabs' => [
                            // type: tabs
                            'gap' => false,
                            'lot' => [
                                'page' => [
                                    'lot' => [
                                        'fields' => [
                                            'type' => 'fields',
                                            'lot' => [
                                                'title' => [
                                                    'type' => 'title',
                                                    'focus' => true,
                                                    'name' => 'page[title]',
                                                    'value' => $page['title'],
                                                    'width' => true,
                                                    'stack' => 10
                                                ],
                                                'name' => [
                                                    'title' => 'Slug',
                                                    'type' => 'name',
                                                    'x' => false,
                                                    'hint' => To::kebab('g' === $_['task'] ? ($page->name ?? 'Title Goes Here') : 'Title Goes Here'),
                                                    'name' => 'page[name]',
                                                    'value' => $page->name,
                                                    'width' => true,
                                                    'skip' => 's' === $_['task'],
                                                    'stack' => 20
                                                ],
                                                'content' => [
                                                    'type' => 'source',
                                                    'name' => 'page[content]',
                                                    'value' => $page['content'],
                                                    'width' => true,
                                                    'height' => true,
                                                    'state' => [
                                                        'source' => [
                                                            'type' => $page->type
                                                        ]
                                                    ],
                                                    'stack' => 30
                                                ],
                                                'description' => [
                                                    'type' => 'description',
                                                    'name' => 'page[description]',
                                                    'value' => $page['description'],
                                                    'width' => true,
                                                    'stack' => 40
                                                ],
                                                'author' => [
                                                    'type' => 'hidden',
                                                    'name' => 'page[author]',
                                                    'hint' => $page['author'] ?? '@' . S . To::kebab(i('John Doe')),
                                                    'value' => $page['author'] ?? $user->user,
                                                    'stack' => 50
                                                ],
                                                'type' => [
                                                    'type' => 'item',
                                                    'name' => 'page[type]',
                                                    'value' => 'text/html' === $page->type ? 'HTML' : $page->type,
                                                    'lot' => [
                                                        'HTML' => 'HTML',
                                                        'Markdown' => isset($state->x->markdown) ? 'Markdown' : null
                                                    ],
                                                    'stack' => 60
                                                ]
                                            ],
                                            'stack' => 10
                                        ]
                                    ],
                                    'stack' => 10
                                ],
                                'data' => [
                                    'lot' => [
                                        'fields' => [
                                            'type' => 'fields',
                                            'lot' => [
                                                'link' => [
                                                    'type' => 'link',
                                                    'name' => 'page[link]',
                                                    'value' => $page['link'],
                                                    'width' => true,
                                                    'stack' => 10
                                                ],
                                                'time' => [
                                                    'type' => 'date-time',
                                                    'name' => 'data[time]',
                                                    'value' => $page['time'],
                                                    'skip' => 's' === $_['task'],
                                                    'stack' => 20
                                                ],
                                                'files' => [
                                                    'title' => "",
                                                    'type' => 'field',
                                                    'lot' => [
                                                        'files' => [
                                                            'type' => 'files',
                                                            'lot' => [],
                                                            'stack' => 10
                                                        ],
                                                        'tasks' => [
                                                            'type' => 'tasks/link',
                                                            '0' => 'p',
                                                            'lot' => [
                                                                's' => [
                                                                    'title' => 'Data',
                                                                    'url' => $_['/'] . '/::s::/' . Path::F($_['path'], '/') . $url->query('&', [
                                                                        'q' => false,
                                                                        'tab' => false,
                                                                        'type' => 'data'
                                                                    ]) . $url->hash,
                                                                    'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                                                    'stack' => 10
                                                                ]
                                                            ],
                                                            'stack' => 20
                                                        ]
                                                    ],
                                                    'skip' => 's' === $_['task'],
                                                    'stack' => 100
                                                ]
                                            ],
                                            'stack' => 10
                                        ]
                                    ],
                                    'stack' => 20
                                ]
                            ]
                        ]
                    ]
                ],
                2 => [
                    // type: section
                    'lot' => [
                        'fields' => [
                            'type' => 'fields',
                            'lot' => [
                                0 => [
                                    'title' => "",
                                    'type' => 'field',
                                    'lot' => [
                                        'tasks' => [
                                            'type' => 'tasks/button',
                                            'lot' => [
                                                's' => [
                                                    'title' => 'Update',
                                                    'description' => ['Update as %s', ucfirst($x = $page->x)],
                                                    'type' => 'submit',
                                                    'name' => 'page[x]',
                                                    'value' => $x,
                                                    'skip' => 's' === $_['task'],
                                                    'stack' => 10
                                                ],
                                                'page' => [
                                                    'title' => 'Publish',
                                                    'type' => 'submit',
                                                    'name' => 'page[x]',
                                                    'value' => 'page',
                                                    'skip' => 'page' === $x,
                                                    'stack' => 20
                                                ],
                                                'draft' => [
                                                    'title' => 'Save',
                                                    'description' => ['Save as %s', 'Draft'],
                                                    'type' => 'submit',
                                                    'name' => 'page[x]',
                                                    'value' => 'draft',
                                                    'skip' => 'draft' === $x,
                                                    'stack' => 30
                                                ],
                                                'archive' => [
                                                    'title' => 'Archive',
                                                    'description' => ['Save as %s', 'Archive'],
                                                    'type' => 'submit',
                                                    'name' => 'page[x]',
                                                    'value' => 'archive',
                                                    'skip' => 'archive' === $x || 's' === $_['task'],
                                                    'stack' => 40
                                                ],
                                                'l' => [
                                                    'title' => 'Delete',
                                                    'type' => 'link',
                                                    'url' => strtr($url->clean . $url->query('&', [
                                                        'q' => false,
                                                        'tab' => false,
                                                        'token' => $_['token'],
                                                        'trash' => $trash
                                                    ]), ['::g::' => '::l::']),
                                                    'skip' => 's' === $_['task'],
                                                    'stack' => 50
                                                ]
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
];

$session = $_SESSION['_']['file'] ?? [];

Hook::set('_', function($_) use($page, $session, $trash, $url) {
    $apart = [];
    if (!empty($_['lot']['desk']['lot']['form']['data'])) {
        foreach ($_['lot']['desk']['lot']['form']['data'] as $k => $v) {
            if ('data' === $k && is_array($v)) {
                $apart = array_replace($apart, $v);
                continue;
            }
            if (0 === strpos($k, 'data[')) {
                $apart[substr(explode(']', $k, 2)[0], 5)] = 1;
            }
        }
    }
    if (!empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot'])) {
        foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot'] as $k => $v) {
            foreach ($v as $kk => $vv) {
                if (empty($vv['fields']['lot'])) {
                    continue;
                }
                foreach ($vv['fields']['lot'] as $kkk => $vvv) {
                    $vvvv = $vvv['name'] ?? $kkk;
                    if (0 === strpos($vvvv, 'data[')) {
                        $apart[substr(explode(']', $vvvv, 2)[0], 5)] = 1;
                    }
                }
            }
        }
        $count = 0;
        $files = [];
        if ($page->exist) {
            $p = array_replace(From::page(file_get_contents($path = $page->path)), $apart);
            $before = $_['/'] . '/::';
            foreach (g(Path::F($path), 'data') as $k => $v) {
                $after = '::' . strtr($k, [
                    LOT => "",
                    DS => '/'
                ]);
                if (!$skip = isset($p[basename($k, '.data')])) {
                    ++$count;
                }
                $files[$k] = [
                    'path' => $k,
                    'current' => !empty($session[$k]),
                    'title' => S . ($n = basename($k)) . S,
                    'description' => (new File($k))->size,
                    'type' => 'file',
                    'tags' => [
                        'x:data' => true
                    ],
                    'url' => $before . 'g' . $after . $url->query('&', [
                        'q' => false,
                        'tab' => false
                    ]) . $url->hash,
                    'tasks' => [
                        'g' => [
                            'title' => 'Edit',
                            'description' => 'Edit',
                            'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                            'url' => $before . 'g' . $after . $url->query('&', [
                                'q' => false,
                                'tab' => false
                            ]) . $url->hash,
                            'stack' => 10
                        ],
                        'l' => [
                            'title' => 'Delete',
                            'description' => 'Delete',
                            'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                            'url' => $before . 'l' . $after . $url->query('&', [
                                'q' => false,
                                'tab' => false,
                                'token' => $_['token'],
                                'trash' => $trash
                            ]),
                            'stack' => 20
                        ]
                    ],
                    'skip' => $skip
                ];
                if (isset($session[$k])) {
                    unset($_SESSION['_']['file'][$k]);
                }
            }
            ksort($files);
        }
        if ($count) {
            $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['files']['lot']['files']['lot'] = $files;
        } else {
            $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['files']['lot']['files']['skip'] = true;
        }
    }
    return $_;
}, 20);

return ($_ = array_replace_recursive($_, [
    'lot' => [
        'bar' => $bar,
        'desk' => $desk
    ]
]));