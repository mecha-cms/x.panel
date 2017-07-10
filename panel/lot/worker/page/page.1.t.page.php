<?php

$__tags = [];
$__types = (array) Config::get('panel.f.page.types', []);
$__x = $__page[0]->state;

call_user_func(function() use($__page, &$__tags, &$__types) {
    if ($__page[0]->kind) {
        foreach ($__page[0]->kind as $__v) {
            $__tags[] = To::tag($__v);
        }
    }
    sort($__tags);
    asort($__types);
});

$__x = $__page[0]->state;

return [
    'title' => [
        'type' => 'text',
        'value' => $__page[0]->title,
        'placeholder' => $language->f_title,
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
        'placeholder' => To::slug($language->f_title),
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
        'placeholder' => $language->f_description($language->page),
        'union' => ['div'],
        'is' => [
            'block' => true
        ],
        'stack' => 50
    ],
    'link' => [
        'type' => 'url',
        'value' => $__page[0]->link,
        'placeholder' => $url->protocol,
        'is' => [
            'block' => true
        ],
        'stack' => 60
    ],
    'tags' => [
        'type' => 'text',
        'value' => implode(', ', (array) $__tags) ?: null,
        'placeholder' => $language->f_query,
        'if' => Extend::exist('tag'),
        'is' => [
            'block' => true
        ],
        'attributes' => [
            'classes' => [3 => 'query']
        ],
        'stack' => 70
    ],
    'time' => [
        'type' => 'text',
        'value' => (new Date($__page[0]->time))->format('Y/m/d H:i:s'),
        'placeholder' => date('Y/m/d H:i:s'),
        'if' => $__action !== 's',
        'attributes' => [
            'classes' => [2 => 'date']
        ],
        'stack' => 80
    ],
    // the submit button(s)
    'x' => [
        'type' => 'submit',
        'title' => $language->submit,
        'text' => $language->update,
        'values' => array_merge($__action !== 's' ? [
            '*' . $__x => $language->update
        ] : [], array_filter([
            'page' => $language->publish,
            'draft' => $language->save,
            'archive' => $language->archive,
            'trash' => $__action !== 's' ? $language->delete : null
        ], function($__k) use($__x) {
            return $__k !== $__x;
        }, ARRAY_FILTER_USE_KEY)),
        'stack' => 0
    ]
];