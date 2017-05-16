<?php

Hook::set('__page.url', function($content, $lot) use($__state) {
    $s = Path::F($lot['path'], LOT);
    return rtrim(__url__('url') . '/' . $__state->path . '/::g::/' . ltrim(To::url($s), '/'), '/');
});

if (Extend::exist('tag')) {
    function fn_tags_set($__file) {
        if (!Message::$x) {
            global $language;
            // Create `kind.data` file…
            if ($s = Request::post('tags')) {
                $s = explode(',', $s);
                $__kinds = [];
                $__author = Request::post('author', false);
                if (count($s) > 12) {
                    Request::save('post');
                    Message::error('max', [$language->tags, '<strong>12</strong>']);
                } else {
                    foreach ($s as $v) {
                        $v = To::slug($v);
                        if (($id = From::tag($v)) !== false) {
                            $__kinds[] = $id;
                        } else {
                            $__o = 0;
                            foreach (glob(TAG . DS . '*' . DS . 'id.data', GLOB_NOSORT) as $vv) {
                                $id = (int) file_get_contents($vv);
                                if ($id > $__o) $__o = $id;
                            }
                            ++$__o;
                            $__kinds[] = $__o;
                            $f = TAG . DS . $v;
                            Hook::fire('on.tag.set', [$__file, $__o]);
                            File::write(date(DATE_WISE))->saveTo($f . DS . 'time.data', 0600);
                            File::write($__o)->saveTo($f . DS . 'id.data', 0600);
                            Page::data([
                                'title' => $v,
                                'author' => $__author
                            ])->saveTo($f . '.page', 0600);
                            Message::info('create', $language->tag . ' <em>' . str_replace('-', ' ', $v) . '</em>');
                        }
                    }
                    $__kinds = array_unique($__kinds);
                    sort($__kinds);
                    Hook::fire('on.tags.set', [$__file, $__kinds]);
                    if (!Message::$x) {
                        File::write(To::json($__kinds))->saveTo(Path::F($__file) . DS . 'kind.data', 0600);
                    }
                }
            } else {
                Hook::fire('on.tags.reset', [$__file, []]);
                File::open(Path::F($__file) . DS . 'kind.data')->delete();
            }
        }
        return $__file;
    }

    Hook::set('on.page.set', 'fn_tags_set');

    // Delete trash…
    Hook::set('on.user.exit', function() {
        foreach (File::explore(TAG, true, true) as $k => $v) {
            if ($v === 0) continue;
            $s = Path::F($k);
            foreach (g($s, 'trash') as $v) {
                File::open($v)->delete();
            }
            if (Path::X($k) === 'trash') {
                File::open($k)->delete();
                if (Is::D($s)) {
                    File::open($s)->delete();
                }
            }
        }
    });
}


$__is_data = substr($url->path, -2) === '/+' || strpos($url->path, '/+/') !== false;

Lot::set('__is_data', $__is_data);

// `panel/::s::/page` → new page in `lot\page`
// `panel/::g::/page` → index view
// `panel/::s::/page/blog` → new child page for `lot\page\blog`
// `panel/::g::/page/blog` → edit page of `lot\page\blog`
$site->is = $__is_has_step ? 'pages' : 'page';
$site->is_f = $__is_has_step ? false : 'editor';
$site->layout = $__is_has_step || $__is_data ? 2 : 3;

Config::set('panel.t', substr($__path, -2) === '/+' || strpos($__path, '/+/') !== false ? [
    'data' => [
        'title' => $language->data,
        'content' => __DIR__ . DS . '..' . DS . 'page' . DS . 'page.2.t.data.php',
        'stack' => 10
    ]
] : [
    'page' => [
        'title' => $language->page,
        'content' => __DIR__ . DS . '..' . DS . 'page' . DS . 'page.2.t.page.php',
        'stack' => 10
    ],
    'css' => [
        'title' => 'CSS',
        'content' => __DIR__ . DS . '..' . DS . 'page' . DS . 'page.2.t.css.php',
        'stack' => 20
    ],
    'js' => [
        'title' => 'JavaScript',
        'content' => __DIR__ . DS . '..' . DS . 'page' . DS . 'page.2.t.js.php',
        'stack' => 30
    ]
]);
// Config::set('panel.t:active', 'page');

if ($__f = File::exist(__DIR__ . DS . 'page' . DS . $__sgr . '.php')) require $__f;