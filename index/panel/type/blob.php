<?php

if (!$folder->exist && 'set' === $_['task']) {
    $_['alert']['error'][] = ['Path %s is not a %s.', ['<code>' . x\panel\from\path($folder->path ?? $file->path ?? P) . '</code>', 'folder']];
    $_['kick'] = [
        'part' => 1,
        'path' => dirname($_['path']),
        'query' => x\panel\_query_set(),
        'task' => 'get'
    ];
    return $_;
}

// <https://www.php.net/manual/en/function.ini-get.php>
$bytes = static function (string $v) {
    $i = intval($v = trim($v));
    switch (strtolower(substr($v, -1))) {
        case 'g':
            $i *= 1024;
        case 'm':
            $i *= 1024;
        case 'k':
            $i *= 1024;
    }
    return $i;
};

if (is_string($upload_max_size = ini_get('upload_max_filesize'))) {
    $upload_max_size = $bytes($upload_max_size);
}

// Compare with value from `.\lot\x\panel\state\file\size.php` and prefers the smaller one!
$upload_max_size = min($upload_max_size, $state->x->panel->guard->file->size[1]);

return x\panel\type\blob(array_replace_recursive($_, [
    'lot' => [
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
                                        'blob' => [
                                            // `tab`
                                            'lot' => [
                                                'fields' => [
                                                    // `fields`
                                                    'lot' => [
                                                        'blob' => [
                                                            'description' => ['Maximum file size allowed to upload is %s.', size((float) $upload_max_size)],
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        2 => [
                            // `section`
                            'lot' => [
                                'fields' => [
                                    // `fields`
                                    'lot' => [
                                        0 => [
                                            // `field`
                                            'lot' => [
                                                'tasks' => [
                                                    // `tasks/button`
                                                    'lot' => [
                                                        'set' => [
                                                            'description' => $folder->exist && 'set' === $_['task'] ? ['Upload to %s', x\panel\from\path($folder->path)] : null,
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
        ]
    ]
]));