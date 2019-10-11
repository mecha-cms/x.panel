<?php

$GLOBALS['_']['form']['data']['time'] = function($value) {
    return (string) (new Date($value ?? $_SERVER['REQUEST_TIME'] ?? time()));
};

$GLOBALS['_']['form']['page']['author'] = $safe = function($value) {
    return _\lot\x\panel\h\w($value);
};

$GLOBALS['_']['form']['page']['id'] = $safe;
$GLOBALS['_']['form']['page']['link'] = $safe;
$GLOBALS['_']['form']['page']['description'] = function($value) {
    return _\lot\x\panel\h\w($value, 'a');
};

$GLOBALS['_']['form']['page']['title'] = $safe;

$GLOBALS['_']['form']['page']['x'] = $safe;

$page = is_file($f = $_['f']) ? new Page($f) : new Page;

$lot = [
    'bar' => [
        // type: Bar
        'lot' => [
            // type: List
            0 => [
                'lot' => [
                    'folder' => [
                        'icon' => 'M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z',
                        'url' => $url . $_['/'] . '::g::' . ($_['task'] === 'g' ? dirname($_['path']) : $_['path']) . '/1' . $url->query('&', ['content' => false, 'tab' => false]) . $url->hash,
                        'lot' => false // Disable sub-menu(s)
                    ],
                    's' => [
                        'hidden' => $_['task'] === 's',
                        'icon' => 'M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z',
                        'title' => false,
                        'description' => $language->doCreate . ' (' . $language->page . ')',
                        'url' => str_replace('::g::', '::s::', dirname($url->clean)) . $url->query('&', ['content' => 'page', 'tab' => false]) . $url->hash,
                        'stack' => 10.5
                    ]
                ]
            ]
        ]
    ],
    'desk' => [
        // type: Desk
        'lot' => [
            'form' => [
                // type: Form.Post
                'lot' => [
                    1 => [
                        // type: Section
                        'lot' => [
                            'tabs' => [
                                // type: Tabs
                                'lot' => [
                                    'page' => [
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => [
                                                    'token' => [
                                                        'type' => 'Hidden',
                                                        'value' => $_['token']
                                                    ],
                                                    'seal' => [
                                                        'type' => 'Hidden',
                                                        'name' => 'file[seal]',
                                                        'value' => '0600'
                                                    ],
                                                    'title' => [
                                                        'type' => 'Text',
                                                        'alt' => $_['task'] === 'g' ? ($page['title'] ?? $language->fieldAltTitle) : $language->fieldAltTitle,
                                                        'focus' => true,
                                                        'name' => 'page[title]',
                                                        'value' => $page['title'],
                                                        'width' => true,
                                                        'stack' => 10
                                                    ],
                                                    'name' => [
                                                        'hidden' => $_['task'] === 's',
                                                        'title' => $language->slug,
                                                        'type' => 'Text',
                                                        'pattern' => "^[a-z\\d]+(-[a-z\\d]+)*$",
                                                        'alt' => To::kebab($_['task'] === 'g' ? ($page->name ?? $language->fieldAltTitle) : $language->fieldAltTitle),
                                                        'name' => 'page[name]',
                                                        'value' => $page->name,
                                                        'width' => true,
                                                        'stack' => 20
                                                    ],
                                                    'content' => [
                                                        'type' => 'Source',
                                                        'alt' => $language->fieldAltContent,
                                                        'name' => 'page[content]',
                                                        'value' => $page['content'],
                                                        'width' => true,
                                                        'height' => true,
                                                        'stack' => 30
                                                    ],
                                                    'description' => [
                                                        'type' => 'Content',
                                                        'alt' => $language->fieldAltDescription,
                                                        'name' => 'page[description]',
                                                        'value' => $page['description'],
                                                        'width' => true,
                                                        'stack' => 40
                                                    ],
                                                    'author' => [
                                                        'type' => 'Hidden',
                                                        'alt' => $page['author'] ?? '@' . S . To::kebab($language->fieldAltAuthor),
                                                        'name' => 'page[author]',
                                                        'value' => $page['author'] ?? $user->user,
                                                        'stack' => 50
                                                    ],
                                                    'type' => [
                                                        'type' => 'Item',
                                                        'name' => 'page[type]',
                                                        'value' => $page->type,
                                                        'lot' => (array) Language::get('field:page-type.lot'),
                                                        'stack' => 60
                                                    ]
                                                ],
                                                'stack' => 10
                                            ]
                                        ],
                                        'stack' => 10
                                    ],
                                    'data' => [
                                        'title' => $language->data(2),
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => [
                                                    'link' => [
                                                        'type' => 'Text',
                                                        'pattern' => "^(https?:)?\\/\\/\\S+$",
                                                        'alt' => $url->protocol . S . $url->host . S,
                                                        'name' => 'page[link]',
                                                        'value' => $page['link'],
                                                        'width' => true,
                                                        'stack' => 10
                                                    ],
                                                    'time' => [
                                                        'hidden' => $_['task'] === 's',
                                                        'type' => 'Text',
                                                        'pattern' => "^[1-9]\\d{3,}-(0\\d|1[0-2])-(0\\d|[1-2]\\d|3[0-1])([ ]([0-1]\\d|2[0-4])(:([0-5]\\d|60)){2})?$",
                                                        'alt' => date('Y-m-d H:i:s'),
                                                        'name' => 'data[time]',
                                                        'value' => $page->time . "",
                                                        'stack' => 20
                                                    ],
                                                    'files' => [
                                                        'hidden' => $_['task'] === 's',
                                                        'type' => 'Field',
                                                        'title' => "",
                                                        'lot' => [
                                                            'files' => [
                                                                'type' => 'Files',
                                                                'tags' => ['mb:1'],
                                                                'lot' => [],
                                                                'stack' => 10
                                                            ],
                                                            'tasks' => [
                                                                'type' => 'Tasks.Link',
                                                                'lot' => [
                                                                    's' => [
                                                                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                                                        'title' => $language->data(2),
                                                                        'url' => $url . $_['/'] . '::s::' . Path::F($_['path'], '/') . $url->query('&', ['content' => 'data', 'tab' => false]) . $url->hash,
                                                                        'stack' => 10
                                                                    ]
                                                                ],
                                                                'stack' => 20
                                                            ]
                                                        ],
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
                        // type: Section
                        'lot' => [
                            'fields' => [
                                'type' => 'Fields',
                                'lot' => [
                                    0 => [
                                        'type' => 'Field',
                                        'title' => "",
                                        'lot' => [
                                            'tasks' => [
                                                'type' => 'Tasks.Button',
                                                'lot' => [
                                                    's' => [
                                                        'hidden' => $_['task'] === 's',
                                                        'type' => 'Submit',
                                                        'title' => $language->doUpdate,
                                                        'name' => 'page[x]',
                                                        'value' => $page->x,
                                                        'stack' => 10
                                                    ],
                                                    'page' => [
                                                        'hidden' => $page->x === 'page',
                                                        'type' => 'Submit',
                                                        'title' => $language->doPublish,
                                                        'name' => 'page[x]',
                                                        'value' => 'page',
                                                        'stack' => 20
                                                    ],
                                                    'draft' => [
                                                        'hidden' => $page->x === 'draft',
                                                        'type' => 'Submit',
                                                        'title' => $language->doSave,
                                                        'name' => 'page[x]',
                                                        'value' => 'draft',
                                                        'stack' => 30
                                                    ],
                                                    'archive' => [
                                                        'hidden' => $_['task'] === 's' || $page->x === 'archive',
                                                        'type' => 'Submit',
                                                        'title' => $language->doArchive,
                                                        'name' => 'page[x]',
                                                        'value' => 'archive',
                                                        'stack' => 40
                                                    ],
                                                    'l' => [
                                                        'hidden' => $_['task'] === 's',
                                                        'type' => 'Link',
                                                        'title' => $language->doDelete,
                                                        'url' => str_replace('::g::', '::l::', $url->clean . $url->query('&', ['tab' => false, 'token' => $_['token']])),
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

Hook::set('set', function() use($_, $language, $page, $url) {
    $apart = [];
    if (!empty($GLOBALS['_']['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs'])) {
        foreach ($GLOBALS['_']['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs'] as $k => $v) {
            foreach ($v as $kk => $vv) {
                if (empty($vv['lot']['fields']['lot'])) {
                    continue;
                }
                foreach ($vv['lot']['fields']['lot'] as $kkk => $vvv) {
                    $vvvv = $vvv['name'] ?? $kkk;
                    if (strpos($vvvv, 'data[') === 0) {
                        $apart[substr($vvvv, 5, -1)] = 1;
                    }
                }
            }
        }
        $files = [];
        if ($page->exist) {
            $p = array_replace(From::page(file_get_contents($path = $page->path)), $apart);
            $before = $url . $_['/'] . '::';
            foreach (g(Path::F($path), 'data') as $k => $v) {
                if ($v === 1 && isset($p[basename($k, '.data')])) {
                    continue;
                }
                $after = '::' . strtr($k, [
                    LOT => "",
                    DS => '/'
                ]);
                $files[$k] = [
                    'path' => $k,
                    'type' => 'File',
                    'title' => $n = basename($k),
                    'description' => (new File($k))->size,
                    'url' => $before . 'g' . $after . $url->query . $url->hash,
                    'tasks' => [
                        'g' => [
                            'title' => $language->doEdit,
                            'description' => $language->doEdit,
                            'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                            'url' => $before . 'g' . $after . $url->query('&', ['tab' => false]) . $url->hash,
                            'stack' => 10
                        ],
                        'l' => [
                            'title' => $language->doDelete,
                            'description' => $language->doDelete,
                            'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                            'url' => $before . 'l' . $after . $url->query('&', ['tab' => false, 'token' => $_['token']]),
                            'stack' => 20
                        ]
                    ]
                ];
            }
            asort($files);
        }
        $GLOBALS['_']['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['files']['lot']['files']['lot'] = $files;
    }
}, 0);

return $lot;