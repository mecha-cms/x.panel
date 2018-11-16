<?php

$extends = [];
foreach (glob(EXTEND . DS . '*' . DS . 'about.page', GLOB_NOSORT) as $v) {
    $extends[File::open($v)->get(1)] = $v;
}

ksort($extends); // Sort by `title`

Config::set('panel.desk.body.tab.file.explore', array_values($extends));
Config::set('panel.desk.header.tool', [
    'file' => [
        'title' => $language->add,
        'query' => [
            'tab' => ['blob'],
            'view' => 'file'
        ]
    ],
    'folder' => null,
    '+' => ['+' => null]
]);

Config::set('panel.+.page.tool', [
    'enter' => null,
    's' => null,
    'status' => [
        'if' => function($file) use($language): array {
            $active = file_exists(($dir = dirname($file)) . DS . 'index.php');
            return [
                'x' => has(['asset', 'page', 'plugin', 'shield', 'user'], basename($dir)),
                'description' => $language->{$active ? 'eject' : 'attach'},
                'icon' => [[$active ? 'M13,9.86V11.18L15,13.18V9.86C17.14,9.31 18.43,7.13 17.87,5C17.32,2.85 15.14,1.56 13,2.11C10.86,2.67 9.57,4.85 10.13,7C10.5,8.4 11.59,9.5 13,9.86M14,4A2,2 0 0,1 16,6A2,2 0 0,1 14,8A2,2 0 0,1 12,6A2,2 0 0,1 14,4M18.73,22L14.86,18.13C14.21,20.81 11.5,22.46 8.83,21.82C6.6,21.28 5,19.29 5,17V12L10,17H7A3,3 0 0,0 10,20A3,3 0 0,0 13,17V16.27L2,5.27L3.28,4L13,13.72L15,15.72L20,20.72L18.73,22Z' : 'M18,6C18,7.82 16.76,9.41 15,9.86V17A5,5 0 0,1 10,22A5,5 0 0,1 5,17V12L10,17H7A3,3 0 0,0 10,20A3,3 0 0,0 13,17V9.86C11.23,9.4 10,7.8 10,5.97C10,3.76 11.8,2 14,2C16.22,2 18,3.79 18,6M14,8A2,2 0 0,0 16,6A2,2 0 0,0 14,4A2,2 0 0,0 12,6A2,2 0 0,0 14,8Z']],
                'path' => Path::R($dir, LOT, '/') . '/index.' . ($active ? 'php' : 'x'),
                'task' => [1 => ['index.' . ($active ? 'x' : 'php')]]
            ];
        },
        'title' => false,
        'task' => ['2eca1f34', ['index.x']],
        'stack' => 9.9
    ],
    'g' => [
        'if' => function($file): array {
            return ['path' => Path::R(dirname($file), LOT, '/') . '/1'];
        }
    ],
    'r' => [
        'if' => function($file): array {
            return [
                'x' => has(['asset', 'page', 'plugin', 'shield', 'user'], basename($dir = dirname($file))),
                'path' => Path::R($dir, LOT, '/')
            ];
        }
    ]
]);

Hook::set('page.url', function($url) {
    $path = $this->path;
    return $path && strpos($path, EXTEND . DS) === 0 ? false : $url;
});