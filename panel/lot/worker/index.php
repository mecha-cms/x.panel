<?php

if (Extend::exist('tag')) {
    $__NS = explode('/', $url->path . '///')[2];
    if (!$__NS || $__NS === 'tag') $__NS = X;
    function fn_tags_set($__file) {
        if (!Message::$x) {
            global $language;
            // Create `kind.data` file…
            if ($__s = Request::post('tags')) {
                $__s = explode(',', $__s);
                $__kinds = [];
                $__author = Request::post('author');
                $__i = File::open(PANEL . DS . 'lot' . DS . 'extend' . DS . 'query' . DS . 'lot' . DS . 'state' . DS . 'config.php')->import(['max' => 12])['max'];
                if (count($__s) > $__i) {
                    Request::save('post');
                    Message::error('max', [$language->tags, '<strong>' . $__i . '</strong>']);
                } else {
                    foreach ($__s as $__v) {
                        $__v = To::slug($__v);
                        if (($__id = From::tag($__v)) !== false) {
                            $__kinds[] = $__id;
                        } else {
                            $__o = 0;
                            foreach (glob(TAG . DS . '*' . DS . 'id.data', GLOB_NOSORT) as $__vv) {
                                $__id = (int) file_get_contents($__vv);
                                if ($__id > $__o) $__o = $__id;
                            }
                            ++$__o;
                            $__kinds[] = $__o;
                            $__f = TAG . DS . $__v;
                            Hook::fire('on.tag.set', [$__file, $__o]);
                            File::write(date(DATE_WISE))->saveTo($__f . DS . 'time.data', 0600);
                            File::write($__o)->saveTo($__f . DS . 'id.data', 0600);
                            Page::data([
                                'title' => $__v,
                                'author' => $__author
                            ])->saveTo($__f . '.page', 0600);
                            Message::info($language->message_info_create([$language->tag, '<em>' . str_replace('-', ' ', $__v) . '</em>']) . ' ' . HTML::a($language->edit, Extend::state('panel', 'path') . '/::g::/tag/' . $__v, true, ['classes' => ['right']]));
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
    }
    Hook::set('on.' . $__NS . '.set', 'fn_tags_set');
    // Delete trash…
    Hook::set('on.user.exit', function() {
        foreach (File::explore(TAG, true, true) as $__k => $__v) {
            if ($__v === 0) continue;
            $__kk = Path::F($__k);
            foreach (g($__kk, 'trash') as $__v) {
                File::open($__v)->delete();
            }
            if (Path::X($__k) === 'trash') {
                File::open($__k)->delete();
                if (Is::D($__kk)) {
                    File::open($__kk)->delete();
                }
            }
        }
    });
}

// Delete trash…
Hook::set('on.user.exit', function() {
    foreach (File::explore(USER, true, true) as $__k => $__v) {
        if ($__v === 0) continue;
        $__kk = Path::F($__k);
        foreach (g($__kk, 'trash') as $__v) {
            File::open($__v)->delete();
        }
        if (Path::X($__k) === 'trash') {
            File::open($__k)->delete();
            if (Is::D($__kk)) {
                File::open($__kk)->delete();
            }
        }
    }
});