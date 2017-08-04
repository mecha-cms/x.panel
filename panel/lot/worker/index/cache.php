<?php

Hook::set('panel.a.' . $__chops[0] . 's', function() use($language) {
    return [
        'reset' => ['&#x2716; ' . $language->delete, '#']
    ];
});

Hook::set('panel.a.' . $__chops[0], function() {
    return [];
});