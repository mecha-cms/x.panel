<?php

require __DIR__ . DS . 'page.php';

// Disable page children feature
Config::reset('panel.+.page.tool.s');
Config::set('panel.error', !!$chops);

Config::set('panel.+.page.tool', [
    'enter' => null,
    's' => null,
    'r' => [
        'if' => function($file): array {
            // You can’t delete the `en-us` language
            return ['x' => pathinfo($file, PATHINFO_FILENAME) === 'en-us'];
        }
    ],
    'status' => [
        'if' => function($file) use($config, $language): array {
            return [
                'hidden' => pathinfo($file, PATHINFO_FILENAME) === $config->language,
                'description' => $language->attach,
                'icon' => [['M18,6C18,7.82 16.76,9.41 15,9.86V17A5,5 0 0,1 10,22A5,5 0 0,1 5,17V12L10,17H7A3,3 0 0,0 10,20A3,3 0 0,0 13,17V9.86C11.23,9.4 10,7.8 10,5.97C10,3.76 11.8,2 14,2C16.22,2 18,3.79 18,6M14,8A2,2 0 0,0 16,6A2,2 0 0,0 14,4A2,2 0 0,0 12,6A2,2 0 0,0 14,8Z']]
            ];
        },
        'title' => false,
        'task' => 'c528a68c',
        'stack' => 9.9
    ]
]);

Hook::set('page.url', function($url) {
    $path = $this->path;
    return $path && strpos($path, LANGUAGE . DS) === 0 ? false : $url;
});