<?php

return [
    'route' => '/page/1',
    'guard' => [
        // The default value is `state('x.user.guard.route')` or `state('x.user.route')`
        'route' => '/panel',
        // Move deleted file(s) to the trash folder?
        'trash' => true
    ]
];