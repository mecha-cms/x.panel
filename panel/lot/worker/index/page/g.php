<?php

if ($__is_data) {
    $site->is = 'page';
    $__folder = LOT . DS . $__[0];
    if (!$__f = File::exist($__folder . DS . $__key . '.data')) {
        Shield::abort(PANEL_404);
    }
    require __DIR__ . DS . '-data.php';
    require __DIR__ . DS . '-datas.php';
    require __DIR__ . DS . '-source.php';
    if ($__is_post && !Message::$x) {
        if (Request::post('x') === 'trash') {
            Guardian::kick(str_replace('::g::', '::r::', $url->current . HTTP::query(['token' => Request::post('token')])));
        }
        $__k = Request::post('key', "", false);
        $__f = $__folder . DS . $__k . '.data';
        if ($__k !== $__key && file_exists($__f)) {
            Request::save('post');
            Message::error('exist', [$language->key, '<em>' . $__k . '</em>']);
        }
        Hook::NS('on.data.set', [$__f]);
        if (!Message::$x) {
            $__content = Request::post('content', "", false);
            if (is_array($__content)) {
                $__content = json_encode($__content);
            }
            File::write($__content)->saveTo($__f, 0600);
            if ($__k !== $__key) {
                File::open($__folder . DS . $__key . '.data')->delete();
            }
            Message::success(To::sentence($language->updateed));
            Guardian::kick($__state->path . '/::g::/' . $__[0] . '/+/' . $__k);
        }
    }
} else {
    require __DIR__ . DS . '-childs.php';
    require __DIR__ . DS . '-datas.php';
    require __DIR__ . DS . '-kins.php';
    if (!$__is_has_step) {
        if ($__is_post && !Message::$x) {
            $__f = LOT . DS . $__path;
            if (!$__f = File::exist([
                $__f . '.draft',
                $__f . '.page',
                $__f . '.archive'
            ])) {
                Shield::abort(PANEL_404);
            }
            if ($__s = Request::post('as_')) {
                if ($__state_shield = Shield::state($config->shield)) {
                    $__state_shield['path'] = $__s;
                    File::export($__state_shield)->saveTo(SHIELD . DS . $config->shield . DS . 'state' . DS . 'config.php');
                } else if ($__state_extend_page = Extend::state('page')) {
                    $__state_extend_page['path'] = $__s;
                    File::export($__state_extend_page)->saveTo(EXTEND . DS . 'page' . DS . 'lot' . DS . 'state' . DS . 'config.php');
                }
            }
            $__p = Path::F($__f) . DS . Path::B($__f);
            if (Request::post('as_page')) {
                File::write("")->saveTo($__p, 0600); // a placeholder page
            } else {
                if (File::open($__p)->read(X) === "") File::open($__p)->delete();
            }
            if (Request::post('x') === 'trash') {
                Guardian::kick(str_replace('::g::', '::r::', $url->current) . HTTP::query(['token' => Request::post('token')]));
            }
            $__s = Path::N($__f);
            $__ss = Request::post('slug');
            $__x = Path::X($__f);
            $__xx = Request::post('x', $__x);
            $__d = Path::D($__f);
            $__dd = $__d . DS . $__ss;
            $__ddd = $__dd . '.' . $__xx;
            $__dddd = [];
            foreach (explode("\n", trim(Request::post('__data', ""))) as $__v) {
                $__v = trim($__v);
                if ($__v === "") continue;
                $__v = explode(Page::$v[2], $__v, 2);
                if (!isset($__v[1])) $__v[1] = $__v[0];
                $__dddd[trim($__v[0])] = trim($__v[1]);
            }
            $__headers = array_replace([
                'title' => false,
                'description' => false,
                'author' => false,
                'type' => false,
                'link' => false,
                'content' => false
            ], $__dddd);
            foreach ($__headers as $__k => $__v) {
                if (file_exists($__dd . DS . $__k . '.data')) continue;
                $__headers[$__k] = Request::post($__k, $__v);
            }
            if ($__s !== $__ss && File::exist([
                $__dd . '.draft',
                $__dd . '.page',
                $__dd . '.archive'
            ])) {
                Request::save('post');
                Message::error('exist', [$language->slug, '<em>' . $__ss . '</em>']);
            }
            $__ff = Path::D($__f) . DS . $__ss . '.' . $__xx;
            Hook::fire('on.page.set', [$__ff]);
            if (!Message::$x) {
                Page::open($__f)->data($__headers)->save(0600);
                if ($__s !== $__ss || $__x !== $__xx) {
                    // Rename folder…
                    if ($__s !== $__ss) {
                        File::open(Path::F($__f))->renameTo($__ss);
                    }
                    // Rename file…
                    File::open($__f)->renameTo($__ss . '.' . $__xx);
                }
                // Create `time.data` file…
                if (!$__s = Request::post('time')) {
                    $__s = date(DATE_WISE);
                } else {
                    $__s = DateTime::createFromFormat('Y/m/d H:i:s', $__s)->format(DATE_WISE);
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
                Message::success(To::sentence($language->{($__xx === 'draft' ? 'save' : 'update') . 'ed'}) . ($__xx === 'draft' ? "" : ' ' . HTML::a($language->view, Page::open($__ddd)->get('url'), true, ['classes' => ['right']])));
                Guardian::kick(Path::D($url->current) . '/' . $__ss);
            }
        }
        require __DIR__ . DS . '-page.php';
    }
    require __DIR__ . DS . '-pages.php';
    require __DIR__ . DS . '-parent.php';
}