<?php

return [
    'css' => [
        'title' => 'CSS',
        'type' => 'editor',
        'value' => $__page[0]->css,
        'placeholder' => null,
        'union' => ['div'],
        'is' => [
            'expand' => true
        ],
        'attributes' => [
            'data' => [
                'type' => 'CSS'
            ]
        ],
        'expand' => true,
        'stack' => 10
    ]
];