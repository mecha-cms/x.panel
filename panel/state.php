<?php

return [
    'chunk' => 20,
    'sort' => [-1, 'time'],
    'path' => '/page',
    // Enable AJAX feature?
    'fetch' => true,
    'guard' => [
        'path' => '/panel',
        // Minimum and maximum file size allowed to upload (0 – 120 MB)
        'size' => [0, 125829120],
        // Move deleted file(s) to the trash folder?
        'trash' => true
    ]
];
