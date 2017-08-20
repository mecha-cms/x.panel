<?php

$__s = $__page[0]->css;

return [
    '+[css]' => [
        'key' => 'css',
        'title' => 'CSS',
        'type' => 'editor',
        'value' => $__s,
        'placeholder' => null,
        'union' => ['div'],
        'height' => true,
        'attributes' => [
            'data' => [
                'type' => stripos($__s, '</style>') === false && stripos($__s, '<link ') === false ? 'CSS' : 'HTML'
            ]
        ],
        'expand' => true,
        'stack' => 10
    ]
];