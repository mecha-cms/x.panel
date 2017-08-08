<?php

// Do not allow user to create page child(s)…
if ($__command === 's' && count($__chops) > 1) {
    Shield::abort(PANEL_404);
}

// Preparation(s)…
if ($__command !== 's' && count($__chops) === 1) {
    $__chops[1] = $config->language;
    $__path .= '/' . $__chops[1];
}

// Set or modify the default panel content(s)…
$__page[1] = new Page(LOT . DS . $__chops[0] . DS . 'en-us.page', [], $__chops[0]);
Config::set([
    'is' => 'page',
    'panel' => [
        'layout' => 2,
        'l' => 'page',
        'c:f' => true,
        'm' => [
            't' => [
                'page' => [
                    'title' => $language->editor,
                    'list' => [
                        'title' => [
                            'placeholder' => $__page[1]->title,
                            'stack' => 10
                        ],
                        'content' => [
                            'type' => 'editor',
                            'value' => $__page[0]->content ?: $__page[1]->content,
                            'attributes' => [
                                'data' => [
                                    'type' => 'YAML'
                                ]
                            ],
                            'stack' => 20
                        ],
                        'description' => [
                            'placeholder' => $__page[1]->description ?: $language->f_description($language->{$__chops[0]}),
                            'stack' => 30
                        ],
                        'version' => [
                            'type' => 'text',
                            'value' => $__page[0]->version,
                            'placeholder' => $__page[1]->version,
                            'stack' => 40
                        ],
                        '*slug' => [
                            'title' => $language->locale,
                            'placeholder' => $__page[1]->slug,
                            'is' => [
                                'block' => false
                            ],
                            'expand' => false,
                            'stack' => 50
                        ],
                        // the submit button(s)
                        'x' => [
                            'values' => [
                                '*' . $__page[0]->state => null,
                                'page' => $language->{$__command === 's' ? 'create' : 'update'},
                                'draft' => false,
                                'archive' => false
                            ],
                            'order' => ['page', 'trash'],
                            'stack' => 0
                        ],
                        '+[time]' => false,
                        'type' => false,
                        'link' => false,
                        'tags' => false
                    ],
                    'stack' => 10
                ],
                'file' => false,
                'folder' => false,
                'upload' => false
            ]
        ],
        's' => [
            1 => [
                'kin' => [
                    'title' => $language->{$__chops[0] . 's'},
                    'list' => $__kins,
                    'a' => [
                        'set' => ['&#x2795;', $__state->path . '/::s::/' . (Path::D($__path) ?: $__path), false, ['title' => $language->add]]
                    ],
                    'lot' => false,
                    'stack' => 10
                ],
                'author' => false,
                'current' => false,
                'nav' => false,
                'parent' => false,
                'search' => false,
                'setting' => false
            ]
        ],
        'x' => [
            's' => [
                'current' => true,
                'parent' => true
            ]
        ]
    ]
]);