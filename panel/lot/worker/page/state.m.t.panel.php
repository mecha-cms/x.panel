<?php

$__shields = [];

call_user_func(function() use($config, &$__shields) {
    foreach (glob(PANEL . DS . 'lot' . DS . 'shield' . DS . '*', GLOB_ONLYDIR) as $__v) {
        if (!$__v = File::exist([
            $__v . DS . 'about.' . $config->language . '.page',
            $__v . DS . 'about.page'
        ])) continue;
        $__shields[Path::B(Path::D($__v))] = (new Page($__v))->title;
    }
    asort($__shields);
});

return [
    '__[shield]' => [
        'key' => 'shield',
        'type' => 'select',
        'value' => $__state->shield,
        'values' => $__shields,
        'stack' => 10
    ],
    '__[path]' => [
        'key' => 'slug',
        'type' => 'text',
        'value' => $__state->path,
        'stack' => 20
    ],
    '__[sort][0]' => [
        'key' => 'order',
        'type' => 'toggle',
        'value' => $__state->sort[0],
        'values' => $language->o_sort[0],
        'stack' => 30
    ],
    '__[sort][1]' => [
        'key' => 'by',
        'type' => 'toggle',
        'value' => $__state->sort[1],
        'values' => $language->o_sort[1],
        'stack' => 40
    ],
    '__[chunk]' => [
        'key' => 'chunk',
        'type' => 'number',
        'title' => $language->__->panel->chunk,
        'value' => $__state->chunk,
        'attributes' => [
            'min' => 0,
            'max' => 50
        ],
        'stack' => 50
    ]
];