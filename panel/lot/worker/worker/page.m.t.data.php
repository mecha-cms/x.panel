<?php

$__s = substr($__path, -2) === '/+';
$__values = [
    'data' => $language->{$__s ? 'save' : 'update'},
    'trash' => $__s ? false : $language->delete
];

$__key = $__data[0]->key;
$__k = Anemon::alter($__key, [
    'chunk' => 'number',
    'time' => 'date'
], 'editor');

return [
    'value' => [
        'type' => $__k,
        'value' => $__data[0]->value,
        'placeholder' => $language->{'f_' . ($__k === 'editor' ? 'content' : 'value')},
        'union' => ['div'],
        'attributes' => [
            'data' => [
                'type' => 'PHP'
            ]
        ],
        'is' => [
            'expand' => true
        ],
        'expand' => $__k === 'editor',
        'stack' => 10
    ],
    '*key' => [
        'type' => 'text',
        'value' => $__key,
        'pattern' => '^[a-z\\d]+(?:_[a-z\\d]+)*$',
        'stack' => 20
    ],
    'x' => [
        'type' => 'submit',
        'title' => $language->submit,
        'values' => $__values,
        'order' => ['data', 'trash'],
        'stack' => 0
    ]
];