<?php

return [
    'path' => '/page',
    'fetch' => true, // Enable AJAX feature?
    'sync' => [
        'version' => true // Enable version update check
    ],
    'guard' => [
        'path' => '/panel',
        // Minimum and maximum file size allowed to upload (0 – 120 MB)
        'size' => [0, 125829120],
        // Move deleted file(s) to the trash folder?
        'trash' => true
    ],
    'skin' => 'default'
];