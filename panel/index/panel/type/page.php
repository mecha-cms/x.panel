<?php

if (is_dir($file = $_['file'] ?? $_['folder']) && 'get' === $_['task']) {
    $_['alert']['error'][$file] = ['Path %s is not a %s.', ['<code>' . x\panel\from\path($file) . '</code>', 'file']];
    $_['kick'] = x\panel\to\link([
        'part' => 1,
        'path' => dirname($_['path']),
        'query' => [
            'query' => null,
            'stack' => null,
            'tab' => null,
            'type' => null
        ],
        'task' => 'get'
    ]);
    return $_;
}

$_['page'] = $page = is_file($file) ? new Page($file) : new Page;

$has_folder = is_dir($folder = dirname($file) . D . pathinfo($file, PATHINFO_FILENAME));

// Sanitize the form data
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    // Get current time
    $time = $_SERVER['REQUEST_TIME'] ?? time();
    // Set `time` data based on the current `time` data or use the `$time` value
    $_POST['data']['time'] = (string) new Time($_POST['data']['time'] ?? $time);
    // Remove all HTML tag(s) from the `author` data
    $_POST['page']['author'] = strip_tags($_POST['page']['author'] ?? "");
    if (isset($_POST['page']['id'])) {
        // Remove all HTML tag(s) from the `id` data if any
        $_POST['page']['id'] = strip_tags($_POST['page']['id']);
    }
    if (isset($_POST['page']['link'])) {
        // Remove all HTML tag(s) from the `link` data if any
        $_POST['page']['link'] = strip_tags($_POST['page']['link']);
    }
    if (isset($_POST['page']['description'])) {
        // Remove all block HTML tag(s) from the `description` data if any
        $_POST['page']['description'] = x\panel\to\w($_POST['page']['description'], 'a');
        // Limit `description` data value to 400 character(s)
        $_POST['page']['description'] = To::description($_POST['page']['description'], 400);
    }
    if (isset($_POST['page']['title'])) {
        // Remove all block HTML tag(s) from the `title` data if any
        $_POST['page']['title'] = x\panel\to\w($_POST['page']['title']);
        // Limit `title` data value to 200 character(s)
        $_POST['page']['title'] = To::description($_POST['page']['title'], 200);
    }
    // Make sure to have a file extension
    $_POST['page']['x'] = strip_tags($_POST['page']['x'] ?? 'page');
    // Make sure to have a file name
    if (empty($_POST['page']['name'])) {
        $name = To::kebab($_POST['page']['title'] ?? "");
        $_POST['page']['name'] = "" !== $name ? $name : date('Y-m-d-H-i-s', $time);
    }
    // Detect `time` pattern in the page’s file name and remove the `time` data if matched
    $n = $_POST['page']['name'];
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
        unset($_POST['data']['time']);
    }
}

$trash = !empty($state->x->panel->guard->trash) ? date('Y-m-d-H-i-s') : null;

$bar = [
    // `bar`
    'lot' => [
        // `links`
        0 => [
            'lot' => [
                'folder' => ['skip' => true],
                'link' => [
                    'skip' => false,
                    'url' => x\panel\to\link([
                        'part' => 1,
                        'path' => 'get' === $_['task'] ? (0 === q(g($folder, 'archive,draft,page')) ? dirname($_['path']) : dirname($_['path']) . '/' . pathinfo($_['path'], PATHINFO_FILENAME)) : $_['path'],
                        'query' => [
                            'query' => null,
                            'stack' => null,
                            'tab' => null,
                            'type' => null
                        ],
                        'task' => 'get'
                    ])
                ],
                'set' => [
                    'description' => ['New %s', 'Page'],
                    'icon' => 'M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z',
                    'skip' => 'set' === $_['task'],
                    'stack' => 10.5,
                    'title' => false,
                    'url' => x\panel\to\link([
                        'path' => dirname($_['path']),
                        'query' => [
                            'query' => null,
                            'stack' => null,
                            'tab' => null,
                            'type' => 'page'
                        ],
                        'task' => 'set'
                    ])
                ]
            ]
        ]
    ]
];

