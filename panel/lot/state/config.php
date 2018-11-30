<?php

return [
    'path' => 'panel',
    '$' => 'page', // Default redirect target
    'style' => [
        'fonts' => [
            0 => 'Roboto Condensed', // Body
            1 => 'Roboto Condensed', // Header(s)
            2 => 'Roboto Condensed', // Alternate
            3 => 'Roboto Mono' // Code
        ],
        'width' => 1024, // Maximum editor width
    ],
    'file' => [
        'chunk' => 50,
        'kin' => 2,
        'size' => [ // TODO: Minimum and maximum file size to upload in byte(s)
            0, // 0 MB
            4e+6 // 4 MB
        ]
    ],
    'page' => [
        'chunk' => 25,
        'kin' => 2,
        'snippet' => 120,
        'sort' => [-1, 'time'],
        'image' => [
            // <https://en.wikipedia.org/wiki/Display_resolution>
            // CGA (color): 320×200
            // CGA (monochrome): 640×200
            // EGA: 640×350
            // VGA: 640×480
            // HGC: 720×348
            // XGA: 1024×768
            'width' => 640,
            'height' => 480,
            // Upload pattern relative to `ASSET`
            'directory' => '%{extension}%',
            'name' => '%{id}%.%{extension}%'
        ]
    ]
];