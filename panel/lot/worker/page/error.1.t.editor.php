<?php

return [
    'content' => [
        'type' => 'editor',
        'value' => $__page[0]->content,
        'placeholder' => null,
        'union' => ['div'],
        'is' => [
            'expand' => true
        ],
        'expand' => true,
        'stack' => 10
    ],
    // the submit button
    'x' => [
        'type' => 'submit',
        'title' => $language->submit,
        'text' => $language->delete,
        'value' => 'trash',
        'stack' => 0
    ]
];