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

// Set custom file manager layout
Config::set([
    'is' => 'page',
    'panel' => [
        'layout' => 2,
        'l' => 'page',
        'c:f' => true
    ]
]);

// Set or modify the default panel content(s)…
Hook::set('shield.enter', function() {
    extract(Lot::get(null, []));
    $__page[1] = new Page(LOT . DS . $__chops[0] . DS . 'en-us.page', [], $__chops[0]);
    Config::set([
        'panel' => [
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
                                    'block' => null
                                ],
                                'expand' => null,
                                'stack' => 50
                            ],
                            // the submit button(s)
                            'x' => [
                                'values' => [
                                    '*' . $__page[0]->state => null,
                                    'page' => $language->{$__command === 's' ? 'create' : 'update'},
                                    'draft' => null,
                                    'archive' => null
                                ],
                                'order' => ['page', 'trash'],
                                'stack' => 0
                            ],
                            '+[time]' => null,
                            'type' => null,
                            'link' => null,
                            'tags' => null
                        ],
                        'stack' => 10
                    ],
                    'file' => null,
                    'folder' => null,
                    'upload' => null
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
                        'lot' => null,
                        'stack' => 10
                    ],
                    'author' => null,
                    'current' => null,
                    'nav' => null,
                    'parent' => null,
                    'search' => null,
                    'setting' => null
                ]
            ]
        ]
    ]);
}, 0);