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
                                                'icon' => 'M5,13H19V11H5M3,17H17V15H3M7,7V9H21V7',
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
                                                'icon' => 'M5,13H19V11H5M3,17H17V15H3M7,7V9H21V7',
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