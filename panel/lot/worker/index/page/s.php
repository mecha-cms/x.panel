<?php

if ($__is_data) {
    $__folder = LOT . DS . $__[0];
    if (file_exists($__folder . DS . $__key . '.data')) {
        Guardian::kick(str_replace('::s::', '::g::', $url->current));
    }
    require __DIR__ . DS . '-data.php';
    require __DIR__ . DS . '-datas.php';
    require __DIR__ . DS . '-source.php';
    if ($__is_post && !Message::$x) {
        $__k = Request::post('key', "", false);
        $__f = $__folder . DS . $__k . '.data';
        if ($__k !== $__key && file_exists($__f)) {
            Request::save('post');
            Message::error('exist', [$language->key, '<em>' . $__k . '</em>']);
        }
        Hook::NS('on.data.set', [null]);
        if (!Message::$x) {
            $__content = Request::post('content', "", false);
            if (is_array($__content)) {
                $__content = json_encode($__content);
            }
            File::write($__content)->saveTo($__f, 0600);
            if ($__k !== $__key) {
                File::open($__folder . DS . $__key . '.data')->delete();
            }
            Message::success(To::sentence($language->createed));
            Guardian::kick($__state->path . '/::g::/' . $__[0] . '/+/' . $__k);
        }
    }
} else {
    require __DIR__ . DS . '-childs.php';
    require __DIR__ . DS . '-datas.php';
    require __DIR__ . DS . '-kins.php';
    if (!$__is_has_step) {
        if ($__is_post && !Message::$x) {
            $__ss = Request::post('slug');
            $__xx = Request::post('x', 'page');
            $__d = LOT . DS . $__path;
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
            if (File::exist([
                $__dd . '.draft',
                $__dd . '.page',
                $__dd . '.archive'
            ])) {
                Request::save('post');
                Message::error('exist', [$language->slug, '<em>' . $__ss . '</em>']);
            }
            Hook::fire('on.page.set', [$__ddd]);
            if (!Message::$x) {
                // Create `css.data` file…
                if (($__s = trim(Request::post('css', "", false))) !== "") {
                    File::write($__s)->saveTo($__dd . DS . 'css.data', 0600);
                } else {
                    File::open($__dd . DS . 'css.data')->delete();
                }
                // Create `js.data` file…
                if (($__s = trim(Request::post('js', "", false))) !== "") {
                    File::write($__s)->saveTo($__dd . DS . 'js.data', 0600);
                } else {
                    File::open($__dd . DS . 'js.data')->delete();
                }
                // Create `time.data` file…
                File::write(date(DATE_WISE))->saveTo($__dd . DS . 'time.data', 0600);
                // Create `sort.data` file…
                if ($__s = Request::post('sort')) {
                    File::write(To::json($__s))->saveTo($__dd . DS . 'sort.data', 0600);
                }
                // Create `chunk.data` file…
                if ($__s = Request::post('chunk')) {
                    File::write($__s)->saveTo($__dd . DS . 'chunk.data', 0600);
                }
                Page::data($__headers)->saveTo($__ddd, 0600);
                Message::success(To::sentence($language->{($__xx === 'draft' ? 'save' : 'create') . 'ed'}) . ($__xx === 'draft' ? "" : ' ' . HTML::a($language->view, Page::open($__ddd)->get('url'), true, ['classes' => ['right']])));
                Guardian::kick(str_replace('::s::', '::g::', $url->current) . '/' . $__ss);
            }
        }
        Lot::set('__page', [
            new Page(null, [], '__page'),
            new Page(null, [], 'page')
        ]);
    }
    require __DIR__ . DS . '-parent.php';
}