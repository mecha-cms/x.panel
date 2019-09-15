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
                    's' => [
                        'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                        'title' => false,
                        'hidden' => $_['task'] === 's',
                        'description' => $language->doCreate . ' (' . $language->file . ')',
                        'url' => str_replace('::g::', '::s::', dirname($url->clean)) . $url->query . $url->hash,
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
                                        'title' => $language->file,
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
                                                        'name' => $editable ? 'file[content]' : null,
                                                        'title' => $language->{$editable ? 'content' : 'type'},
                                                        'type' => $editable ? 'Source' : 'Field',
                                                        'alt' => $language->fieldAltContent,
                                                        'value' => $content,
                                                        'width' => true,
                                                        'height' => true,
                                                        'content' => $editable ? null : '<output class="output"><code>' . $type . '</code></output>',
                                                        'stack' => 10
                                                    ],
                                                    'name' => [
                                                        'name' => 'file[name]',
                                                        'title' => $language->name,
                                                        'type' => 'Text',
                                                        'alt' => $_['task'] === 'g' ? ($name ?? $language->fieldAltName) : $language->fieldAltName,
                                                        'pattern' => '^([_.]?[a-z\\d]+([_.-][a-z\\d]+)*)?\\.(' . implode('|', array_keys(array_filter(File::$config['x']))) . ')$',
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
                                                        'name' => false,
                                                        'title' => $language->{$_['task'] === 'g' ? 'doUpdate' : 'doCreate'},
                                                        'stack' => 10
                                                    ],
                                                    'l' => [
                                                        'type' => 'Link',
                                                        'hidden' => $_['task'] === 's',
                                                        'title' => $language->doDelete,
                                                        'url' => str_replace('::g::', '::l::', $url->clean . $url->query('&', ['token' => $_['token']])),
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