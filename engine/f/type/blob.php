<?php namespace x\panel\type\blob;

function x(array $_ = []) {
    $content  = '<p>' . \i('Make sure that the extension package you want to upload is structured like this:') . '</p>';
    $content .= '<pre><code class="txt">' . \i('extension') . '.zip
├── about.page
├── index.php
└── …</code></pre>';
    $description = ['It is not possible to upload the package due to the missing %s extension.', 'PHP <a href="https://www.php.net/manual/en/class.ziparchive.php" rel="nofollow" target="_blank"><code>zip</code></a>'];
    $type = $_['type'] ?? 'blob/x';
    $zip = \extension_loaded('zip');
    return \x\panel\type\blob(\array_replace_recursive([
        'lot' => [
            'desk' => [
                'lot' => [
                    'form' => [
                        'lot' => [
                            1 => [
                                'lot' => [
                                    'tabs' => [
                                        'lot' => [
                                            'blob' => [
                                                'lot' => [
                                                    'fields' => [
                                                        'lot' => [
                                                            'blob' => [
                                                                // Disable file upload if it is not possible to extract package with the current environment
                                                                'active' => $zip,
                                                                'description' => $zip ? null : $description,
                                                                // Disable file upload multiple
                                                                'name' => 'blobs[0]',
                                                                'type' => 'blob'
                                                            ],
                                                            'description' => [
                                                                'lot' => [
                                                                    'content' => [
                                                                        'content' => $content,
                                                                        'stack' => 10,
                                                                        'type' => 'content'
                                                                    ]
                                                                ],
                                                                'stack' => 20,
                                                                'title' => "",
                                                                'type' => 'field'
                                                            ],
                                                            'options' => ['skip' => true]
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'values' => [
                            'type' => $type
                        ]
                    ]
                ]
            ]
        ],
        'type' => $type
    ], $_));
}

function y(array $_ = []) {
    $content  = '<p>' . \i('Make sure that the layout package you want to upload is structured like this:') . '</p>';
    $content .= '<pre><code class="txt">' . \i('layout') . '.zip
├── about.page
├── index.php
└── …</code></pre>';
    $type = $_['type'] ?? 'blob/y';
    return \x\panel\type\blob\x(\array_replace_recursive([
        'lot' => [
            'desk' => [
                'lot' => [
                    'form' => [
                        'lot' => [
                            1 => [
                                'lot' => [
                                    'tabs' => [
                                        'lot' => [
                                            'blob' => [
                                                'lot' => [
                                                    'fields' => [
                                                        'lot' => [
                                                            'description' => [
                                                                'lot' => [
                                                                    'content' => [
                                                                        'content' => $content
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
                        ],
                        'values' => [
                            'type' => $type
                        ]
                    ]
                ]
            ]
        ],
        'type' => $type
    ], $_));
}