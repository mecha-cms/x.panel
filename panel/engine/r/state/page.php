<?php

if (is_dir($f = $_['f']) && 'g' === $_['task']) {
    Alert::error('Path %s is not a %s.', ['<code>' . _\lot\x\panel\h\path($f) . '</code>', 'file']);
    Guard::kick($url . $_['/'] . '/::g::/' . $_['path'] . $url->query('&', [
        'layout' => false
    ]) . $url->hash);
}

// Sanitize form data
Hook::set(['do.page.get', 'do.page.set'], function($_) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    $_['form']['data']['time'] = (string) (new Time($_['form']['data']['time'] ?? time()));
    $_['form']['page']['author'] = strip_tags($_['form']['page']['author'] ?? "");
    $_['form']['page']['id'] = strip_tags($_['form']['page']['id'] ?? "");
    $_['form']['page']['link'] = strip_tags($_['form']['page']['link'] ?? "");
    $_['form']['page']['description'] = _\lot\x\panel\h\w($_['form']['page']['description'] ?? "", 'a');
    $_['form']['page']['title'] = _\lot\x\panel\h\w($_['form']['page']['title'] ?? "");
    $_['form']['page']['x'] = strip_tags($_['form']['page']['x'] ?? 'page');
    if (empty($_['form']['page']['name'])) {
        $name = To::kebab($_['form']['page']['title'] ?? "");
        $_['form']['page']['name'] = "" !== $name ? $name : date('Y-m-d-H-i-s');
    }
    // Detect `time` pattern in the page’s file name and remove the `time` field if matched
    $n = $_['form']['page']['name'];
    if (
        is_string($n) && (
            // `2017-04-21.page`
            2 === substr_count($n, '-') ||
            // `2017-04-21-14-25-00.page`
            5 === substr_count($n, '-')
        ) &&
        is_numeric(str_replace('-', "", $n)) &&
        preg_match('/^[1-9]\d{3,}-(0\d|1[0-2])-(0\d|[1-2]\d|3[0-1])(-([0-1]\d|2[0-4])(-([0-5]\d|60)){2})?$/', $n)
    ) {
        unset($_['form']['data']['time']);
    }
    return $_;
}, 9.9);

$page = is_file($f) ? new Page($f) : new Page;

$trash = $_['trash'] ? date('Y-m-d-H-i-s') : false;

