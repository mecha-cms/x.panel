<?php

$__s = $__page[0]->js;

return [
    '+[js]' => [
        'key' => 'js',
        'title' => 'JavaScript',
        'type' => 'editor',
        'value' => $__s,
        'placeholder' => null,
        'union' => ['div'],
        'is' => [
            'expand' => true
        ],
        'attributes' => [
            'data' => [
                'type' => stripos($__s, '</script>') === false && stripos($__s, '<script ') === false ? 'JavaScript' : 'HTML'
            ]
        ],
        'expand' => true,
        'stack' => 10
    ]
];