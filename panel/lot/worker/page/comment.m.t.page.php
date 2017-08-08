<?php

$__x = $__page[0]->state;
$__statuss = (array) $language->o_user;
$__types = a(Config::get('panel.o.page.type', []));

asort($__types);

return [
    'content' => [
        'type' => 'editor',
        'value' => $__page[0]->content,
        'placeholder' => $language->f_content,
        'union' => ['div'],
        'is' => [
            'expand' => true
        ],
        'attributes' => [
            'data' => [
                'type' => $__page[0]->type
            ]
        ],
        'expand' => true,
        'stack' => 10
    ],
    'type' => [
        'type' => 'select',
        'value' => $__page[0]->type,
        'values' => $__types ?: ['HTML' => 'HTML'],
        'stack' => 20
    ],
    'author' => [
        'type' => 'text',
        'value' => $__page[0]->author,
        'is' => [
            'block' => true
        ],
        'stack' => 30
    ],
    'email' => [
        'type' => 'email',
        'value' => $__page[0]->email,
        'is' => [
            'block' => true
        ],
        'stack' => 40
    ],
    'status' => [
        'type' => 'toggle',
        'value' => $__page[0]->status,
        'values' => [
            1 => $__statuss[1],
            2 => $__statuss[2]
        ],
        'stack' => 50
    ],
    'x' => [
        'key' => 'submit',
        'type' => 'submit[]',
        'values' => array_merge($__command !== 's' ? [
            '*' . $__x => $language->update
        ] : [], array_filter([
            'page' => $language->approve,
            'draft' => $language->_approve,
            'trash' => $__command !== 's' ? $language->delete : false
        ], function($__k) use($__x) {
            return $__k !== $__x;
        }, ARRAY_FILTER_USE_KEY)),
        'stack' => 0
    ]
];