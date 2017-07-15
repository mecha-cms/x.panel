<?php

$__types = a(Config::get('panel.o.page.type', []));
$__s = isset($__page[0]->config['page']) ? (array) $__page[0]->config['page'] : [];
$__ss = [
    'title' => null,
    'author' => null,
    'type' => 'HTML',
    'editor' => Extend::state('panel', 'editor', ""),
    'content' => null
];

$__s = Anemon::extend($__ss, $__s);

asort($__types);

return [
    'c[page][title]' => [
        'key' => 'page-title',
        'type' => 'text',
        'title' => $language->title,
        'value' => $__s['title'],
        'placeholder' => $__s['title'] ?: $language->f_title,
        'is' => [
            'block' => true
        ],
        'stack' => 10
    ],
    'c[page][author]' => [
        'key' => 'page-author',
        'type' => 'text',
        'title' => $language->author,
        'value' => $__s['author'],
        'placeholder' => $__s['author'] ?: $language->f_user,
        'is' => [
            'block' => true
        ],
        'stack' => 20
    ],
    'c[page][type]' => [
        'key' => 'page-type',
        'type' => 'select',
        'title' => $language->type,
        'value' => $__s['type'],
        'values' => $__types,
        'stack' => 30
    ],
    'c[page][content]' => [
        'key' => 'page-content',
        'type' => 'editor',
        'title' => $language->content,
        'value' => $__s['content'],
        'placeholder' => $language->f_content,
        'union' => ['div'],
        'is' => [
            'expand' => true
        ],
        'attributes' => [
            'data' => [
                'type' => $__s['type']
            ]
        ],
        'stack' => 40
    ]
];