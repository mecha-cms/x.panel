<?php

// `http://127.0.0.1/panel/::g::/user/foo-bar.page`
$GLOBALS['_']['content'] = $_['content'] = [
    'archive' => 'page',
    'data' => 'data',
    'draft' => 'page',
    'page' => 'page'
][pathinfo($_['f'], PATHINFO_EXTENSION)] ?? (is_dir($_['f']) ? 'folder' : 'file');

$fields = array_replace_recursive(require __DIR__ . DS . '..' . DS . 'page.php', [
    'bar' => [
        // type: Bar
        'lot' => [
            // type: List
            0 => [
                'lot' => [
                    's' => [
                        'icon' => 'M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M6,10V7H4V10H1V12H4V15H6V12H9V10M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12Z',
                        'description' => $language->doCreate . ' (' . $language->user . ')'
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
                                        'title' => $language->user,
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
                                                    'title' => ['hidden' => true],
                                                    'author' => [
                                                        'type' => 'Text',
                                                        'alt' => $_['task'] === 'g' ? ($page['author'] ?? $language->fieldAltAuthor) : $language->fieldAltAuthor,
                                                        'name' => 'page[author]',
                                                        'value' => $page['author'],
                                                        'width' => true,
                                                        'stack' => 10
                                                    ],
                                                    'name' => [
                                                        'type' => 'Hidden',
                                                        'before' => ['icon' => 'M12,15C12.81,15 13.5,14.7 14.11,14.11C14.7,13.5 15,12.81 15,12C15,11.19 14.7,10.5 14.11,9.89C13.5,9.3 12.81,9 12,9C11.19,9 10.5,9.3 9.89,9.89C9.3,10.5 9,11.19 9,12C9,12.81 9.3,13.5 9.89,14.11C10.5,14.7 11.19,15 12,15M12,2C14.75,2 17.1,3 19.05,4.95C21,6.9 22,9.25 22,12V13.45C22,14.45 21.65,15.3 21,16C20.3,16.67 19.5,17 18.5,17C17.3,17 16.31,16.5 15.56,15.5C14.56,16.5 13.38,17 12,17C10.63,17 9.45,16.5 8.46,15.54C7.5,14.55 7,13.38 7,12C7,10.63 7.5,9.45 8.46,8.46C9.45,7.5 10.63,7 12,7C13.38,7 14.55,7.5 15.54,8.46C16.5,9.45 17,10.63 17,12V13.45C17,13.86 17.16,14.22 17.46,14.53C17.76,14.84 18.11,15 18.5,15C18.92,15 19.27,14.84 19.57,14.53C19.87,14.22 20,13.86 20,13.45V12C20,9.81 19.23,7.93 17.65,6.35C16.07,4.77 14.19,4 12,4C9.81,4 7.93,4.77 6.35,6.35C4.77,7.93 4,9.81 4,12C4,14.19 4.77,16.07 6.35,17.65C7.93,19.23 9.81,20 12,20H17V22H12C9.25,22 6.9,21 4.95,19.05C3,17.1 2,14.75 2,12C2,9.25 3,6.9 4.95,4.95C6.9,3 9.25,2 12,2Z'],
                                                        'alt' => To::kebab($_['task'] === 'g' ? ($page->name ?? $language->fieldAltAuthor) : $language->fieldAltAuthor)
                                                    ]
                                                ],
                                                'stack' => 10
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
        ]
    ]
]);

if (isset($fields['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['files']['lot']['files']['lot'])) {
    foreach ($fields['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['files']['lot']['files']['lot'] as &$v) {
        if (strpos(',pass,token,', ',' . basename($v, '.data') . ',') !== false) {
            $v = ['hidden' => true]; // Hide `pass` and `token` data from file list
        }
    }
}

return $fields;