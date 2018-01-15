<?php

// TODO

Hook::set('panel.a.' . $__chops[0] . 's', function() use($language) {
    return [];
}, 0);

$__query = HTTP::query([
    'token' => $__token,
    'r' => 1,
    $config->q => false
]);

Hook::set('panel.a.' . $__chops[0], function($__a) use($__query) {
    if (isset($__a['reset'])) {
        $__a['reset'][1] = explode('?', $__a['reset'][1], 2)[0] . $__query;
        return ['reset' => $__a['reset']];
    }
    return [];
}, 0);