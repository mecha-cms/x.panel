<?php

return (function() {
    extract($GLOBALS);
    $id = explode('/', $PANEL['path'], 3)[1];
    $folders = [];
    foreach (g(LOT) as $k => $v) {
        if ($v === 0) {
            $n = basename($k);
            if ($n === 'x') {
                continue;
            }
            $folders[$n] = [
                'current' => strpos($PANEL['path'] . '/', '/' . $n . '/') === 0,
                'icon' => ['M10,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V8C22,6.89 21.1,6 20,6H12L10,4Z'],
                'title' => $language->{$n},
                'url' => $url . $PANEL['//'] . '/::g::/' . $n . '/1'
            ];
        }
    }
    ksort($folders);
    $i = 0;
    foreach ($folders as &$v) {
        $v['stack'] = 10 + $i;
        $i += 10;
    }
    return _\lot\x\panel\lot(['lot' => array_replace_recursive([
        'bar' => [
            'type' => 'Bar',
            'lot' => [
                0 => [
                    'type' => 'List',
                    'lot' => [
                        0 => [
                            'icon' => 'M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z',
                            'caret' => false,
                            'title' => false,
                            'url' => $url,
                            'lot' => $folders,
                            'tags' => ['is:main'],
                            'stack' => 10
                        ],
                        1 => [
                            'type' => 'Form.Get',
                            'url' => $url->current,
                            'name' => 'search',
                            'lot' => [
                                'fields' => [
                                    'type' => 'Fields',
                                    'lot' => [
                                        'q' => [
                                            '2' => ['title' => $language->doSearch . ': ' . explode('/', $PANEL['path'], 2)[1]],
                                            'type' => 'Text',
                                            'title' => $language->doSearch,
                                            'placeholder' => $language->doSearch
                                        ]
                                    ]
                                ]
                            ],
                            'stack' => 20
                        ]
                    ],
                    'stack' => 10
                ],
                1 => [
                    'type' => 'List',
                    'lot' => [
                        'site' => [
                            'current' => false,
                            'title' => $language->site,
                            'link' => $url,
                            'lot' => [
                                'user' => [
                                    'icon' => 'M12,19.2C9.5,19.2 7.29,17.92 6,16C6.03,14 10,12.9 12,12.9C14,12.9 17.97,14 18,16C16.71,17.92 14.5,19.2 12,19.2M12,5A3,3 0 0,1 15,8A3,3 0 0,1 12,11A3,3 0 0,1 9,8A3,3 0 0,1 12,5M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z',
                                    'title' => $language->user,
                                    'url' => '',
                                    'lot' => [
                                        'g' => [
                                            'icon' => 'M21.7,13.35L20.7,14.35L18.65,12.3L19.65,11.3C19.86,11.09 20.21,11.09 20.42,11.3L21.7,12.58C21.91,12.79 21.91,13.14 21.7,13.35M12,18.94L18.06,12.88L20.11,14.93L14.06,21H12V18.94M12,14C7.58,14 4,15.79 4,18V20H10V18.11L14,14.11C13.34,14.03 12.67,14 12,14M12,4A4,4 0 0,0 8,8A4,4 0 0,0 12,12A4,4 0 0,0 16,8A4,4 0 0,0 12,4Z',
                                            'title' => $language->doEdit,
                                            'url' => $url . $PANEL['//'] . '/::g::/user/' . $user->name,
                                            'stack' => 10
                                        ],
                                        'exit' => [
                                            'icon' => 'M19,21V19H15V17H19V15L22,18L19,21M10,4A4,4 0 0,1 14,8A4,4 0 0,1 10,12A4,4 0 0,1 6,8A4,4 0 0,1 10,4M10,14C11.15,14 12.25,14.12 13.24,14.34C12.46,15.35 12,16.62 12,18C12,18.7 12.12,19.37 12.34,20H2V18C2,15.79 5.58,14 10,14Z',
                                            'title' => $language->doExit,
                                            'url' => '/',
                                            'stack' => 20
                                        ]
                                    ],
                                    'stack' => 10
                                ],
                                'view' => [
                                    'current' => false,
                                    'icon' => 'M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z',
                                    'title' => $language->doView,
                                    'link' => $url,
                                    'stack' => 20
                                ]
                            ],
                            'stack' => 10
                        ]
                    ],
                    'stack' => 20
                ],
                2 => [
                    'type' => 'List',
                    'lot' => [],
                    'stack' => 30
                ]
            ],
            'stack' => 10
        ],
        'desk' => [
            'type' => 'Desk',
            'lot' => [
                'form' => [
                    'type' => 'Form.Post',
                    'url' => $url->current,
                    'name' => 'edit',
                    'lot' => [
                        0 => [
                            'type' => 'Section',
                            'lot' => [],
                            'stack' => 10
                        ],
                        1 => [
                            'type' => 'Section',
                            'lot' => [
                                'tabs' => [
                                    'type' => 'Tabs',
                                    'name' => 0,
                                    'lot' => []
                                ]
                            ],
                            'stack' => 20
                        ],
                        2 => [
                            'type' => 'Section',
                            'lot' => [],
                            'stack' => 30
                        ]
                    ],
                    'stack' => 10
                ]
            ],
            'stack' => 20
        ]
    ], (array) ($PANEL['lot'] ?? []))], 0);
})();