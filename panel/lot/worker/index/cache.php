<?php

// TODO

Hook::set('panel.a.' . $__chops[0] . 's', function() use($language) {
    return [];
}, 0);

Hook::set('panel.a.' . $__chops[0], function($__a) {
    if (isset($__a['reset'])) {
        return ['reset' => $__a['reset']];
    }
    return [];
}, 0);