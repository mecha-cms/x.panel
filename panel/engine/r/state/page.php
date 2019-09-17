<?php

$page = is_file($f = $_['f']) ? new Page($f) : new Page;

$files = [];
if ($page->exist) {
    $p = From::page($path = $page->path);
    foreach (g(Path::F($path), 'data') as $k => $v) {
        if ($v === 1 && isset($p[basename($k, '.data')])) {
            continue;
        }
        $files[] = $k;
    }
    sort($files);
}

return [
    'bar' => [
        // type: Bar
        'lot' => [
            // type: List
            0 => [
                'lot' => [
                    's' => [
                        'icon' => 'M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z',
                        'title' => false,
                        'hidden' => $_['task'] === 's',
                        'description' => $language->doCreate . ' (' . $language->page . ')',
                        'url' => str_replace('::g::', '::s::', dirname($url->clean)) . $url->query('&', ['content' => 'page']) . $url->hash,
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
                                        'title' => $language->page,
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => [
                                                    'token' => [
                                                        'type' => 'Hidden',
                                                        'value' => $_['token']
                                                    ],
                                                    'c' => [
                                                        'type' => 'Hidden',
                                                        'value' => $_GET['content'] ?? 'page'
                                                    ],
                                                    'seal' => [
                                                        'type' => 'Hidden',
                                                        'name' => 'file[seal]',
                                                        'value' => '0600'
                                                    ],
                                                    'title' => [
                                                        'title' => $language->title,
                                                        'type' => 'Text',
                                                        'alt' => $_['task'] === 'g' ? ($page['title'] ?? $language->fieldAltTitle) : $language->fieldAltTitle,
                                                        'name' => 'page[title]',
                                                        'value' => $page['title'],
                                                        'width' => true,
                                                        'stack' => 10
                                                    ],
                                                    'name' => [
                                                        'title' => $language->name,
                                                        'type' => 'Text',
                                                        'alt' => \To::kebab($_['task'] === 'g' ? ($page->name ?? $language->fieldAltTitle) : $language->fieldAltTitle),
                                                        'name' => 'page[name]',
                                                        'value' => $page->name,
                                                        'width' => true,
                                                        'stack' => 20
                                                    ],
                                                    'content' => [
                                                        'title' => $language->content,
                                                        'type' => 'Source',
                                                        'alt' => $language->fieldAltContent,
                                                        'name' => 'page[content]',
                                                        'value' => $page['content'],
                                                        'width' => true,
                                                        'height' => true,
                                                        'stack' => 30
                                                    ],
                                                    'description' => [
                                                        'title' => $language->description,
                                                        'type' => 'Content',
                                                        'alt' => $language->fieldAltDescription,
                                                        'name' => 'page[description]',
                                                        'value' => $page['description'],
                                                        'width' => true,
                                                        'stack' => 40
                                                    ],
                                                    'type' => [
                                                        'title' => $language->type,
                                                        'type' => 'Item',
                                                        'name' => 'page[type]',
                                                        'value' => $page->type,
                                                        'lot' => [
                                                            'HTML' => 'HTML',
                                                            'Markdown' => 'Markdown'
                                                        ],
                                                        'stack' => 50
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
                                                        'title' => $language->link,
                                                        'type' => 'Text',
                                                        'pattern' => "^(https?:)?\\/\\/\\S+$",
                                                        'alt' => $url->protocol . S . $url->host . S,
                                                        'name' => 'page[link]',
                                                        'value' => $page['link'],
                                                        'width' => true,
                                                        'stack' => 10
                                                    ],
                                                    'time' => [
                                                        'title' => $language->time,
                                                        'type' => 'Text',
                                                        'hidden' => $_['task'] === 's',
                                                        'pattern' => "^[1-9]\\d{3,}-(0\\d|1[0-2])-(0\\d|[1-2]\\d|3[0-1])([ ]([0-1]\\d|2[0-4])(:([0-5]\\d|60)){2})?$",
                                                        'alt' => date('Y-m-d H:i:s'),
                                                        'name' => 'page[+][time]',
                                                        'value' => $page->time . "",
                                                        'stack' => 20
                                                    ],
                                                    'files' => [
                                                        'title' => false,
                                                        'type' => 'Field',
                                                        'hidden' => $_['task'] === 's',
                                                        'lot' => [
                                                            'files' => [
                                                                'type' => 'Files',
                                                                'tags' => ['mb:1'],
                                                                'lot' => $files,
                                                                'tasks' => function($in) use($_, $language, $url) {
                                                                    $before = $url . $_['//'] . '/::';
                                                                    $after = '::' . strtr($in['path'], [
                                                                        LOT => "",
                                                                        DS => '/'
                                                                    ]);
                                                                    return [
                                                                        'g' => [
                                                                            'title' => $language->doEdit,
                                                                            'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                                                                            'url' => $before . 'g' . $after . $url->query('&', ['content' => 'data', 'tab' => false]) . $url->hash,
                                                                            'stack' => 10
                                                                        ],
                                                                        'l' => [
                                                                            'title' => $language->doDelete,
                                                                            'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                                                                            'url' => $before . 'l' . $after . $url->query('&', ['content' => 'data', 'tab' => false, 'token' => $_['token']]),
                                                                            'stack' => 20
                                                                        ]
                                                                    ];
                                                                },
                                                                'stack' => 10
                                                            ],
                                                            'tasks' => [
                                                                'type' => 'Tasks.Link',
                                                                'lot' => [
                                                                    's' => [
                                                                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                                                                        'title' => $language->data(2),
                                                                        'url' => $url . $_['//'] . '/::s::' . Path::F($_['path']) . $url->query('&', ['content' => 'data', 'tab' => false]) . $url->hash,
                                                                        'stack' => 10
                                                                    ]
                                                                ],
                                                                'stack' => 20
                                                            ]
                                                        ],
                                                        'stack' => 30
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
                                        'title' => false,
                                        'lot' => [
                                            'tasks' => [
                                                'type' => 'Tasks.Button',
                                                'lot' => [
                                                    's' => [
                                                        'type' => 'Submit',
                                                        'title' => $language->{$_['task'] === 'g' ? 'doUpdate' : 'doPublish'},
                                                        'name' => 'x',
                                                        'value' => $page->x,
                                                        'stack' => 10
                                                    ],
                                                    'draft' => [
                                                        'type' => 'Submit',
                                                        'title' => $language->doSave,
                                                        'name' => 'x',
                                                        'value' => 'draft',
                                                        'stack' => 20
                                                    ],
                                                    'archive' => [
                                                        'type' => 'Submit',
                                                        'title' => $language->doArchive,
                                                        'name' => 'x',
                                                        'value' => 'archive',
                                                        'stack' => 30
                                                    ],
                                                    'l' => [
                                                        'type' => 'Link',
                                                        'hidden' => $_['task'] === 's',
                                                        'title' => $language->doDelete,
                                                        'url' => str_replace('::g::', '::l::', $url->clean . $url->query('&', ['content' => 'page', 'token' => $_['token']])),
                                                        'stack' => 40
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