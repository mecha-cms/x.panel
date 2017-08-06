<?php

Config::set([
    'is' => 'page',
    'panel' => [
        'layout' => 2,
        'm:f' => true
    ]
]);

Hook::set('shield.enter', function() {
    extract(Lot::get(null, []));
    if ($__states = g(LOT . DS . $__chops[0], 'php', "", false)) {
        $__kins = [[], []];
        foreach ($__states as $__v) {
            $__v = o(File::inspect($__v));
            $__b = basename($__v->path);
            $__v->title = '<i class="i i-f"></i> ' . $__b;
            $__v->url = $__state->path . '/::g::/' . $__chops[0] . '/' . $__b;
            $__kins[0][] = $__v;
            $__kins[1][] = $__v;
        }
    }
    $__B = isset($__chops[1]) ? $__chops[1] : 'config.php';
    if ($__f = File::exist(LOT . DS . $__chops[0] . DS . $__B)) {
        $__s = [
            'path' => $__f,
            'config' => File::open($__f)->import()
        ];
        $__page = [
            new Page(null, $__s, '__' . $__chops[0]),
            new Page(null, $__s, $__chops[0])
        ];
    }
    Lot::set([
        '__kins' => $__kins,
        '__page' => $__page
    ]);
    Config::set([
        'panel' => [
            'm' => [
                't' => isset($__chops[1]) && $__chops[1] !== 'config.php' ? [
                    'editor' => [
                        'list' => [
                            'content' => [
                                'type' => 'editor',
                                'value' => To::yaml(File::open(STATE . DS . $__chops[1])->import()),
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
                                'type' => 'submit[]',
                                'title' => $language->submit,
                                'values' => [
                                    'php' => $language->{$__command === 's' ? 'save' : 'update'},
                                    '.x' => [$__command === 's' ? null : $language->delete, 'trash']
                                ],
                                'stack' => 0
                            ]
                        ],
                        'stack' => 10
                    ],
                    'file' => null,
                    'folder' => null,
                    'package' => null
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
                    ],
                    'file' => null,
                    'folder' => null,
                    'package' => null
                ]
            ],
            's' => [
                1 => [
                    'kin' => [
                        'title' => $language->{count($__kins[0]) === 1 ? 'config' : 'configs'},
                        'list' => $__kins,
                        'if' => $__kins[0],
                        'a' => null,
                        'stack' => 10
                    ],
                    'parent' => null,
                    'search' => null,
                    'nav' => null
                ]
            ]
        ]
    ]);
}, 0);

if ($__is_post) {
    $__N = isset($__chops[1]) ? $__chops[1] : 'config';
    if ($__c = Request::post('content')) {
        File::export(From::yaml($__c))->saveTo(STATE . DS . $__N . '.php', 0600);
    } else {
        File::export(Request::post('c'))->saveTo(STATE . DS . $__N . '.php', 0600);
    }
    if (!isset($__chops[1]) || $__chops[1] === 'config') {
        Message::success('update', [$language->setting, '<strong>' . $language->common . '</strong>']);
        Message::success('update', [$language->setting, '<strong>' . $language->page . '</strong>']);
    } else {
        Message::success('update', [$language->setting, '<em>' . $__N . '.php</em>']);
    }
    if ($__c = Request::post('__')) {
        $__c = array_replace_recursive((array) Extend::state(PANEL, []), (array) $__c);
        if (!Request::post('__.shield')) {
            unset($__c['shield']);
        }
        File::export($__c)->saveTo(PANEL . DS . 'lot' . DS . 'state' . DS . 'config.php', 0600);
        Message::success('update', [$language->setting, '<strong>' . $language->states . '</strong>']);
        if (isset($__c['path']) && $__c['path'] !== $__state->path) {
            Guardian::kick($url . '/' . $__c['path'] . '/::' . $__command . '::/' . $__path);
        }
    }
    Guardian::kick($url->current);
}