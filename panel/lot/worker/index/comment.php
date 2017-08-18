<?php

// Preparation(s)…
if (!Get::kin('_' . $__chops[0] . 's')) {
    Get::plug('_' . $__chops[0] . 's', function($__folder) {
        $__output = [];
        foreach (File::explore([$__folder, 'draft,page,archive'], true) as $__k => $__v) {
            $__output[basename($__k)] = $__k;
        }
        krsort($__output);
        return !empty($__output) ? array_values($__output) : false;
    });
}
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

// Set custom panel view
Config::set('panel.view', 'page');

// Set or modify the default panel content(s)…
Config::set('panel', [
    'm' => [
        't' => [
            'page' => [
                'title' => $language->comment
            ]
        ]
    ],
    'f' => [
        'page' => [
            'author' => [
                'is' => [
                    'hidden' => false
                ],
                'expand' => true,
                'stack' => 10
            ],
            '+[time]' => false,
            'description' => false,
            'link' => false,
            '*slug' => false,
            'tags' => false,
            'title' => false
        ]
    ],
    's' => [
        1 => [
            'author' => false,
            'current' => false,
            'parent' => false,
            'setting' => false
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