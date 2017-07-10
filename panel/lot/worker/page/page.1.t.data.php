
<?php

$__s = substr($__path, -2) === '/+';
$__values = [
    'data' => $language->{$__s ? 'save' : 'update'},
    'trash' => $__s ? null : $language->delete
];

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
    '*key' => [
        'type' => 'text',
        'value' => $__data[0]->key,
        'pattern' => '^[a-z\\d]+(?:_[a-z\\d]+)*$',
        'stack' => 20
    ],
    'x' => [
        'type' => 'submit',
        'title' => $language->submit,
        'values' => $__values,
        'stack' => 0
    ]
];