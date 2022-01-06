<?php

$file = $_['file'];

$type = $file ? mime_content_type($file) : null;
$editable = 'set' === $_['task'];
$name = 'get' === $_['task'] ? basename($file) : "";

if (0 === strpos($type, 'text/') || 'inode/x-empty' === $type || 'image/svg+xml' === $type) {
    $editable = true;
}

if (0 === strpos($type, 'application/')) {
    $editable = false !== strpos(',javascript,json,ld+json,php,x-httpd-php,x-httpd-php-source,x-php,xhtml+xml,xml,', ',' . substr($type, 12) . ',');
}

// <https://stackoverflow.com/a/60861168>
// -2: Unreadable
// -1: Missing
// +0: Empty
// +1: Printable
// +2: ASCII
// +3: Binary
$check_mode = static function($path, $printable = false, $max = 256) {
    $max = floor($max);
    if (is_file($path)) {
        if (is_readable($path)) {
            $size = filesize($path);
            if (0 === $size) {
                return 0; // Empty
            }
            if ($max > $size) {
                $max = $size;
            }
            $chunk = ceil($size / $max);
            $h = fopen($path, 'rb');
            for ($i = 0; $i < $chunk; ++$i) {
                $buffer = fread($h, $max);
                if (preg_match('/[\x80-\xFF]/', $buffer)) {
                    fclose($h);
                    return 3; // Binary
                }
                if ($printable) {
                    $printable = ctype_print($buffer);
                }
            }
            fclose($h);
            return $printable ? 1 : 2; // Printable or ASCII
        }
        return -2; // Unreadable
    }
    return -1; // Missing
};

if (!$editable) {
    $test = $check_mode($file);
    $editable = 0 === $test || 1 === $test || 2 === $test;
}

$content = 'get' === $_['task'] && $file && $editable ? file_get_contents($file) : "";

if ("" === $content) $content = null;
if ("" === $name) $name = null;

$trash = !empty($state->x->panel->guard->trash) ? date('Y-m-d-H-i-s') : false;

$bar = [
    // `bar`
    'lot' => [
        // `links`
        0 => [
            'lot' => [
                'folder' => ['skip' => true],
                'link' => [
                    'skip' => false,
                    'url' => x\panel\to\link([
                        'part' => 1,
                        'path' => 'get' === $_['task'] ? dirname($_['path']) : $_['path'],
                        'query' => [
                            'tab' => false,
                            'type' => false
                        ],
                        'task' => 'get'
                    ])
                ],
                'set' => [
                    'description' => ['New %s', 'File'],
                    'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                    'skip' => 'set' === $_['task'],
                    'stack' => 10.5,
                    'title' => false,
                    'url' => x\panel\to\link([
                        'part' => 0,
                        'path' => 'get' === $_['task'] ? dirname($_['path']) : $_['path'],
                        'query' => [
                            'tab' => false,
                            'type' => 'file'
                        ],
                        'task' => 'set'
                    ])
                ]
            ]
        ]
    ]
];

$desk = [
    // `desk`
    'lot' => [
        'form' => [
            // `form/post`
            'data' => [
                'token' => $_['token'],
                'trash' => !empty($state->x->panel->guard->trash) ? date('Y-m-d-H-i-s') : null,
                'type' => $_['type']
            ],
            'lot' => [
                1 => [
                    // `section`
                    'lot' => [
                        'tabs' => [
                            // `tabs`
                            'lot' => [
                                'file' => [
                                    'lot' => [
                                        'fields' => [
                                            'lot' => [
                                                'content' => [
                                                    'height' => true,
                                                    'name' => 'file[content]',
                                                    'skip' => !$editable,
                                                    'stack' => 10,
                                                    'type' => 'source',
                                                    'value' => $content,
                                                    'width' => true
                                                ],
                                                'name' => [
                                                    'focus' => true,
                                                    'name' => 'file[name]',
                                                    'stack' => 20,
                                                    'type' => 'name',
                                                    'value' => $name,
                                                    'width' => true
                                                ]
                                            ],
                                            'stack' => 10,
                                            'type' => 'fields'
                                        ]
                                    ],
                                    'stack' => 10
                                ]
                            ]
                        ]
                    ]
                ],
                2 => [
                    // `section`
                    'lot' => [
                        'fields' => [
                            'lot' => [
                                0 => [
                                    'title' => "",
                                    'type' => 'field',
                                    'lot' => [
                                        'tasks' => [
                                            'lot' => [
                                                'set' => [
                                                    'description' => ['Save to %s', x\panel\from\path($file)],
                                                    'name' => 'task',
                                                    'stack' => 10,
                                                    'title' => 'get' === $_['task'] ? 'Update' : 'Create',
                                                    'type' => 'submit',
                                                    'value' => $_['task']
                                                ],
                                                'let' => [
                                                    'name' => 'task',
                                                    'skip' => 'set' === $_['task'],
                                                    'stack' => 20,
                                                    'title' => 'Delete',
                                                    'value' => 'let'
                                                ]
                                            ],
                                            'type' => 'tasks/button'
                                        ]
                                    ]
                                ]
                            ],
                            'stack' => 10,
                            'type' => 'fields'
                        ]
                    ]
                ]
            ]
        ]
    ]
];

return ($_ = array_replace_recursive($_, [
    'lot' => [
        'bar' => $bar,
        'desk' => $desk
    ]
]));