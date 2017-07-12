<?php

$__languages = [];
$__shields = [];

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
    'config[zone]' => [
        'key' => 'zone',
        'type' => 'select',
        'value' => $__page[0]->config['zone'],
        'values' => Get::zone(),
        'is' => [
            'block' => true
        ],
        'stack' => 10
    ],
    'config[charset]' => [
        'key' => 'charset',
        'type' => 'text',
        'title' => $language->encoding,
        'value' => $__page[0]->config['charset'],
        'placeholder' => 'utf-8',
        'stack' => 20
    ],
    'config[language]' => [
        'key' => 'language',
        'type' => 'select',
        'value' => $__page[0]->config['language'],
        'values' => $__languages,
        'stack' => 30
    ],
    'config[direction]' => [
        'key' => 'direction',
        'type' => 'toggle',
        'value' => $__page[0]->config['direction'],
        'values' => $language->f_directions,
        'stack' => 40
    ],
    'config[title]' => [
        'key' => 'title',
        'type' => 'text',
        'value' => $__page[0]->config['title'],
        'placeholder' => $__page[1]->config['title'] ?: $language->f_title,
        'is' => [
            'block' => true
        ],
        'stack' => 50
    ],
    'config[description]' => [
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
    'config[shield]' => [
        'key' => 'shield',
        'type' => 'select',
        'value' => $__page[0]->config['shield'],
        'values' => $__shields,
        'stack' => 70
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