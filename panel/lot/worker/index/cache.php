<?php

Hook::set('panel.a.' . $__chops[0] . 's', function() use($language) {
    return [
        'reset' => [$language->delete, '#']
    ];
}, 0);

Hook::set('panel.a.' . $__chops[0], function() {
    return [];
}, 0);