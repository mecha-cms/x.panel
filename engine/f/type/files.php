<?php namespace x\panel\type\files;

function cache(array $_ = []) {
    $token = $_['token'] ?? null;
    $type = $_['type'] ?? 'files/cache';
    return \x\panel\type\files(\array_replace_recursive([
        'lot' => [
            'desk' => [
                // `desk`
                'lot' => [
                    'form' => [
                        // `form/post`
                        'lot' => [
                            0 => [
                                // `section`
                                'lot' => [
                                    'tasks' => [
                                        // `tasks/button`
                                        'lot' => [
                                            'blob' => ['skip' => true],
                                            'file' => ['skip' => true],
                                            'folder' => ['skip' => true],
                                            'let' => [
                                                'icon' => 'M12,18A6,6 0 0,1 6,12C6,11 6.25,10.03 6.7,9.2L5.24,7.74C4.46,8.97 4,10.43 4,12A8,8 0 0,0 12,20V23L16,19L12,15M12,4V1L8,5L12,9V6A6,6 0 0,1 18,12C18,13 17.75,13.97 17.3,14.8L18.76,16.26C19.54,15.03 20,13.57 20,12A8,8 0 0,0 12,4Z',
                                                'skip' => false,
                                                'stack' => 10,
                                                'title' => 'Flush',
                                                'type' => 'link',
                                                'url' => [
                                                    'query' => \x\panel\_query_set(['token' => $token]),
                                                    'task' => 'fire/flush'
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
        ],
        'type' => $type
    ], $_));
}

function trash(array $_ = []) {
    $token = $_['token'] ?? null;
    $type = $_['type'] ?? 'files/trash';
    return \x\panel\type\files(\array_replace_recursive([
        'lot' => [
            'desk' => [
                // `desk`
                'lot' => [
                    'form' => [
                        // `form/post`
                        'lot' => [
                            0 => [
                                // `section`
                                'lot' => [
                                    'tasks' => [
                                        // `tasks/button`
                                        'lot' => [
                                            'blob' => ['skip' => true],
                                            'file' => ['skip' => true],
                                            'folder' => ['skip' => true],
                                            'let' => [
                                                'icon' => 'M15,16H19V18H15V16M15,8H22V10H15V8M15,12H21V14H15V12M11,10V18H5V10H11M13,8H3V18A2,2 0 0,0 5,20H11A2,2 0 0,0 13,18V8M14,5H11L10,4H6L5,5H2V7H14V5Z',
                                                'skip' => false,
                                                'stack' => 10,
                                                'title' => 'Clear',
                                                'type' => 'link',
                                                'url' => [
                                                    'query' => \x\panel\_query_set(['token' => $token]),
                                                    'task' => 'fire/flush'
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
        ],
        'type' => $type
    ], $_));
}

function x(array $_ = []) {
    $path = $_['path'] ?? null;
    $type = $_['type'] ?? 'files/x';
    return \x\panel\type\files(\array_replace_recursive([
        'lot' => [
            'bar' => [
                // `bar`
                'lot' => [
                    0 => [
                        // `links`
                        'lot' => [
                            'folder' => ['skip' => true],
                            'link' => [
                                'icon' => 'M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z',
                                'skip' => false,
                                'url' => [
                                    'part' => 1,
                                    'path' => $path ? \dirname($path) : $path,
                                    'query' => \x\panel\_query_set(),
                                    'task' => 'get'
                                ]
                            ],
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
                                            'info' => [
                                                // `tab`
                                                'lot' => [
                                                    0 => [
                                                        // `content`
                                                        'content' => '<p role="status">' . \i('No %s.', ['content']) . '</p>',
                                                        'description' => \i('No %s.', ['description']),
                                                        'stack' => 10,
                                                        'title' => \i('No %s', ['Title']) . ' <sup>0.0.0</sup>',
                                                        'type' => 'content'
                                                    ]
                                                ],
                                                'stack' => 20
                                            ],
                                            'license' => [
                                                // `tab`
                                                'lot' => [
                                                    0 => [
                                                        // `content`
                                                        'content' => '<pre class="is:text"><code class="txt">' . \preg_replace('/&lt;(https?:\/\/\S+?)&gt;/', '&lt;<a href="$1" rel="nofollow" target="_blank">$1</a>&gt;', \htmlspecialchars(\file_get_contents(__DIR__ . \D . 'LICENSE.txt'))) . '</code></pre>',
                                                        'stack' => 10,
                                                        'type' => 'content'
                                                    ]
                                                ],
                                                'stack' => 30
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        'type' => $type
    ], $_));
}

function y(array $_ = []) {
    $type = $_['type'] ?? 'files/y';
    return \x\panel\type\files\x(\array_replace_recursive([
        'type' => $type
    ], $_));
}