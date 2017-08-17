<?php

// Do not allow user to create page child(s)…
if ($__command === 's' && count($__chops) > 1) {
    Shield::abort(404);
}

// Preparation(s)…
if (count($__chops) === 1) {
    if ($__command === 'g' && $__is_get) {
        Request::delete('post');
        Guardian::kick($__state->path . '/::g::/' . $__chops[0] . '/' . $config->language);
    } else if ($__is_get) {
        $__chops[1] = $config->language;
        $__path .= '/' . $config->language;
        Lot::set([
            '__chops' => $__chops,
            '__path' => $__path
        ]);
    }
}

// ...
if ($__is_post) {
    // Disable the custom field(s) in any way!
    Hook::set('on.' . $__chops[0] . '.set', function($__f) {
        File::open(Path::F($__f))->delete();
    }, 0);
    // Set language from this form!
    if (Request::post('x') === 'archive') {
        $__config = State::config();
        $__config['language'] = $__chops[1];
        File::export($__config)->saveTo(STATE . DS . 'config.php', 0600);
        Request::set('post', 'x', 'page');
    }
    // Update event here…
}

// Do not allow user to delete the `en-us` language
if ($__command === 'r' && $__chops[1] === 'en-us') {
    Shield::abort(409);
}

// Load the page…
$__f = LOT . DS . $__chops[0] . DS . (isset($__chops[1]) ? $__chops[1] : 'en-us') . '.page';
Lot::set('__page', $__page = [
    new Page($__f, [], '__' . $__chops[0]),
    new Page($__f, [], $__chops[0])
]);

// Set or modify the default panel content(s)…
Config::set([
    'is' => 'page',
    'panel' => [
        'view' => 'page',
        'layout' => 2,
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
                            'value' => $__page[0]->content,
                            'attributes' => [
                                'data' => [
                                    'type' => 'YAML'
                                ]
                            ],
                            'stack' => 20
                        ],
                        'description' => [
                            'placeholder' => $language->f_description($language->{$__chops[0]}),
                            'stack' => 30
                        ],
                        'version' => [
                            'value' => $__command === 's' ? null : $__page[0]->version,
                            'placeholder' => $__page[1]->version,
                            'is' => [
                                'hidden' => false
                            ],
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
                        'type' => [
                            'type' => 'hidden',
                            'value' => 'YAML'
                        ],
                        'x' => [
                            'values' => [
                                '*' . ($__page[0]->state ?: "") => false,
                                'page' => $language->{$__command === 's' ? 'create' : 'update'},
                                'archive' => $__command !== 's' && $config->language !== $__page[0]->slug ? $language->attach : false,
                                'draft' => false
                            ],
                            'order' => ['page', 'archive', 'trash'],
                            'stack' => 0
                        ],
                        '+[time]' => false,
                        'link' => false,
                        'tags' => false
                    ],
                    'stack' => 10
                ]
            ]
        ],
        's' => [
            1 => [
                'kin' => [
                    'title' => $language->{$__chops[0] . 's'},
                    'list' => $__kins,
                    'a' => [
                        'set' => ["", $__state->path . '/::s::/' . (Path::D($__path) ?: $__path), false, ['title' => $language->add]]
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