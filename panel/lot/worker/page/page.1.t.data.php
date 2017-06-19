<?php

$__content = $__data[0]->content;

return [
    'content' => [
        'type' => 'editor',
        'value' => is_array($__content) ? To::json($__content) : $__content,
        'placeholder' => $language->f_content,
        'union' => ['div'],
        'is' => [
            'expand' => true
        ],
        'expand' => true,
        'stack' => 10
    ],
    'key' => [
        'type' => 'text',
        'value' => $__data[0]->key,
        'pattern' => '^[a-z\\d]+(?:_[a-z\\d]+)*$',
        'is' => [
            '*' => true
        ],
        'stack' => 10.1
    ]
];