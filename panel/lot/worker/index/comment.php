<?php

// Do not allow user to create page child(s)…
// But allow user to create custom field(s)…
if ($__command === 's' && end($__chops) !== '+') {
    Shield::abort(404);
}

Hook::set('message.set.success', function($__s) use($language, $__command, $__chops, $__path) {
    $__x = Request::post('x');
    if ($__command === 'r' || !$__x || strpos('/' . $__path . '/', '/+/') !== false) {
        return $__s;
    }
    if ($__x !== 'page') {
        return $__s;
    }
    $__p = new Page(LOT . DS . $__path . DS . Request::post('slug') . '.' . $__x, [], $__chops[0]);
    return $__s . ' ' . HTML::a($language->view, $__p->url, true, ['classes' => ['right']]);
});

// Preparation(s)…
if (!Get::kin('_' . $__chops[0] . 's')) {
    Get::plug('_' . $__chops[0] . 's', function($__folder) use($__is_has_step) {
        $__output = [];
        foreach (File::explore([$__folder, 'draft,page,archive'], $__is_has_step) as $__k => $__v) {
            $__output[basename($__k)] = $__k;
        }
        krsort($__output);
        return !empty($__output) ? array_values($__output) : false;
    });
}
if ($__is_has_step) {
    Hook::set($__chops[0] . '.title', function($__title, $__lot) {
        if (!isset($__lot['path'])) {
            return $__title;
        }
        return Page::apart($__lot['path'], 'author', $__title);
    }, 0);
    Hook::set($__chops[0] . '.description', function($__content, $__lot) {
        if (!isset($__lot['path'])) {
            return $__content;
        }
        return Page::apart($__lot['path'], 'content', $__content);
    }, 0);
    Hook::set('panel.a.' . $__chops[0] . 's', function() {
        return [];
    }, 0);
}

// Set custom panel view
Config::set('panel.view', 'page');

// Set or modify the default panel content(s)…
if (!$__is_has_step) {
    Config::set('panel', [
        'm' => [
            't' => [
                'page' => [
                    'list' => [
                        'content' => [
                            'stack' => 10
                        ],
                        'type' => [
                            'stack' => 20
                        ],
                        'author' => [
                            'stack' => 30
                        ],
                        'email' => [
                            'stack' => 40
                        ],
                        'status' => [
                            'description' => false,
                            'values' => [
                                1 => $language->author,
                                2 => $language->guest,
                               -1 => $language->spam,
                                0 => false,
                                3 => false
                            ],
                            'stack' => 50
                        ],
                        'x' => [
                            'values' => [
                                'archive' => false
                            ]
                        ],
                        '+[time]' => false,
                        'description' => false,
                        'link' => false,
                        'slug' => false,
                        'tags' => false,
                        'title' => false
                    ]
                ]
            ]
        ],
        's' => [
            1 => [
                'author' => false,
                'current' => false,
                'parent' => false
            ],
            2 => [
                'child' => false
            ]
        ],
        'x' => [
            's' => [
                'child' => true,
                'current' => true,
                'parent' => true
            ]
        ]
    ]);
    Hook::set('shield.enter', function() {
        Config::reset('panel.o.page.toggle');
    }, 0);
}