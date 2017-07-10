<?php

Config::set([
    'is' => 'page',
    'is_f' => 'editor',
    'layout' => 2,
    'panel' => [
        'm' => [
            't' => [
                'page' => [
                    'title' => $language->editor,
                    'stack' => 10
                ]
            ]
        ]
    ]
]);

Hook::set('__language.url', function($__content, $__lot) use($__state, $__chops) {
    return $__state->path . '/::g::/' . $__chops[0] . '/' . $__lot['slug'];
});

/* `sgr` */

foreach (glob(LANGUAGE . DS . '*.page') as $__v) {
    $__kins[0][] = new Page($__v, [], '__language');
    $__kins[1][] = new Page($__v, [], 'language');
}

Lot::set('__kins', $__kins);

if (!$__file = File::exist([
    LOT . DS . $__path . '.page',
    LANGUAGE . DS . $site->language . '.page'
])) {
    Shield::abort(PANEL_404);
}

if ($__action === 'g' && Path::N($__file) === 'en-us' && isset($__chops[1]) && $__chops[1] !== 'en-us') {
    Shield::abort(PANEL_404);
}

$__page = [
    new Page($__file, [], '__language'),
    new Page($__file, [], 'language')
];

Lot::set('__page', $__page);

if (Request::is('post') && !Message::$x) {
    $__n = Path::N($__file);
    if (Request::post('x') === 'trash') {
        if ($__n === 'en-us') {
            Shield::abort(PANEL_404); // you can’t delete the default language
        }
        Hook::NS('on.language.reset', [$__file]);
        if (!Message::$x) {
            File::open($__file)->renameTo($__n . '.trash');
            Message::success(To::sentence($language->deleteed) . ' ' . HTML::a($language->restore, $__state->path . '/::r::/' . $__path . HTTP::query(['token' => $__token, 'abort' => 1]), false, ['classes' => ['right']]));
            Guardian::kick(Path::D($url->path));
        }
    }
    $__s = Request::post('slug');
    if ($__s === 'en-us' || ($__s !== $__n && File::exist(LANGUAGE . DS . $__s . '.page'))) {
        Request::save('post');
        Message::error('exist', [$language->locale, '<em>' . $__s . '</em>']);
    }
    Hook::NS('on.language.set', [$__file]);
    if (!Message::$x) {
        $__headers = [
            'title' => false,
            'description' => false,
            'author' => false,
            'type' => 'YAML',
            'version' => '0.0.0',
            'content' => false
        ];
        foreach ($__headers as $__k => $__v) {
            $__headers[$__k] = Request::post($__k, $__v);
        }
        $__f = LANGUAGE . DS . $__s . '.page';
        Page::data($__headers)->saveTo($__f, 0600);
        if ($__s !== $__n) {
            File::open($__file)->delete(); // slug has been changed, delete the old file!
        }
        Message::success(To::sentence($language->{($__action === 'g' ? 'update' : 'create') . 'ed'}));
        Guardian::kick($__state->path . '/::g::/' . $__chops[0] . '/' . $__s);
    }
}

if ($__action === 's') {
    if (isset($__chops[1])) {
        Shield::abort(PANEL_404);
    }
    Lot::set('__page', [
        new Page(null, [
            'type' => 'YAML',
            'content' => $__page[0]->content
        ], '__language'),
        $__page[1]
    ]);
} else if ($__action === 'r') {
    if (!Request::get('token')) {
        Shield::abort(PANEL_404);
    }
    $__s = Path::B($url->path);
    if (!$__file = File::exist(LANGUAGE . DS . $__s . '.trash')) {
        Shield::abort(PANEL_404);
    }
    File::open($__file)->renameTo($__s . '.page');
    Message::success(To::sentence($language->restoreed));
    Guardian::kick($__state->path . '/::g::/' . $__chops[0] . '/' . $__s);
}