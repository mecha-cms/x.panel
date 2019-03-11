<?php

$features = ['asset', 'comment', 'page', 'user', 'tag'];

if (!has($features, $id)) {
    Config::set('panel.error', $language->message_error_feature_get);
}

Hook::set('start', function() use($features, $user) {
    if (!Config::get('panel.+.form.editor')) {
        Config::set('panel.nav.lot.+.asset.path', basename(ASSET) . '/' . $user->key . '/1');
    }
    Config::reset('panel.nav.site.+.state/config');
    $features = X . implode(X, $features) . X;
    foreach ((array) Config::get('panel.nav.lot.+', []) as $k => $v) {
        if (strpos($features, X . $k . X) === false) {
            Config::reset('panel.nav.lot.+.' . $k);
        }
    }
}, 10);