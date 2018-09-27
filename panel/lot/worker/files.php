<?php

// `panel.desk`
// `panel.desk.header`
// `panel.desk.header.tools`
// `panel.desk.body`
// `panel.desk.body.files`
// `panel.desk.body.fields`
// `panel.desk.body.tabs`
// `panel.desk.body.tabs.fields`
// `panel.desk.footer`
// `panel.desk.footer.tools`
// `panel.desk.footer.pager`

// `panel.file.tools`

$path = $panel->id . '/' . $panel->path;

Config::set('panel.desk', [
    'header' => [
        'tool[]' => [
            'file' => [
                'icon' => [['M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z']],
                'path' => $path,
                'c' => 's',
                'stack' => 10,
                'query' => [
                    'q' => false,
                    'tab:' . $panel->id => 'file'
                ],
                'stack' => 10
            ],
            'folder' => [
                'icon' => [['M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z']],
                'path' => $path,
                'c' => 's',
                'query' => [
                    'q' => false,
                    'tab:' . $panel->id => 'folder'
                ],
                'stack' => 10.1
            ],
            '+' => [
                'title' => false,
                'icon' => [['M16,12A2,2 0 0,1 18,10A2,2 0 0,1 20,12A2,2 0 0,1 18,14A2,2 0 0,1 16,12M10,12A2,2 0 0,1 12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12M4,12A2,2 0 0,1 6,10A2,2 0 0,1 8,12A2,2 0 0,1 6,14A2,2 0 0,1 4,12Z']],
                'kind' => ['text'],
                'menu[]' => [
                    'foo' => [
                        'title' => 'Foo',
                        'stack' => 10
                    ],
                    'baz' => [
                        'title' => 'Bar',
                        'stack' => 10.1
                    ]
                ],
                'stack' => 10.2
            ]
        ]
    ],
    'body' => [
        'tab[]' => [
            $panel->id . 's' => [
                'title' => $language->files,
                'file[]' => true,
                'stack' => 10
            ]
        ]
    ],
    'footer' => [
        'pager' => true
    ]
]);