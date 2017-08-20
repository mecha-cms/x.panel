<?php

if ($__states = g(LOT . DS . $__chops[0], 'php', "", false)) {
    $__kins = [[], []];
    foreach ($__states as $__v) {
        $__a = $__aa = o(File::inspect($__v));
        $__t = basename($__a->path);
        $__aa->title = '<i class="i i-f"></i> ' . $__t;
        $__a->url = $__state->path . '/::g::/' . $__chops[0] . '/' . $__t;
        $__kins[0][] = $__a;
        $__kins[1][] = $__aa;
    }
}
$__B = isset($__chops[1]) ? $__chops[1] : 'config.php';
if ($__f = File::exist(LOT . DS . $__chops[0] . DS . $__B)) {
    $__s = [
        'path' => $__f,
        'c' => File::open($__f)->import()
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
    'is' => 'page',
    'panel' => [
        'layout' => 2,
        'm:f' => true,
        'm' => [
            't' => isset($__chops[1]) && $__chops[1] !== 'config.php' ? [
                'file' => [
                    'list' => [
                        'content' => [
                            'value' => To::yaml(File::open(LOT . DS . $__path)->import()),
                            'attributes' => [
                                'data' => [
                                    'type' => 'YAML'
                                ]
                            ]
                        ],
                        'x' => [
                            'key' => 'submit',
                            'type' => 'submit[]',
                            'values' => [
                                'php' => $language->{$__command === 's' ? 'save' : 'update'},
                                '.x' => [$__command === 's' ? false : $language->delete, 'trash']
                            ],
                            'stack' => 0
                        ]
                    ]
                ],
                'folder' => false,
                'upload' => false
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
                'file' => false,
                'folder' => false,
                'upload' => false
            ]
        ],
        's' => [
            1 => [
                'kin' => [
                    'title' => $language->{count($__kins[0]) === 1 ? 'config' : 'configs'},
                    'list' => $__kins,
                    'hidden' => !$__kins[0],
                    'a' => false,
                    'stack' => 10
                ],
                'parent' => false,
                'search' => false,
                'nav' => false
            ]
        ],
        'x' => [
            's' => [
                'kin' => true,
                'parent' => true
            ]
        ]
    ]
]);

if ($__is_post && !Message::$x) {
    $__B = isset($__chops[1]) ? $__chops[1] : 'config.php';
    $__F = LOT . DS . $__chops[0] . DS . $__B;
    if ($__c = Request::post('content')) {
        File::export(From::yaml($__c))->saveTo($__F, 0600);
    } else {
        File::export(Request::post('c'))->saveTo($__F, 0600);
    }
    if ($__c = Request::post('__')) {
        $__c = array_replace_recursive((array) Extend::state(PANEL, []), (array) $__c);
        if (!Request::post('__.shield')) {
            unset($__c['shield']);
        }
        File::export($__c)->saveTo(PANEL . DS . 'lot' . DS . 'state' . DS . 'config.php', 0600);
        if (isset($__c['path']) && $__c['path'] !== $__state->path) {
            Guardian::kick($url . '/' . $__c['path'] . '/::' . $__command . '::/' . $__path);
        }
    }
    Hook::fire('on.' . $__chops[0] . '.set', [$__F, null]);
    Message::success(To::sentence($language->updateed));
    Guardian::kick($url->current);
}