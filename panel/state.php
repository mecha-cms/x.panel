<?php

return [
    'fetch' => false, // Enable AJAX feature?
    'route' => '/page/1',
    'sync' => [
        'version' => true // Enable version update check
    ],
    'guard' => [
        // The default value is `state('x.user.guard.route')` or `state('x.user.route')`
        'route' => '/panel',
        // Move deleted file(s) to the trash folder?
        'trash' => true
    ]
];