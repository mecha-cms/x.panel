<?php

Hook::set('__language.url', function($__content, $__lot) use($__chops) {
    return __url__('url') . '/' . Extend::state('panel', 'path') . '/::g::/' . $__chops[0] . '/' . $__lot['slug'];
});

$site->is = 'page';
$site->is_f = 'editor';
$site->layout = 2;

Config::set('panel.t', [
    'page' => [
        'title' => $language->editor,
        'content' => __DIR__ . DS . '..' . DS . 'page' . DS . 'language.1.t.page.php',
        'stack' => 10
    ]
]);

/* `sgr` */

foreach (glob(LANGUAGE . DS . '*.page') as $v) {
    $__kins[0][] = new Page($v, [], '__language');
    $__kins[1][] = new Page($v, [], 'language');
}

Lot::set('__kins', $__kins);

if (!$__file = File::exist([
    LOT . DS . $__path . '.page',
    LANGUAGE . DS . $site->language . '.page'
])) {
    Shield::abort(PANEL_404);
}

if ($__sgr === 'g' && Path::N($__file) === 'en-us' && isset($__chops[1]) && $__chops[1] !== 'en-us') {
    Shield::abort(PANEL_404);
}

$__page = [
    new Page($__file, [], '__language'),
    new Page($__file, [], 'language')
];

Lot::set('__page', $__page);

if (Request::is('post') && !Message::$x) {
    $n = Path::N($__file);
    if (Request::post('x') === 'trash') {
        if ($n === 'en-us') {
            Shield::abort(PANEL_404); // you can’t delete the default language
        }
        Hook::NS('on.language.reset', [$__file]);
        if (!Message::$x) {
            File::open($__file)->renameTo($n . '.trash');
            Message::success(To::sentence($language->deleteed) . ' ' . HTML::a($language->restore, $__state->path . '/::r::/' . $__path . HTTP::query(['token' => $__token, 'abort' => 1]), false, ['classes' => ['right']]));
            Guardian::kick(Path::D($url->path));
        }
    }
    $s = Request::post('slug');
    if ($s === 'en-us' || ($s !== $n && File::exist(LANGUAGE . DS . $s . '.page'))) {
        Request::save('post');
        Message::error('exist', [$language->locale, '<em>' . $s . '</em>']);
    }
    Hook::NS('on.language.set', [$__file]);
    if (!Message::$x) {
        $headers = [
            'title' => false,
            'description' => false,
            'author' => false,
            'type' => 'YAML',
            'version' => '0.0.0',
            'content' => false
        ];
        foreach ($headers as $k => $v) {
            $headers[$k] = Request::post($k, $v);
        }
        $f = LANGUAGE . DS . $s . '.page';
        Page::data($headers)->saveTo($f, 0600);
        Message::success(To::sentence($language->{($__sgr === 'g' ? 'update' : 'create') . 'ed'}));
        Guardian::kick($__state->path . '/::g::/' . $__chops[0] . '/' . $s);
    }
}

if ($__sgr === 's') {
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
} else if ($__sgr === 'r') {
    if (!Request::get('token')) {
        Shield::abort(PANEL_404);
    }
    $s = Path::B($url->path);
    if (!$__file = File::exist(LANGUAGE . DS . $s . '.trash')) {
        Shield::abort(PANEL_404);
    }
    File::open($__file)->renameTo($s . '.page');
    Message::success(To::sentence($language->restoreed));
    Guardian::kick($__state->path . '/::g::/' . $__chops[0] . '/' . $s);
}