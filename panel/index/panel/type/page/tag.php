<?php

if ('set' === $_['task']) {
    $id = 0;
    foreach (g($_['folder'], 'archive,page') as $k => $v) {
        $v = From::tag(pathinfo($k, PATHINFO_FILENAME)) ?? 0;
        if ($v > $id) {
            $id = $v;
        }
    }
    ++$id;
}

if ('POST' === $_SERVER['REQUEST_METHOD'] && ($_['file'] || $_['folder'])) {
    $current = $_POST['data']['id'] ?? $_POST['page']['id'] ?? 0;
    foreach (g('set' === $_['task'] ? $_['folder'] : dirname($_['file']), 'archive,page') as $k => $v) {
        $v = From::tag(pathinfo($k, PATHINFO_FILENAME)) ?? 0;
        if ($v === $current && $k !== $_['file']) {
            // Found duplicate tag ID!
            $_['alert']['error'][$k] = ['%s %s is in use.', ['ID', '<a href="' . x\panel\to\link([
                'part' => 0,
                'path' => 'tag/' . basename($k),
                'query' => [
                    'tab' => ['data'],
                    'type' => null
                ],
                'task' => 'get'
            ]) . '"><code>' . $v . '</code></a>']];
            break;
        }
    }
}

$_['lot'] = array_replace_recursive($_['lot'] ?? [], [
    'bar' => [
        // `bar`
        'lot' => [
            // `links`
            0 => [
                'lot' => [
                    'set' => [
                        'description' => ['New %s', 'Tag'],
                        'icon' => 'M21.41,11.58L12.41,2.58C12.04,2.21 11.53,2 11,2H4A2,2 0 0,0 2,4V11C2,11.53 2.21,12.04 2.59,12.41L3,12.81C3.9,12.27 4.94,12 6,12A6,6 0 0,1 12,18C12,19.06 11.72,20.09 11.18,21L11.58,21.4C11.95,21.78 12.47,22 13,22C13.53,22 14.04,21.79 14.41,21.41L21.41,14.41C21.79,14.04 22,13.53 22,13C22,12.47 21.79,11.96 21.41,11.58M5.5,7A1.5,1.5 0 0,1 4,5.5A1.5,1.5 0 0,1 5.5,4A1.5,1.5 0 0,1 7,5.5A1.5,1.5 0 0,1 5.5,7M10,19H7V22H5V19H2V17H5V14H7V17H10V19Z',
                        'url' => [
                            'part' => 0,
                            'path' => dirname($_['path']),
                            'query' => [
                                'query' => null,
                                'stack' => null,
                                'tab' => null,
                                'type' => 'page/tag'
                            ],
                            'task' => 'set'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'desk' => [
        // `desk`
        'lot' => [
            'form' => [
                // `form/post`
                'lot' => [
                    1 => [
                        // `section`
                        'lot' => [
                            'tabs' => [
                                // `tabs`
                                'lot' => [
                                    'page' => [
                                        'name' => 'tag',
                                        'lot' => [
                                            'fields' => [
                                                // `fields`
                                                'lot' => [
                                                    'content' => ['skip' => true]
                                                ]
                                            ]
                                        ],
                                        'stack' => 10
                                    ],
                                    'data' => [
                                        'lot' => [
                                            'fields' => [
                                                'lot' => [
                                                    'id' => [
                                                        'fix' => true,
                                                        'name' => 'data[id]',
                                                        'stack' => 10,
                                                        'title' => 'ID',
                                                        'type' => 'number',
                                                        'value' => 'set' === $_['task'] ? $id : $page->id
                                                    ],
                                                    'link' => ['skip' => true]
                                                ]
                                            ]
                                        ]
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

return $_;