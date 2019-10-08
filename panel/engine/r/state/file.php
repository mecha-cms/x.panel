<?php

$f = $_['f'];
$type = $f && is_file($f) ? mime_content_type($f) : null;
$name = $_['task'] === 'g' ? basename($f) : "";

$editable = $_['task'] === 's';
if (strpos($type, 'text/') === 0 || $type === 'inode/x-empty' || $type === 'image/svg+xml') {
    $editable = true;
}
if (strpos($type, 'application/') === 0) {
    $editable = strpos(',javascript,json,ld+json,php,x-httpd-php,x-httpd-php-source,x-php,xhtml+xml,xml,', ',' . substr($type, 12) . ',') !== false;
}

$content = $_['task'] === 'g' && $f && $editable ? file_get_contents($f) : "";

if ("" === $name) $name = null;
if ("" === $content) $content = null;

return [
    'bar' => [
        // type: Bar
        'lot' => [
            // type: List
            0 => [
                'lot' => [
                    'folder' => [
                        'icon' => 'M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z',
                        'url' => $url . $_['/'] . '::g::' . ($_['task'] === 'g' ? dirname($_['path']) : $_['path']) . '/1' . $url->query('&', ['content' => false, 'tab' => false]) . $url->hash,
                        'lot' => false // Disable sub-menu(s)
                    ],
                    's' => [
                        'hidden' => $_['task'] === 's',
                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                        'title' => false,
                        'description' => $language->doCreate . ' (' . $language->file . ')',
                        'url' => str_replace('::g::', '::s::', dirname($url->clean)) . $url->query('&', ['content' => 'file', 'tab' => false]) . $url->hash,
                        'stack' => 10.5
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
                                    'file' => [
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
                                                        'value' => $_GET['content'] ?? 'file'
                                                    ],
                                                    'content' => [
                                                        'hidden' => !$editable,
                                                        'type' => 'Source',
                                                        'alt' => $language->fieldAltContent,
                                                        'name' => 'file[content]',
                                                        'value' => $content,
                                                        'width' => true,
                                                        'height' => true,
                                                        'stack' => 10
                                                    ],
                                                    'name' => [
                                                        'type' => 'Text',
                                                        'alt' => $_['task'] === 'g' ? ($name ?? $language->fieldAltName) : $language->fieldAltName,
                                                        'pattern' => "^([_.]?[a-z\\d]+([_.-][a-z\\d]+)*)?\\.(" . implode('|', array_keys(array_filter(File::$state['x']))) . ")$",
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
                        // type: Section
                        'lot' => [
                            'fields' => [
                                'type' => 'Fields',
                                'lot' => [
                                    0 => [
                                        'type' => 'Field',
                                        'title' => "",
                                        'lot' => [
                                            'tasks' => [
                                                'type' => 'Tasks.Button',
                                                'lot' => [
                                                    's' => [
                                                        'type' => 'Submit',
                                                        'title' => $language->{$_['task'] === 'g' ? 'doUpdate' : 'doCreate'},
                                                        'name' => false,
                                                        'stack' => 10
                                                    ],
                                                    'l' => [
                                                        'hidden' => $_['task'] === 's',
                                                        'type' => 'Link',
                                                        'title' => $language->doDelete,
                                                        'url' => str_replace('::g::', '::l::', $url->clean . $url->query('&', ['content' => 'file', 'token' => $_['token']])),
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
    ]
];