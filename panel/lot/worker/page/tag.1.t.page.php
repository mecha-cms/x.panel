<?php

$__types = a(Config::get('panel.f.page.types', []));
$__x = $__page[0]->state;

asort($__types);

return [
    'title' => [
        'type' => 'text',
        'value' => $__page[0]->title,
        'placeholder' => $__page[0]->title ?: $language->f_title,
        'is' => [
            'block' => true
        ],
        'attributes' => [
            'data' => [
                'slug-i' => 'title'
            ]
        ],
        'expand' => true,
        'stack' => 10
    ],
    '*slug' => [
        'type' => 'text',
        'value' => $__page[0]->slug,
        'placeholder' => $__page[0]->slug ?: To::slug($language->f_title),
        'pattern' => '^[a-z\\d]+(?:-[a-z\\d]+)*$',
        'is' => [
            'block' => true
        ],
        'attributes' => [
            'data' => [
                'slug-o' => 'title'
            ]
        ],
        'expand' => true,
        'stack' => 20
    ],
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
        'stack' => 30
    ],
    'type' => [
        'type' => 'select',
        'value' => $__page[0]->type,
        'values' => $__types,
        'stack' => 40
    ],
    'description' => [
        'type' => 'textarea',
        'value' => $__page[0]->description,
        'placeholder' => $__page[0]->description ?: $language->f_description($language->tag),
        'is' => [
            'block' => true
        ],
        'stack' => 50
    ],
    'x' => [
        'type' => 'submit',
        'title' => $language->submit,
        'values' => array_merge($__action !== 's' ? [
            '*' . $__x => $language->update
        ] : [], array_filter([
            'page' => $language->publish,
            'draft' => $language->save,
            'trash' => $__action === 's' ? null : $language->delete
        ], function($__k) use($__x) {
            return $__k !== $__x;
        }, ARRAY_FILTER_USE_KEY)),
        'stack' => 0
    ]
];