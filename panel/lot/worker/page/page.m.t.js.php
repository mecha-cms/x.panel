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
        'height' => true,
        'attributes' => [
            'data[]' => [
                'type' => stripos($__s, '</script>') === false && stripos($__s, '<script ') === false ? 'JavaScript' : 'HTML'
            ]
        ],
        'expand' => true,
        'stack' => 10
    ]
];