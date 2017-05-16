<?php

Hook::set('__tag.url', function($__content, $__lot) use($__state) {
    $__s = Path::F($__lot['path'], LOT);
    return rtrim(__url__('url') . '/' . $__state->path . '/::g::/' . ltrim(To::url($__s), '/'), '/');
});

$site->is = $__is_has_step ? 'pages' : 'page';
$site->is_f = $__is_has_step ? false : 'editor';
$site->layout = $__is_has_step ? 2 : 3;

Config::set('panel.t', [
    'page' => [
        'title' => $language->page,
        'content' => __DIR__ . DS . '..' . DS . 'page' . DS . 'tag.2.t.page.php',
        'stack' => 10
    ]
]);

// `sgr`
if ($__is_has_step) {
    if ($__f = Get::tags($__folder, 'draft,page', [1, 'id'], 'path')) {
        if ($__q = l(Request::get('q', ""))) {
            Message::info('search', '<em>' . $__q . '</em>');
            $__q = explode(' ', $__q);
            $__f = array_filter($__f, function($__v) use($__q) {
                $__v = Path::N($__v);
                foreach ($__q as $_) {
                    if (strpos($__v, $_) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }
        foreach ($__f as $__v) {
            $__pages[0][] = new Page($__v, [], '__tag');
            $__pages[1][] = new Page($__v, [], 'tag');
        }
        Lot::set([
            '__pages' => $__pages,
            '__pager' => [new Elevator($__f ?: [], $__chunk, $__step, $url . '/' . $__state->path . '/::g::/tag', [
                'direction' => [
                   '-1' => 'previous',
                    '0' => false,
                    '1' => 'next'
                ],
                'union' => [
                   '-2' => [
                        2 => ['rel' => null, 'classes' => ['button', 'x']]
                    ],
                   '-1' => [
                        1 => '&#x276E;',
                        2 => ['rel' => 'prev', 'classes' => ['button']]
                    ],
                    '1' => [
                        1 => '&#x276F;',
                        2 => ['rel' => 'next', 'classes' => ['button']]
                    ]
                ]
            ], '__tags')]
        ]);
    }
} else {
    if ($__is_post) {
        if (Request::post('x') === 'trash') {
            Guardian::kick(str_replace('::g::', '::r::', $url->current . HTTP::query(['token' => Request::post('token')])));
        }
        $__s = Path::N($__file);
        $__ss = Request::post('slug');
        $__x = Path::X($__file);
        $__xx = Request::post('x', $__x);
        $__d = Path::D($__file);
        $__dd = $__d . DS . $__ss;
        $__ddd = $__dd . '.' . $__xx;
        $__headers = [
            'title' => false,
            'description' => false,
            'author' => false,
            'type' => false,
            'content' => false
        ];
        foreach ($__headers as $__k => $__v) {
            if (file_exists($__dd . DS . $__k . '.data')) continue;
            $__headers[$__k] = Request::post($__k, $__v);
        }
        if ($__sgr === 's' && File::exist([
            $__folder . DS . $__ss . '.draft',
            $__folder . DS . $__ss . '.page'
        ]) || $__s !== $__ss && File::exist([
            $__dd . '.draft',
            $__dd . '.page'
        ])) {
            Request::save('post');
            Message::error('exist', [$language->slug, '<em>' . $__ss . '</em>']);
            Guardian::kick($url->current);
        }
        $__f = Path::D($__file) . DS . $__ss . '.' . $__xx;
        Hook::fire('on.tag.set', [$__f]);
        if (!Message::$x) {
            if ($__sgr === 'g') {
                Page::open($__file)->data($__headers)->save(0600);
                if ($__s !== $__ss || $__x !== $__xx) {
                    // Rename folder…
                    if ($__s !== $__ss) {
                        File::open(Path::F($__file))->renameTo($__ss);
                    }
                    // Rename file…
                    File::open($__file)->renameTo($__ss . '.' . $__xx);
                }
            } else {
                if ($__sgr === 's') {
                    $__dd = $__file . DS . $__ss; // New tag…
                }
                Page::data($__headers)->saveTo($__folder . DS . $__ss . '.' . $__xx, 0600);
            }
            // Create `id.data` file…
            if ($__s = Request::post('id', "", false)) {
                File::write($__s)->saveTo($__dd . DS . 'id.data', 0600);
            }
            // Create `time.data` file…
            if (!$__s = Request::post('time')) {
                $__s = date(DATE_WISE);
            } else {
                $__s = DateTime::createFromFormat('Y/m/d H:i:s', $__s)->format();
            }
            File::write($__s)->saveTo($__dd . DS . 'time.data', 0600);
            // Create `sort.data` file…
            if ($__s = Request::post('sort')) {
                File::write(To::json($__s))->saveTo($__dd . DS . 'sort.data', 0600);
            }
            // Create `chunk.data` file…
            if ($__s = Request::post('chunk')) {
                File::write($__s)->saveTo($__dd . DS . 'chunk.data', 0600);
            }
            if ($__sgr === 'g') {
                Message::success(To::sentence($language->updateed));
                Guardian::kick(Path::D($url->current) . '/' . $__ss);
            } else {
                Message::success(To::sentence($language->createed));
                Guardian::kick(str_replace('::s::', '::g::', $url->current) . '/' . $__ss);
            }
        }
    } else {
        if ($__sgr === 'r') {
            if (!Request::get('token')) {
                Shield::abort(PANEL_404);
            }
            if (!$__file = File::exist([
                $__folder . '.draft',
                $__folder . '.page',
                $__folder . '.archive',
                $__folder . '.trash'
            ])) {
                Shield::abort(PANEL_404);
            }
            $__k = str_replace('::r::', '::g::', $url->path);
            $__n = Path::B($__folder);
            Hook::fire('on.tag.reset', [$__file]);
            if (Message::$x) {
                Guardian::kick($__k);
            }
            if (Request::get('abort')) {
                File::open($__folder . '.trash')->renameTo($__n . '.draft');
                Message::success(To::sentence($language->restoreed));
            } else {
                File::open($__file)->renameTo($__n . '.trash');
                Message::success(To::sentence($language->deleteed) . ' ' . HTML::a($language->restore, $url->path . HTTP::query(['abort' => 1]), false, ['classes' => ['right']]));
            }
            Guardian::kick(Path::D($__k) . '/1');
        }
        if (($__file === $__folder || $__sgr === 's') && isset($__chops[1])) {
            Shield::abort(PANEL_404);
        }
    }
    if ($__file = File::exist([
        $__folder . '.draft',
        $__folder . '.page'
    ])) {
        $__page = [
            new Page($__file, [], '__tag'),
            new Page($__file, [], 'tag')
        ];
    } else {
        $__page = [
            new Page(null, [], '__tag'),
            new Page(null, [], 'tag')
        ];
    }
    Lot::set('__page', $__page);
    if ($__f = Get::tags(Path::D($__folder), 'draft,page', $__sort, 'path')) {
        $__s = Path::N($__file);
        $__f = array_filter($__f, function($__v) use($__s) {
            return Path::N($__v) !== $__s;
        });
        foreach (Anemon::eat($__f)->chunk($__chunk, 0) as $__k => $__v) {
            $__kins[0][] = new Page($__v, [], '__tag');
            $__kins[1][] = new Page($__v, [], 'tag');
        }
        $__is_has_step_kin = count($__f) > $__chunk;
        Lot::set([
            '__kins' => $__kins,
            '__is_has_step_kin' => $__is_has_step_kin
        ]);
    }
}