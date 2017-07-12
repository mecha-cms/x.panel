<?php

Hook::set('on.panel.ready', function() use($language) {
    foreach ((array) $language->o_type as $k => $v) {
        Config::set('panel.o.page.type.' . $k, $v);
    }
    foreach ((array) $language->o_editor as $k => $v) {
        Config::set('panel.o.page.editor.' . $k, $v);
    }
}, 20);

if (Extend::exist('tag')) {
    $__NS = explode('/', $url->path . '///')[2];
    if (!$__NS || $__NS === 'tag') $__NS = X;
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
                            Message::info($language->message_info_create([$language->tag, '<em>' . str_replace('-', ' ', $v) . '</em>']) . ' ' . HTML::a($language->edit, Extend::state('panel', 'path') . '/::g::/tag/' . $v, true, ['classes' => ['right']]));
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
    Hook::set('on.' . $__NS . '.set', 'fn_tags_set');
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