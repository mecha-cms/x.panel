<?php

$__statuss = (array) $language->o_users;

$__type = isset($config->page->type) ? $config->page->type : 'HTML';
$__types = a(Config::get('panel.f.page.types', []));

asort($__types);

return [
    '*user' => [
        'type' => 'text',
        'placeholder' => User::ID . l($language->user),
        'pattern' => '^' . x(User::ID) . '[a-z\\d]+(?:-[a-z\\d]+)*$',
        'is' => [
            'block' => true
        ],
        'stack' => 10
    ],
    'status' => [
        'type' => 'toggle',
        'values' => [
            '1' => $__statuss[1],
            (g(USER, 'page') ? "" : '.') . '2' => $__statuss[2]
        ],
        'stack' => 20
    ],
    '*author' => [
        'type' => 'text',
        'is' => [
            'block' => true
        ],
        'stack' => 30
    ],
    'email' => [
        'type' => 'email',
        'is' => [
            'block' => true
        ],
        'stack' => 40
    ],
    'link' => [
        'type' => 'url',
        'placeholder' => $url->protocol,
        'is' => [
            'block' => true
        ],
        'stack' => 50
    ],
    'content' => [
        'type' => 'editor',
        'title' => $language->description,
        'placeholder' => $language->f_description($language->user),
        'union' => ['div'],
        'is' => [
            'expand' => true
        ],
        'attributes' => [
            'data' => [
                'type' => $__type
            ]
        ],
        'stack' => 60
    ],
    'type' => [
        'type' => 'select',
        'value' => $__type,
        'values' => $__types,
        'stack' => 70
    ],
    // the submit button
    'x' => [
        'type' => 'submit',
        'title' => $language->submit,
        'values' => [
            'page' => $language->create,
            // 'draft' => $language->save
        ],
        'stack' => 0
    ]
];