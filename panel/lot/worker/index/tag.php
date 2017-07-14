<?php

// Load the main task(s)…
require __DIR__ . DS . '..' . DS . 'worker' . DS . 'page.php';
// Do not allow user to create page child(s)…
if ($__f && $__action === 's') {
    Shield::abort(PANEL_404);
}

// Set or modify the default panel content(s)…
$__x = $__page[0] ? $__page[0]->state : 'data';
Config::set('panel.m.t.page.content', [
    'link' => null,
    'tags' => null,
    'x' => [
        'values' => [
            '*' . $__x => $__action === 's' ? null : $language->update,
            'page' => $language->create,
            'archive' => null
        ],
        'order' => ['*' . $__x, 'page', 'draft', 'trash']
    ]
]);

Config::set('panel.s', [
    1 => [
        'parent' => null,
        'current' => null,
        'kin' => count($__chops) > 1 ? [
            'stack' => 20
        ] : null,
        'setting' => null
    ],
    2 => [
        'id' => [
            'content' => __DIR__ . DS . '..' . DS . 'page' . DS . 'tag' . DS . '-id.php',
            'stack' => 20
        ],
        'child' => null
    ]
]);