$lot = [
    'bar' => [
        // type: bar
        'lot' => [
            // type: bar/menu
            0 => [
                'lot' => [
                    'folder' => ['hidden' => true],
                    'link' => [
                        'url' => $url . $_['/'] . '/::g::/' . ('g' === $_['task'] ? dirname($_['path']) : $_['path']) . '/1' . $url->query('&', ['layout' => false, 'tab' => false]) . $url->hash,
                        'hidden' => false
                    ],
                    's' => [
                        'hidden' => 's' === $_['task'],
                        'icon' => 'M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z',
                        'title' => false,
                        'description' => ['New %s', 'Page'],
                        'url' => str_replace('::g::', '::s::', dirname($url->clean)) . $url->query('&', ['layout' => 'page', 'tab' => false]) . $url->hash,
                        'stack' => 10.5
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
                    'fields' => [
                        'type' => 'fields',
                        'lot' => [ // Hidden field(s)
                            'token' => [
                                'type' => 'hidden',
                                'value' => $_['token']
                            ],
                            'seal' => [
                                'type' => 'hidden',
                                'name' => 'file[seal]',
                                'value' => '0600'
                            ]
                        ],
                        'stack' => -1
                    ],
                    1 => [
                        // type: section
                        'lot' => [
                            'tabs' => [
                                // type: tabs
                                'lot' => [
                                    'page' => [
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'fields',
                                                'lot' => [
                                                    'title' => [
                                                        'type' => 'text',
                                                        'alt' => 'g' === $_['task'] ? ($page['title'] ?? 'Title Goes Here') : 'Title Goes Here',
                                                        'focus' => true,
                                                        'name' => 'page[title]',
                                                        'value' => $page['title'],
                                                        'width' => true,
                                                        'stack' => 10
                                                    ],
                                                    'name' => [
                                                        'title' => 'Slug',
                                                        'type' => 'text',
                                                        'pattern' => "^[a-z\\d]+(-[a-z\\d]+)*$",
                                                        'alt' => To::kebab('g' === $_['task'] ? ($page->name ?? 'Title Goes Here') : 'Title Goes Here'),
                                                        'name' => 'page[name]',
                                                        'value' => $page->name,
                                                        'width' => true,
                                                        'hidden' => 's' === $_['task'],
                                                        'stack' => 20
                                                    ],
                                                    'content' => [
                                                        'type' => 'source',
                                                        'name' => 'page[content]',
                                                        'alt' => 'Content goes here...',
                                                        'value' => $page['content'],
                                                        'width' => true,
                                                        'height' => true,
                                                        'stack' => 30
                                                    ],
                                                    'description' => [
                                                        'type' => 'content',
                                                        'name' => 'page[description]',
                                                        'alt' => 'Description goes here...',
                                                        'value' => $page['description'],
                                                        'width' => true,
                                                        'stack' => 40
                                                    ],
                                                    'author' => [
                                                        'type' => 'hidden',
                                                        'name' => 'page[author]',
                                                        'alt' => $page['author'] ?? '@' . S . To::kebab(i('John Doe')),
                                                        'value' => $page['author'] ?? $user->user,
                                                        'stack' => 50
                                                    ],
                                                    'type' => [
                                                        'type' => 'item',
                                                        'name' => 'page[type]',
                                                        'value' => $page->type,
                                                        'lot' => [
                                                            'HTML' => 'HTML',
                                                            'Markdown' => null !== State::get('x.markdown') ? 'Markdown' : null
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
                                                        'hidden' => 's' === $_['task'],
                                                        'stack' => 20
                                                    ],
                                                    'files' => [
                                                        'title' => "",
                                                        'type' => 'field',
                                                        'lot' => [
                                                            'files' => [
                                                                'type' => 'files',
                                                                'tags' => ['mb:1'],
                                                                'lot' => [],
                                                                'stack' => 10
                                                            ],
                                                            'tasks' => [
                                                                'type' => 'tasks/link',
                                                                'lot' => [
                                                                    's' => [
                                                                        'title' => 'Data',
                                                                        'url' => $url . $_['/'] . '/::s::/' . Path::F($_['path'], '/') . $url->query('&', ['layout' => 'data', 'tab' => false]) . $url->hash,
                                                                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                                                        'stack' => 10
                                                                    ]
                                                                ],
                                                                'stack' => 20
                                                            ]
                                                        ],
                                                        'hidden' => 's' === $_['task'],
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
                                                        'hidden' => 's' === $_['task'],
                                                        'stack' => 10
                                                    ],
                                                    'page' => [
                                                        'title' => 'Publish',
                                                        'type' => 'submit',
                                                        'name' => 'page[x]',
                                                        'value' => 'page',
                                                        'hidden' => 'page' === $x,
                                                        'stack' => 20
                                                    ],
                                                    'draft' => [
                                                        'title' => 'Save',
                                                        'description' => ['Save as %s', 'Draft'],
                                                        'type' => 'submit',
                                                        'name' => 'page[x]',
                                                        'value' => 'draft',
                                                        'hidden' => 'draft' === $x,
                                                        'stack' => 30
                                                    ],
                                                    'archive' => [
                                                        'title' => 'Archive',
                                                        'description' => ['Save as %s', 'Archive'],
                                                        'type' => 'submit',
                                                        'name' => 'page[x]',
                                                        'value' => 'archive',
                                                        'hidden' => 'archive' === $x || 's' === $_['task'],
                                                        'stack' => 40
                                                    ],
                                                    'l' => [
                                                        'title' => 'Delete',
                                                        'type' => 'link',
                                                        'url' => str_replace('::g::', '::l::', $url->clean . $url->query('&', [
                                                            'tab' => false,
                                                            'token' => $_['token'],
                                                            'trash' => $trash
                                                        ])),
                                                        'hidden' => 's' === $_['task'],
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
    ]
];

Hook::set('_', function($_) use($page, $trash, $url) {
    $apart = [];
    if (!empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs'])) {
        foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs'] as $k => $v) {
            foreach ($v as $kk => $vv) {
                if (empty($vv['lot']['fields']['lot'])) {
                    continue;
                }
                foreach ($vv['lot']['fields']['lot'] as $kkk => $vvv) {
                    $vvvv = $vvv['name'] ?? $kkk;
                    if (0 === strpos($vvvv, 'data[')) {
                        $apart[substr($vvvv, 5, -1)] = 1;
                    }
                }
            }
        }
        $files = [];
        if ($page->exist) {
            $p = array_replace(From::page(file_get_contents($path = $page->path)), $apart);
            $before = $url . $_['/'] . '/::';
            foreach (g(Path::F($path), 'data') as $k => $v) {
                if (1 === $v && isset($p[basename($k, '.data')])) {
                    continue;
                }
                $after = '::' . strtr($k, [
                    LOT => "",
                    DS => '/'
                ]);
                $files[$k] = [
                    'path' => $k,
                    'title' => $n = basename($k),
                    'description' => (new File($k))->size,
                    'type' => 'file',
                    'url' => $before . 'g' . $after . $url->query . $url->hash,
                    'tasks' => [
                        'g' => [
                            'title' => 'Edit',
                            'description' => 'Edit',
                            'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                            'url' => $before . 'g' . $after . $url->query('&', ['tab' => false]) . $url->hash,
                            'stack' => 10
                        ],
                        'l' => [
                            'title' => 'Delete',
                            'description' => 'Delete',
                            'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                            'url' => $before . 'l' . $after . $url->query('&', [
                                'tab' => false,
                                'token' => $_['token'],
                                'trash' => $trash
                            ]),
                            'stack' => 20
                        ]
                    ]
                ];
            }
            asort($files);
        }
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['files']['lot']['files']['lot'] = $files;
    }
    return $_;
}, 20);

return $lot;
