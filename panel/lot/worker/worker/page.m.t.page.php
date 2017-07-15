<?php

$__tags = [];
$__types = (array) Config::get('panel.o.page.type', []);
$__buttons = [
    'page' => $language->publish,
    'draft' => $language->save,
    'archive' => $language->archive,
    'trash' => $__action === 's' ? null : $language->delete
];

call_user_func(function() use($__page, &$__tags, &$__types) {
    if ($__page[0]->kind) {
        foreach ($__page[0]->kind as $__v) {
            $__tags[] = To::tag($__v);
        }
    }
    sort($__tags);
    asort($__types);
});

$__X = $__page[0]->state;
$__N = $__page[0]->slug;

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
        'placeholder' => $__N ?: To::slug($language->f_title),
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
        'placeholder' => $__page[0]->description ?: $language->f_description($language->{$__chops[0]}),
        'stack' => 50
    ],
    'author' => [
        'type' => 'text',
        'value' => $__page[0]->author,
        'placeholder' => $language->f_user,
        'is' => [
            'block' => true,
            'hidden' => true
        ],
        'stack' => 60
    ],
    'link' => [
        'type' => 'url',
        'value' => $__page[0]->link,
        'placeholder' => $language->f_link,
        'is' => [
            'block' => true
        ],
        'stack' => 70
    ],
    'email' => [
        'type' => 'email',
        'value' => $__page[0]->email,
        'placeholder' => $language->f_email,
        'is' => [
            'block' => true,
            'hidden' => true
        ],
        'stack' => 80
    ],
    'tags' => [
        'type' => 'query',
        'value' => implode(', ', (array) $__tags) ?: null,
        'placeholder' => $language->f_query,
        'if' => Extend::exist('tag'),
        'is' => [
            'block' => true
        ],
        'stack' => 90
    ],
    '+[time]' => (
        // Detect time format on the page slug @see `engine\kernel\page.php`
        $__N &&
        is_numeric($__N[0]) &&
        (
            // `2017-04-21.page`
            substr_count($__N, '-') === 2 ||
            // `2017-04-21-14-25-00.page`
            substr_count($__N, '-') === 5
        ) &&
        is_numeric(str_replace('-', "", $__N)) &&
        preg_match('#^\d{4,}-\d{2}-\d{2}(?:-\d{2}-\d{2}-\d{2})?$#', $__N)
    ) ? null : [
        'key' => 'time',
        'type' => 'date',
        'value' => $__page[0]->time,
        'if' => $__action !== 's',
        'stack' => 100
    ],
    'x' => [
        'type' => 'submit[]',
        'title' => $language->submit,
        'values' => array_merge($__action !== 's' ? [
            '*' . $__X => $language->update . ' (' . Anemon::alter($__X, $__buttons) . ')'
        ] : [], array_filter($__buttons, function($__k) use($__X) {
            return $__k !== $__X;
        }, ARRAY_FILTER_USE_KEY)),
        'order' => ['*' . $__X, 'page', 'draft', 'archive', 'trash'],
        'stack' => 0
    ]
];