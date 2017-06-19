<?php

return [
    'js' => [
        'title' => 'JavaScript',
        'type' => 'editor',
        'value' => $__page[0]->js,
        'placeholder' => null,
        'union' => ['div'],
        'is' => [
            'expand' => true
        ],
        'attributes' => [
            'data' => [
                'type' => 'JavaScript'
            ]
        ],
        'expand' => true,
        'stack' => 10
    ]
];