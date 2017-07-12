<?php

// Load the main task(s)…
require __DIR__ . DS . '..' . DS . 'worker' . DS . 'page.php';
// Do not allow user to create page child(s)…
if ($__f && $__action === 's') {
    Guardian::kick(str_replace('::s::', '::g::', $url->current));
}

// Set or modify the default panel content(s)…
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
            'content' => __DIR__ . DS . '..' . DS . 'page' . DS . '-id.php',
            'stack' => 20
        ],
        'child' => null
    ]
]);