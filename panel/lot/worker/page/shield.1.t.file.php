<?php

$__f = [];

if (Is::these(explode(',', SCRIPT_X))->has($__page[0]->extension)) {
    $__f['content'] = [
        'type' => 'editor',
        'value' => file_get_contents($__page[0]->path),
        'placeholder' => $language->f_content,
        'union' => ['div'],
        'is' => [
            'expand' => true
        ],
        'attributes' => [
            'data' => [
                'type' => Anemon::alter($__page[0]->extension, [
                    'css' => 'CSS',
                    'html' => 'HTML',
                    'js' => 'JavaScript',
                    'page' => 'YAML',
                    'php' => 'PHP'
                ])
            ]
        ],
        'expand' => true,
        'stack' => 10
    ];
} else if (Is::these(explode(',', IMAGE_X))->has($__page[0]->extension)) {
    $__f['content'] = [
        'title' => u($__page[0]->extension),
        'type' => 'content',
        'value' => HTML::img($__page[0]->url),
        'union' => ['div'],
        'stack' => 10
    ];
}

$__n = $__page[0]->name;

$__f['name'] = [
    'type' => 'text',
    'value' => $__n,
    'is' => [
        'block' => true,
        '*' => true,
        '!' => strpos($__n, 'about.') === 0 && Path::X($__n) === 'page'
    ],
    'stack' => 10.1
];

return $__f;