<?php

$__kicks = $__shields = [];

call_user_func(function() use($config, $language, &$__kicks, &$__shields) {
    foreach ((array) a(Config::get('panel.n', [])) as $__k => $__v) {
        if ($__k === '+') continue;
        $__kicks[$__k] = isset($__v['text']) ? $__v['text'] : $language->{$__k};
    }
    foreach (glob(PANEL . DS . 'lot' . DS . 'shield' . DS . '*', GLOB_ONLYDIR) as $__v) {
        if (!$__v = File::exist([
            $__v . DS . 'about.' . $config->language . '.page',
            $__v . DS . 'about.page'
        ])) continue;
        $__shields[Path::B(Path::D($__v))] = (new Page($__v))->title;
    }
    asort($__kicks);
    asort($__shields);
});

return [
    '*__[kick]' => [
        'key' => 'kick',
        'type' => 'select',
        'title' => $language->default,
        'value' => $__state->kick,
        'values' => $__kicks,
        'stack' => 10
    ],
    '*__[shield]' => [
        'key' => 'shield',
        'type' => 'select',
        'value' => $__state->shield,
        'values' => ['!' => ""] + $__shields,
        'stack' => 20
    ],
    '*__[path]' => [
        'key' => 'path',
        'type' => 'text',
        'value' => $__state->path,
        'pattern' => '^[a-z\\d]+(?:[-._\\/][a-z\\d]+)*$',
        'stack' => 30
    ],
    '__[sort][0]' => [
        'key' => 'order',
        'type' => 'toggle',
        'value' => $__state->sort[0],
        'values' => $language->o_sort[0],
        'stack' => 40
    ],
    '__[sort][1]' => [
        'key' => 'by',
        'type' => 'toggle',
        'value' => $__state->sort[1],
        'values' => $language->o_sort[1],
        'stack' => 50
    ],
    '__[chunk]' => [
        'key' => 'chunk',
        'type' => 'number',
        'value' => $__state->chunk,
        'attributes' => [
            'min' => 0,
            'max' => 50
        ],
        'stack' => 60
    ],
    'c[panel][v][n]' => [
        'key' => 'navigation',
        'type' => 'toggle[]',
        'value' => ((array) a(Config::get('panel.v.n', []))) ?: $__kicks,
        'values' => $__kicks,
        'stack' => 70
    ]
];