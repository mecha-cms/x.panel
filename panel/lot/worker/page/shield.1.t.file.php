<?php

$__f = [];

call_user_func(function() use($language, $url, $__page, &$__f) {
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
    $__f['*name'] = [
        'type' => 'text',
        'value' => $__n,
        'is' => [
            'block' => true,
            '!' => strpos($__n, 'about.') === 0 && Path::X($__n) === 'page'
        ],
        'stack' => 20
    ];
    $__t = Is::these(explode(',', SCRIPT_X))->has($__page[0]->extension) ? '1' : '0';
    $__f['xx'] = [
        'type' => 'submit',
        'title' => $language->submit,
        'values' => [
            (is_dir($__page[0]->path) ? '0' : '1') => $language->{$__t ? 'update' : 'rename'},
            '-1' => $language->delete,
            "" => HTML::a($language->cancel, Path::D($url->current), false, ['classes' => ['button', 'f-xx:reset']])
        ],
        'stack' => 0
    ];
});

return $__f;