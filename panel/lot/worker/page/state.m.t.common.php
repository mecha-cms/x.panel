<?php

$__editors = a(Config::get('panel.o.page.editor', []));
$__languages = [];
$__shields = [];

asort($__editors);

call_user_func(function() use($config, &$__languages, &$__shields) {
    foreach (glob(LANGUAGE . DS . '*.page') as $__v) {
        $__languages[Path::N($__v)] = (new Page($__v, [], 'language'))->title;
    }
    foreach (glob(SHIELD . DS . '*', GLOB_ONLYDIR) as $__v) {
        if (!$__v = File::exist([
            $__v . DS . 'about.' . $config->language . '.page',
            $__v . DS . 'about.page'
        ])) continue;
        $__shields[Path::B(Path::D($__v))] = (new Page($__v))->title;
    }
    asort($__languages);
    asort($__shields);
});

return [
    'c[zone]' => [
        'key' => 'zone',
        'type' => 'select',
        'value' => $__page[0]->config['zone'],
        'values' => Get::zone(),
        'is' => [
            'block' => true
        ],
        'stack' => 10
    ],
    'c[charset]' => [
        'key' => 'charset',
        'type' => 'text',
        'title' => $language->encoding,
        'value' => $__page[0]->config['charset'],
        'placeholder' => 'utf-8',
        'stack' => 20
    ],
    'c[language]' => [
        'key' => 'language',
        'type' => 'select',
        'value' => $__page[0]->config['language'],
        'values' => $__languages,
        'stack' => 30
    ],
    'c[direction]' => [
        'key' => 'direction',
        'type' => 'toggle',
        'value' => $__page[0]->config['direction'],
        'values' => $language->o_direction,
        'stack' => 40
    ],
    'c[title]' => [
        'key' => 'title',
        'type' => 'text',
        'value' => $__page[0]->config['title'],
        'placeholder' => $__page[1]->config['title'] ?: $language->f_title,
        'is' => [
            'block' => true
        ],
        'stack' => 50
    ],
    'c[description]' => [
        'key' => 'description',
        'type' => 'textarea',
        'value' => $__page[0]->config['description'],
        'placeholder' => $__page[1]->config['description'] ?: $language->f_description($language->site),
        'union' => ['div'],
        'is' => [
            'block' => true
        ],
        'stack' => 60
    ],
    'c[shield]' => [
        'key' => 'shield',
        'type' => 'select',
        'value' => $__page[0]->config['shield'],
        'values' => $__shields,
        'stack' => 70
    ],
    'c[page][editor]' => [
        'key' => 'page-editor',
        'type' => 'select',
        'title' => $language->editor,
        'value' => isset($__page[0]->config['page']['editor']) ? $__page[0]->config['page']['editor'] : "",
        'values' => array_merge(['!' => '&#x2716;'], $__editors),
        'stack' => 80
    ],
    // the submit button
    'x' => [
        'type' => 'submit',
        'title' => $language->submit,
        'text' => $language->update,
        'value' => 'php',
        'stack' => 0
    ]
];