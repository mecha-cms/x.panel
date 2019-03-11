<?php

// No access for pending user(s)
Config::set('panel.error', $language->message_error_feature_get);

Hook::set('start', function() {
    Config::reset(['panel.nav.lot.+', 'panel.nav.site.+.state/config']);
    Config::set('panel.nav.lot.x', true);
}, 10);