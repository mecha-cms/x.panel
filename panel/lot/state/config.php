<?php

return [
    'path' => 'panel',
    '$' => 'page', // default redirect target
    'file' => [
        'chunk' => 50,
        'kin' => 2
    ],
    'page' => [
        'chunk' => 25,
        'kin' => 2,
        'snippet' => 120,
        'sort' => [-1, 'time']
    ]
];