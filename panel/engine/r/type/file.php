<?php

if (is_dir($f = $_['f']) && 'g' === $_['task']) {
    $_['alert']['error'][] = ['Path %s is not a %s.', ['<code>' . x\panel\from\path($f) . '</code>', 'file']];
    $_['kick'] = $_['/'] . '/::g::/' . $_['path'] . $url->query('&', [
        'type' => false
    ]) . $url->hash;
    return $_;
}

$type = $f && is_file($f) ? mime_content_type($f) : null;
$name = 'g' === $_['task'] ? basename($f) : "";

$editable = 's' === $_['task'];

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
    $test = $check_mode($f);
    $editable = 0 === $test || 1 === $test || 2 === $test;
}

$content = 'g' === $_['task'] && $f && $editable ? file_get_contents($f) : "";

if ("" === $name) $name = null;
if ("" === $content) $content = null;

$trash = $_['trash'] ? date('Y-m-d-H-i-s') : false;

$bar = [
    // type: bar
    'lot' => [
        // type: links
        0 => [
            'lot' => [
                'folder' => ['skip' => true],
                'link' => [
                    'url' => $_['/'] . '/::g::/' . ('g' === $_['task'] ? dirname($_['path']) : $_['path']) . '/1' . $url->query('&', [
                        'tab' => false,
                        'type' => false
                    ]) . $url->hash,
                    'skip' => false
                ],
                's' => [
                    'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                    'title' => false,
                    'description' => ['New %s', 'File'],
                    'url' => strtr(dirname($url->clean), ['::g::' => '::s::']) . $url->query('&', [
                        'tab' => false,
                        'type' => 'file'
                    ]) . $url->hash,
                    'skip' => 's' === $_['task'],
                    'stack' => 10.5
                ]
            ]
        ]
    ]
];

$desk = [
    // type: desk
    'lot' => [
        'form' => [
            // type: form/post
            'data' => [
                'token' => $_['token'],
                'type' => $_['type']
            ],
            'lot' => [
                1 => [
                    // type: section
                    'lot' => [
                        'tabs' => [
                            // type: tabs
                            'gap' => false,
                            'lot' => [
                                'file' => [
                                    'lot' => [
                                        'fields' => [
                                            'type' => 'fields',
                                            'lot' => [
                                                'content' => [
                                                    'type' => 'source',
                                                    'name' => 'file[content]',
                                                    'value' => $content,
                                                    'width' => true,
                                                    'height' => true,
                                                    'skip' => !$editable,
                                                    'stack' => 10
                                                ],
                                                'name' => [
                                                    'type' => 'name',
                                                    'focus' => true,
                                                    'name' => 'file[name]',
                                                    'value' => $name,
                                                    'width' => true,
                                                    'stack' => 20
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
                ],
                2 => [
                    // type: section
                    'lot' => [
                        'fields' => [
                            'type' => 'fields',
                            'lot' => [
                                0 => [
                                    'title' => "",
                                    'type' => 'field',
                                    'lot' => [
                                        'tasks' => [
                                            'type' => 'tasks/button',
                                            'lot' => [
                                                's' => [
                                                    'title' => 'g' === $_['task'] ? 'Update' : 'Create',
                                                    'description' => ['Save to %s', x\panel\from\path($_['f'])],
                                                    'type' => 'submit',
                                                    'name' => false,
                                                    'stack' => 10
                                                ],
                                                'l' => [
                                                    'title' => 'Delete',
                                                    'type' => 'link',
                                                    'url' => strtr($url->clean . $url->query('&', [
                                                        'token' => $_['token'],
                                                        'trash' => $trash,
                                                        'type' => 'file'
                                                    ]), ['::g::' => '::l::']),
                                                    'skip' => 's' === $_['task'],
                                                    'stack' => 20
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
];

return ($_ = array_replace_recursive($_, [
    'lot' => [
        'bar' => $bar,
        'desk' => $desk
    ]
]));