<?php

return [
    'fetch' => false, // Enable AJAX feature?
    'route' => '/page/1',
    'sync' => [
        'version' => true // Enable version update check
    ],
    'guard' => [
        // Move deleted file(s) to the trash folder?
        'trash' => true
    ]
];