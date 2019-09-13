<?php

$f = $_['f'];
$type = $f ? mime_content_type($f) : null;
$t = $type === null || $type === 'inode/x-empty' || strpos($type, 'text/') === 0 || $type === 'application/javascript' || strpos($type, 'application/json') === 0;
$name = $_['task'] === 'g' ? basename($f) : "";
$folder = $_['task'] === 'g' ? strtr(dirname($f), [
    LOT . DS . $_['chop'][0] => "",
    DS => '/'
]) : "";

$content = $_['task'] === 'g' && $f && $t ? file_get_contents($f) : "";

// <https://www.w3.org/TR/html5/forms.html#the-placeholder-attribute>
// The `placeholder` attribute represents a short hint (a word or short phrase) intended
// to aid the user with data entry when the control has no value. A hint could be a sample
// value or a brief description of the expected format. The attribute, if specified, must
// have a value that contains no “LF” (U+000A) or “CR” (U+000D) character(s).
$placeholder = is_string($content) ? trim(explode("\n", n($content), 2)[0]) : "";

if ("" === $name) $name = null;
if ("" === $folder) $folder = null;
if ("" === $content) $content = null;
if ("" === $placeholder) $placeholder = null;

return [
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
                                                    'view' => [
                                                        'type' => 'Hidden',
                                                        'value' => $_GET['view'] ?? $_['view']
                                                    ],
                                                    'content' => [
                                                        'name' => 'file[content]',
                                                        'title' => $language->content,
                                                        'hidden' => $_['task'] === 'g' && !$t,
                                                        'type' => 'Source',
                                                        'placeholder' => $_['task'] === 'g' ? ($placeholder ?? $language->fieldDescriptionContent) : $language->fieldDescriptionContent,
                                                        'value' => $content,
                                                        'width' => true,
                                                        'height' => true,
                                                        'stack' => 10
                                                    ],
                                                    'name' => [
                                                        'name' => 'file[name]',
                                                        'title' => $language->name,
                                                        'type' => 'Text',
                                                        'placeholder' => $_['task'] === 'g' ? ($name ?? $language->fieldDescriptionName) : $language->fieldDescriptionName,
                                                        'value' => $name,
                                                        'width' => true,
                                                        'stack' => 20
                                                    ]
                                                ],
                                                'stack' => 10
                                            ]
                                        ],
                                        'stack' => 10
                                    ],
                                    'folder' => [
                                        'title' => $language->folder,
                                        'hidden' => $_['task'] === 's',
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => [
                                                    'folder' => [
                                                        'name' => 'file[folder]',
                                                        'title' => $language->folder,
                                                        'type' => 'Text',
                                                        'placeholder' => $_['task'] === 'g' ? ($folder ?? $language->fieldDescriptionDirectory) : $language->fieldDescriptionDirectory,
                                                        'value' => $folder,
                                                        'width' => true,
                                                        'stack' => 10
                                                    ]
                                                ],
                                                'stack' => 10
                                            ]
                                        ],
                                        'stack' => 20
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