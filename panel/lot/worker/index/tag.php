<?php

// Load the main task(s)…
require __DIR__ . DS . '..' . DS . 'worker' . DS . 'page.php';

// Do not allow user to create page child(s)…
if ($__f && $__action === 's') {
    Shield::abort(PANEL_404);
}

// Set or modify the default panel content(s)…
$__x = $__page[0] ? $__page[0]->state : 'page';
Config::set('panel.m.t.page.content', [
    'link' => null,
    'tags' => null,
    'x' => [
        'values' => [
            '*' . $__x => $__action === 's' ? null : $language->update,
            'page' => $__x === 'page' ? null : $language->create,
            'draft' => $__x === 'draft' ? null : $language->save,
            'archive' => null
        ],
        'order' => ['*' . $__x, 'page', 'draft', 'trash']
    ]
]);

Config::set('panel.s', [
    1 => [
        'parent' => null,
        'current' => null,
        'kin' => [
            'stack' => 20
        ],
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