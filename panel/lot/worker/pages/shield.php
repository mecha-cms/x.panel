<?php

$shields = [];
foreach (glob(SHIELD . DS . '*' . DS . 'about.page', GLOB_NOSORT) as $v) {
    $shields[File::open($v)->get(1)] = $v;
}

ksort($shields); // Sort by `title`

Config::reset('panel.desk.footer.pager'); // Hide pager
Config::set('panel.nav.search.hidden', true); // Hide search box
Config::set('panel.desk.body.tab.file.explore', array_values($shields));
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
    'state' => [
        'if' => function($file) use($language): array {
            return [
                'hidden' => !file_exists($f = dirname($file) . DS . 'state' . DS . 'config.php'),
                'path' => Path::R($f, LOT, '/')
            ];
        },
        'description' => $language->config,
        'icon' => [['M3,17V19H9V17H3M3,5V7H13V5H3M13,21V19H21V17H13V15H11V21H13M7,9V11H3V13H7V15H9V9H7M21,13V11H11V13H21M15,9H17V7H21V5H17V3H15V9Z']],
        'stack' => 9.8
    ],
    'status' => null, // TODO
    'g' => [
        'if' => function($file): array {
            return ['path' => Path::R(dirname($file), LOT, '/') . '/1'];
        }
    ],
    'r' => [
        'if' => function($file) use($config): array {
            $dir = dirname($file);
            return [
                'x' => $config->shield === basename($dir),
                'path' => Path::R($dir, LOT, '/')
            ];
        }
    ]
]);

Hook::set('page.image', function($image) {
    $path = $this->path;
    if (strpos($path, SHIELD . DS) !== 0) {
        return $image;
    }
    $dir = dirname($path);
    $x = '{gif,jpeg,jpg,png}';
    if ($f = glob($dir . DS . 'asset' . DS . $x . DS . basename($dir) . '.' . $x, GLOB_BRACE | GLOB_NOSORT)) {
        return is_file($f[0]) ? To::URL($f[0]) . '?v=' . filemtime($f[0]) : $image;
    }
    return $image;
});

Hook::set('page.url', function($url) {
    $path = $this->path;
    return $path && strpos($path, SHIELD . DS) === 0 ? false : $url;
});