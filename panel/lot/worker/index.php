<?php

// Force to add configuration item(s) for navigation visibility…
$__c = require STATE . DS . 'config.php';
if (!isset($__c['panel']['v']['n'])) {
    $__a = [];
    foreach (glob(LOT . DS . '*', GLOB_ONLYDIR | GLOB_NOSORT) as $__v) {
        $__a[basename($__v)] = true;
    }
    $__c['panel']['v']['n'] = $__a;
    File::export($__c)->saveTo(STATE . DS . 'config.php', 0600);
}

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
                            File::write(date(DATE_WISE))->saveTo($__f . DS . 'time.data', 0600);
                            File::write($__o)->saveTo($__f . DS . 'id.data', 0600);
                            Page::data([
                                'title' => ($__t = To::title($__v)),
                                'author' => $__author
                            ])->saveTo($__f . '.page', 0600);
                            Message::info($language->message_info_create([$language->tag, '<strong>' . $__t . '</strong>']) . ' ' . HTML::a($language->edit, Extend::state('panel', 'path') . '/::g::/tag/' . $__v, true, ['classes' => ['right']]));
                            Hook::fire('on.tag.set', [$__file, null, $__o]);
                        }
                    }
                    $__kinds = array_unique($__kinds);
                    sort($__kinds);
                    if (!Message::$x) {
                        File::write(To::json($__kinds))->saveTo(Path::F($__file) . DS . 'kind.data', 0600);
                        Hook::fire('on.tags.set', [$__file, null, $__kinds]);
                    }
                }
            } else {
                Hook::fire('on.tags.reset', [$__file, null, []]);
                File::open(Path::F($__file) . DS . 'kind.data')->delete();
            }
        }
    }
    Hook::set('on.' . $__NS . '.set', 'fn_tags_set');
}

// Add trash counter…
if ($__trash = File::exist(LOT . DS . 'trash')) {
    $__trash = File::explore($__trash, true, true);
    $__trash = array_replace([0, 0], array_count_values($__trash));
    Config::set('panel.n.trash', [
        'i' => array_sum($__trash),
        'description' => $__trash[0] . ' ' . $language->{$__trash[0] === 1 ? 'folder' : 'folders'} . ', ' . $__trash[1] . ' ' . $language->{$__trash[1] === 1 ? 'file' : 'files'}
    ]);
}
// Set proper menu name…
Config::set('panel.n.extend.text', $language->extension);
// Add shortcut to plugin manager…
Config::set('panel.n.extend.+.extend/plugin', [
    'text' => $language->plugin,
    'url' => $__state->path . '/::g::/extend/plugin/lot/worker/1',
    'stack' => 10
]);

// Delete trash…
Hook::set('on.user.exit', function() {
    File::open(LOT . DS . 'trash')->delete();
});