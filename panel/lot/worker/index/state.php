<?php

Config::set([
    'is' => 'page',
    'panel' => [
        'layout' => 2,
        'c:f' => 'editor',
        'm' => [
            't' => isset($__chops[1]) && $__chops[1] !== 'config' ? [
                'editor' => [
                    'content' => [
                        'content' => [
                            'type' => 'editor',
                            'value' => To::yaml(File::open(STATE . DS . $__chops[1] . '.php')->import()),
                            'is' => [
                                'expand' => true
                            ],
                            'attributes' => [
                                'data' => [
                                    'type' => 'YAML'
                                ]
                            ],
                            'expand' => true,
                            'stack' => 10
                        ],
                        'x' => [
                            'type' => 'submit',
                            'title' => $language->submit,
                            'values' => [
                                'php' => $language->{$__action === 's' ? 'save' : 'update'},
                                '.x' => [$__action === 's' ? null : $language->delete, 'trash']
                            ],
                            'stack' => 0
                        ]
                    ],
                    'stack' => 10
                ]
            ] : [
                'common' => [
                    'legend' => $language->site,
                    'stack' => 10
                ],
                'page' => [
                    'description' => $language->h_state_page,
                    'stack' => 20
                ],
                'panel' => [
                    'title' => $language->states,
                    'legend' => $language->__title,
                    'stack' => 30
                ]
            ]
        ]
    ]
]);

if ($__files = g(STATE, 'php')) {
    foreach ($__files as $__v) {
        $__v = o(File::inspect($__v));
        $__v->title = '<i class="i i-1"></i> ' . $__v->name . '.' . $__v->extension;
        $__v->url = $__state->path . '/::g::/' . $__chops[0] . '/' . $__v->name;
        $__kins[0][] = $__v;
        $__kins[1][] = $__v;
    }
}

$__name = isset($__chops[1]) ? $__chops[1] : 'config';
if ($__file = File::exist(STATE . DS . $__name . '.php')) {
    $__s = [
        'path' => $__file,
        'config' => File::open($__file)->import()
    ];
    $__page = [
        new Page(null, $__s, '__state'),
        new Page(null, $__s, 'state')
    ];
}

Lot::set([
    '__kins' => $__kins,
    '__page' => $__page
]);

if ($__is_post) {
    if ($__c = Request::post('content')) {
        File::export(From::yaml($__c))->saveTo(STATE . DS . $__name . '.php', 0600);
    } else {
        File::export(Request::post('c'))->saveTo(STATE . DS . $__name . '.php', 0600);
    }
    if (!isset($__chops[1]) || $__chops[1] === 'config') {
        Message::success('update', [$language->setting, '<strong>' . $language->common . '</strong>']);
        Message::success('update', [$language->setting, '<strong>' . $language->page . '</strong>']);
    } else {
        Message::success('update', [$language->setting, '<em>' . $__name . '.php</em>']);
    }
    if ($__c = Request::post('__')) {
        File::export(array_replace_recursive(Extend::state(PANEL, []), $__c))->saveTo(PANEL . DS . 'lot' . DS . 'state' . DS . 'config.php', 0600);
        Message::success('update', [$language->setting, '<strong>' . $language->states . '</strong>']);
        if (isset($__c['path']) && $__c['path'] !== $__state->path) {
            Guardian::kick($url . '/' . $__c['path'] . '/::' . $__action . '::/' . $__path);
        }
    }
    Guardian::kick($url->current);
}