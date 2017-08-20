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
        'value' => $__page[0]->c['zone'],
        'values' => Get::zones(),
        'width' => true,
        'stack' => 10
    ],
    'c[charset]' => [
        'key' => 'charset',
        'type' => 'text',
        'title' => $language->encoding,
        'value' => $__page[0]->c['charset'],
        'placeholder' => 'utf-8',
        'stack' => 20
    ],
    'c[language]' => [
        'key' => 'language',
        'type' => 'select',
        'value' => $__page[0]->c['language'],
        'values' => $__languages,
        'stack' => 30
    ],
    'c[direction]' => [
        'key' => 'direction',
        'type' => 'toggle',
        'value' => $__page[0]->c['direction'],
        'values' => $language->o_direction,
        'stack' => 40
    ],
    'c[title]' => [
        'key' => 'title',
        'type' => 'text',
        'value' => $__page[0]->c['title'],
        'placeholder' => $__page[1]->c['title'] ?: $language->f_title,
        'width' => true,
        'stack' => 50
    ],
    'c[description]' => [
        'key' => 'description',
        'type' => 'textarea',
        'value' => $__page[0]->c['description'],
        'placeholder' => $__page[1]->c['description'] ?: $language->f_description($language->site),
        'union' => ['div'],
        'stack' => 60
    ],
    'c[shield]' => [
        'key' => 'shield',
        'type' => 'select',
        'value' => $__page[0]->c['shield'],
        'values' => $__shields,
        'stack' => 70
    ],
    'c[page][editor]' => [
        'key' => 'page-editor',
        'type' => 'select',
        'title' => $language->editor,
        'value' => isset($__page[0]->c['page']['editor']) ? $__page[0]->c['page']['editor'] : "",
        'values' => array_merge(['!' => ""], $__editors),
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