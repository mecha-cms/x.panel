<?php

if (!$file->exist && 'get' === $_['task']) {
    $_['alert']['error'][] = ['Path %s is not a %s.', ['<code>' . x\panel\from\path($file->path ?? $folder->path ?? P) . '</code>', 'file']];
    $_['kick'] = [
        'part' => 1,
        'path' => dirname($_['path']),
        'query' => x\panel\_query_set(),
        'task' => 'get'
    ];
    return $_;
}

if ($file->exist && 'set' === $_['task']) {
    $_['kick'] = ['task' => 'get'];
    return $_;
}

$editable = 'set' === $_['task'];
$type = $file->type ?? "";

if (0 === strpos($type, 'text/') || 'inode/x-empty' === $type || 'image/svg+xml' === $type) {
    $editable = true;
}

if (0 === strpos($type, 'application/')) {
    $editable = false !== strpos(',javascript,json,ld+json,php,x-httpd-php,x-httpd-php-source,x-javascript,x-php,xhtml+xml,xml,', ',' . substr($type, 12) . ',');
}

// <https://stackoverflow.com/a/60861168>
// -2: Unreadable
// -1: Missing
// +0: Empty
// +1: Printable
// +2: ASCII
// +3: Binary
$check_mode = static function ($path, $printable = false, $max = 256) {
    $max = floor($max);
    if (!is_file($path)) {
        return -1; // Missing
    }
    if (!is_readable($path)) {
        return -2; // Unreadable
    }
    $size = filesize($path);
    if (0 === $size) {
        return 0; // Empty
    }
    if ($max > $size) {
        $max = $size;
    }
    $chunk = (int) ceil($size / $max);
    $h = fopen($path, 'rb');
    for ($i = 0; $i < $chunk; ++$i) {
        $buffer = fread($h, $max);
        if (preg_match('/[\x80-\xFF]/', $buffer)) {
            fclose($h);
            return 3; // Binary
        }
        if ($printable && function_exists('ctype_print')) {
            $printable = ctype_print($buffer);
        }
    }
    fclose($h);
    return $printable ? 1 : 2; // Printable or ASCII
};

if (!$editable) {
    $test = $check_mode($file ?? P);
    $editable = 0 === $test || 1 === $test || 2 === $test;
}

$content = 'get' === $_['task'] && $editable ? ($file->content ?? "") : "";
$name = 'get' === $_['task'] ? ($file->name(true) ?? "") : "";
$trash = !empty($state->x->panel->trash) ? date('Y-m-d-H-i-s') : null;

if ("" === $content) $content = null;
if ("" === $name) $name = null;

return x\panel\type\file(array_replace_recursive($_, [
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
                                        'file' => [
                                            // `tab`
                                            'lot' => [
                                                'fields' => [
                                                    'lot' => [
                                                        'content' => [
                                                            'skip' => !$editable,
                                                            'value' => $content
                                                        ],
                                                        'name' => ['value' => $name]
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
                                                            'description' => $folder->exist && 'set' === $_['task'] ? ['Save to %s', x\panel\from\path($folder->path)] : null
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
                    'values' => ['trash' => $trash]
                ]
            ]
        ]
    ]
]));