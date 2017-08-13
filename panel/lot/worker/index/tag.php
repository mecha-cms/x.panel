<?php

// Do not allow user to create page child(s)…
if ($__command === 's' && count($__chops) > 1) {
    if (isset($__chops[2]) && $__chops[2] === '+') {
        // But allow user to create custom field(s)…
    } else {
        Shield::abort(404);
    }
}

// Set custom file manager layout
Config::set('panel.l', 'page');

// Set or modify the default panel content(s)…
$__x = $__page[0] ? $__page[0]->state : 'page';
Config::set('panel', [
    'f' => [
        'page' => [
            'x' => [
                'values' => [
                    'archive' => false
                ],
                'order' => ['*' . $__x, 'page', 'draft', 'trash'],
            ],
            '+[time]' => false,
            'link' => false,
            'tags' => false
        ]
    ],
    's' => [
        1 => [
            'kin' => [
                'stack' => 20
            ],
            'current' => false,
            'parent' => false,
            'setting' => false
        ],
        2 => [
            'id' => [
                'content' => __DIR__ . DS . '..' . DS . 'page' . DS . 'tag' . DS . '-id.php',
                'stack' => 20
            ],
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