$desk = [
    // `desk`
    'lot' => [
        'form' => [
            // `form/post`
            'data' => [
                'file' => ['seal' => '0600'],
                'token' => $_['token'],
                'trash' => $trash,
                'type' => $_['type']
            ],
            'lot' => [
                1 => [
                    // `section`
                    'lot' => [
                        'tabs' => [
                            // `tabs`
                            'gap' => false,
                            'lot' => [
                                'page' => [
                                    'lot' => [
                                        'fields' => [
                                            'lot' => [
                                                'title' => [
                                                    'focus' => true,
                                                    'name' => 'page[title]',
                                                    'stack' => 10,
                                                    'type' => 'title',
                                                    'value' => $page['title'],
                                                    'width' => true
                                                ],
                                                'name' => [
                                                    'hint' => To::kebab('get' === $_['task'] ? ($page->name ?? 'Title Goes Here') : 'Title Goes Here'),
                                                    'name' => 'page[name]',
                                                    'skip' => 'set' === $_['task'],
                                                    'stack' => 20,
                                                    'title' => 'Slug',
                                                    'type' => 'name',
                                                    'value' => $page->name,
                                                    'width' => true,
                                                    'x' => false
                                                ],
                                                'content' => [
                                                    'height' => true,
                                                    'name' => 'page[content]',
                                                    'stack' => 30,
                                                    'state' => [
                                                        'source' => [
                                                            'type' => $page->type
                                                        ]
                                                    ],
                                                    'type' => 'source',
                                                    'value' => $page['content'],
                                                    'width' => true
                                                ],
                                                'description' => [
                                                    'name' => 'page[description]',
                                                    'stack' => 40,
                                                    'type' => 'description',
                                                    'value' => $page['description'],
                                                    'width' => true
                                                ],
                                                'author' => [
                                                    'hint' => $page['author'] ?? '@' . S . To::kebab(i('John Doe')),
                                                    'name' => 'page[author]',
                                                    'stack' => 50,
                                                    'type' => 'hidden',
                                                    'value' => $page['author'] ?? $user->user
                                                ],
                                                'type' => [
                                                    'lot' => [
                                                        'HTML' => 'HTML',
                                                        'Markdown' => isset($state->x->markdown) ? 'Markdown' : null
                                                    ],
                                                    'name' => 'page[type]',
                                                    'stack' => 60,
                                                    'type' => 'item',
                                                    'value' => 'text/html' === $page->type ? 'HTML' : $page->type
                                                ]
                                            ],
                                            'stack' => 10,
                                            'type' => 'fields'
                                        ]
                                    ],
                                    'stack' => 10
                                ],
                                'data' => [
                                    'lot' => [
                                        'fields' => [
                                            'lot' => [
                                                'link' => [
                                                    'name' => 'page[link]',
                                                    'stack' => 10,
                                                    'type' => 'link',
                                                    'value' => $page['link'],
                                                    'width' => true
                                                ],
                                                'time' => [
                                                    'name' => 'data[time]',
                                                    'skip' => 'set' === $_['task'],
                                                    'stack' => 20,
                                                    'type' => 'date-time',
                                                    'value' => $page['time']
                                                ],
                                                'files' => [
                                                    'lot' => [
                                                        'files' => [
                                                            'lot' => [],
                                                            'stack' => 10,
                                                            'type' => 'files'
                                                        ],
                                                        'tasks' => [
                                                            '0' => 'p',
                                                            'lot' => [
                                                                'set' => [
                                                                    'active' => $has_folder,
                                                                    'description' => $has_folder ? ['New %s', 'Data'] : ['Missing folder %s', x\panel\from\path($folder)],
                                                                    'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                                                    'stack' => 10,
                                                                    'title' => 'Data',
                                                                    'url' => $has_folder ? x\panel\to\link([
                                                                        'part' => 0,
                                                                        'path' => dirname($_['path']) . '/' . pathinfo($_['path'], PATHINFO_FILENAME),
                                                                        'query' => [
                                                                            'query' => null,
                                                                            'stack' => null,
                                                                            'tab' => null,
                                                                            'type' => 'data'
                                                                        ],
                                                                        'task' => 'set'
                                                                    ]) : null
                                                                ]
                                                            ],
                                                            'stack' => 20,
                                                            'type' => 'tasks/link'
                                                        ]
                                                    ],
                                                    'skip' => 'set' === $_['task'],
                                                    'stack' => 100,
                                                    'title' => "",
                                                    'type' => 'field'
                                                ]
                                            ],
                                            'stack' => 10,
                                            'type' => 'fields'
                                        ]
                                    ],
                                    'stack' => 20
                                ]
                            ]
                        ]
                    ]
                ],
                2 => [
                    // `section`
                    'lot' => [
                        'fields' => [
                            'lot' => [
                                0 => [
                                    'lot' => [
                                        'tasks' => [
                                            'lot' => [
                                                'set' => [
                                                    'description' => ['Update as %s', ucfirst($x = $page->x)],
                                                    'name' => 'page[x]',
                                                    'skip' => 'set' === $_['task'],
                                                    'stack' => 10,
                                                    'title' => 'Update',
                                                    'type' => 'submit',
                                                    'value' => $x
                                                ],
                                                'page' => [
                                                    'name' => 'page[x]',
                                                    'skip' => 'page' === $x,
                                                    'stack' => 20,
                                                    'title' => 'Publish',
                                                    'type' => 'submit',
                                                    'value' => 'page'
                                                ],
                                                'draft' => [
                                                    'description' => ['Save as %s', 'Draft'],
                                                    'name' => 'page[x]',
                                                    'skip' => 'draft' === $x,
                                                    'stack' => 30,
                                                    'title' => 'Save',
                                                    'type' => 'submit',
                                                    'value' => 'draft'
                                                ],
                                                'archive' => [
                                                    'description' => ['Save as %s', 'Archive'],
                                                    'name' => 'page[x]',
                                                    'skip' => 'archive' === $x || 'set' === $_['task'],
                                                    'stack' => 40,
                                                    'title' => 'Archive',
                                                    'type' => 'submit',
                                                    'value' => 'archive'
                                                ],
                                                'let' => [
                                                    'name' => 'task',
                                                    'skip' => 'set' === $_['task'],
                                                    'stack' => 50,
                                                    'title' => 'Delete',
                                                    'value' => 'let',
                                                ]
                                            ],
                                            'type' => 'tasks/button'
                                        ]
                                    ],
                                    'title' => "",
                                    'type' => 'field'
                                ]
                            ],
                            'stack' => 10,
                            'type' => 'fields'
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
            foreach (g(dirname($path) . D . pathinfo($path, PATHINFO_FILENAME), 'data') as $k => $v) {
                $pp = strtr($k, [
                    LOT . D => "",
                    D => '/'
                ]);
                if (!$skip = isset($p[basename($k, '.data')])) {
                    ++$count;
                }
                $files[$k] = [
                    'current' => !empty($session[$k]),
                    'description' => size(filesize($k)),
                    'path' => $k,
                    'skip' => $skip,
                    'tags' => [
                        'x:data' => true
                    ],
                    'tasks' => [
                        'get' => [
                            'description' => 'Edit',
                            'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                            'stack' => 10,
                            'title' => 'Edit',
                            'url' => x\panel\to\link([
                                'part' => 0,
                                'path' => $pp,
                                'query' => [
                                    'query' => null,
                                    'stack' => null,
                                    'tab' => null,
                                    'type' => null
                                ],
                                'task' => 'get'
                            ])
                        ],
                        'let' => [
                            'title' => 'Delete',
                            'description' => 'Delete',
                            'stack' => 20,
                            'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                            'url' => x\panel\to\link([
                                'part' => 0,
                                'path' => $pp,
                                'query' => [
                                    'query' => null,
                                    'stack' => null,
                                    'tab' => null,
                                    'token' => $_['token'],
                                    'trash' => $trash,
                                    'type' => null
                                ],
                                'task' => 'let'
                            ])
                        ]
                    ],
                    'title' => S . ($n = basename($k)) . S,
                    'type' => 'file',
                    'url' => x\panel\to\link([
                        'part' => 0,
                        'path' => $pp,
                        'query' => [
                            'query' => null,
                            'stack' => null,
                            'tab' => null,
                            'type' => null
                        ],
                        'task' => 'get'
                    ])
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