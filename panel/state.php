<?php

return [
    'path' => '/page',
    // Enable AJAX feature?
    'fetch' => true,
    'guard' => [
        'path' => '/panel',
        // Minimum and maximum file size allowed to upload (0 – 120 MB)
        'size' => [0, 125829120],
        'sync' => [
            'version' => true // Enable version update check
        ],
        // Move deleted file(s) to the trash folder?
        'trash' => true
    ],
    'skin' => 'default'
];