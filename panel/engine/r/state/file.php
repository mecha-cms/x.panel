<?php

$path = $PANEL['file']['path'];
$type = $PANEL['file']['type'];
$name = basename($path);
$folder = strtr(dirname($path), [
    LOT . DS => "",
    DS => '/'
]);

$plain = $type === null || strpos($type, 'text/') === 0 || $type === 'application/javascript' || strpos($type, 'application/json') === 0;
$content = $path && $plain ? file_get_contents($path) : null;

// <https://www.w3.org/TR/html5/forms.html#the-placeholder-attribute>
// The `placeholder` attribute represents a short hint (a word or short phrase) intended
// to aid the user with data entry when the control has no value. A hint could be a sample
// value or a brief description of the expected format. The attribute, if specified, must
// have a value that contains no “LF” (U+000A) or “CR” (U+000D) character(s).
$placeholder = is_string($content) ? trim(explode("\n", n($content), 2)[0]) : "";
$placeholder = $placeholder !== "" ? $placeholder : null;

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
                                                        'value' => $PANEL['token'],
                                                    ],
                                                    'view' => [
                                                        'type' => 'Hidden',
                                                        'value' => $_GET['view'] ?? null
                                                    ],
                                                    'file[content]' => [
                                                        'title' => $language->content,
                                                        'hidden' => !$plain,
                                                        'type' => 'Source',
                                                        'placeholder' => $placeholder ?? $language->fieldDescriptionContent,
                                                        'value' => $content,
                                                        'width' => true,
                                                        'height' => true,
                                                        'stack' => 10
                                                    ],
                                                    'file[name]' => [
                                                        'title' => $language->name,
                                                        'type' => 'Text',
                                                        'placeholder' => $name ?? $language->fieldDescriptionName,
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
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => [
                                                    'file[folder]' => [
                                                        'title' => $language->folder,
                                                        'type' => 'Text',
                                                        'placeholder' => $folder ?? $language->fieldDescriptionFolder,
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
                                                    0 => [
                                                        'type' => 'Submit',
                                                        'title' => $language->doSave
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