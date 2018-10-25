<?php

$extends = [];
foreach (glob(EXTEND . DS . '*' . DS . 'about.page', GLOB_NOSORT) as $v) {
    $extends[File::open($v)->get(1)] = $v;
}

ksort($extends); // Sort by `title`

Config::set('panel.desk.body.tabs.file.files', $extends);
Config::set('panel.$.page.tools', [
    'enter' => null,
    'view' => [
        'data' => function($k, $path) use($language) {
            $active = file_exists(dirname($path) . DS . 'index.php');
            return [
                'description' => $language->{($active ? '_' : "") . 'activate'},
                'icon' => [[$active ? 'M19,19V5H5V19H19M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5A2,2 0 0,1 3,19V5C3,3.89 3.9,3 5,3H19M17,11V13H7V11H17Z' : 'M19,19V5H5V19H19M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5A2,2 0 0,1 3,19V5C3,3.89 3.9,3 5,3H19M11,7H13V11H17V13H13V17H11V13H7V11H11V7Z']],
                'path' => str_replace(LOT . DS, "", dirname($path)) . DS . 'index.' . ($active ? 'php' : 'x'),
                'query' => [
                    'lot' => ['index.' . ($active ? 'x' : 'php')]
                ]
            ];
        },
        'title' => false,
        'c' => 'x',
        'query' => [
            'a' => 'rename_extend',
            'token' => $token
        ],
        'stack' => 9.9
    ],
    'g' => ['path' => function($v, $k, $path) {
        return str_replace(LOT . DS, "", dirname($path)) . DS . '1';
    }],
    'r' => ['path' => function($v, $k, $path) {
        return str_replace(LOT . DS, "", dirname($path));
    }]
]);

Hook::set('page.url', function($url) {
    $path = $this->path;
    return $path && strpos($path, EXTEND . DS) === 0 ? false : $url;
});