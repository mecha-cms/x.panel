<?php

$extends = [];
foreach (glob(EXTEND . DS . '*' . DS . 'about.page', GLOB_NOSORT) as $v) {
    $extends[File::open($v)->get(1)] = $v;
}

ksort($extends); // Sort by `title`

Config::set('panel.desk.body.tabs.file.files', array_values($extends));
Config::set('panel.desk.header.tools', [
    'file' => [
        'title' => $language->add,
        'query' => ['tab' => ['blob']]
    ],
    'folder' => null,
    '+' => ['menus' => null]
]);

Config::set('panel.$.page.tools', [
    'enter' => null,
    'status' => [
        'data' => function($path) use($language) {
            $active = file_exists(dirname($path) . DS . 'index.php');
            return [
                'description' => $language->{($active ? '_' : "") . 'activate'},
                'icon' => [[$active ? 'M19,19H5V5H15V3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V11H19M7.91,10.08L6.5,11.5L11,16L21,6L19.59,4.58L11,13.17L7.91,10.08Z' : 'M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V19H5V5H19Z']],
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
    'g' => ['path' => function($path) {
        return str_replace(LOT . DS, "", dirname($path)) . DS . '1';
    }],
    'r' => ['path' => function($path) {
        return str_replace(LOT . DS, "", dirname($path));
    }]
]);

Hook::set('page.url', function($url) {
    $path = $this->path;
    return $path && strpos($path, EXTEND . DS) === 0 ? false : $url;
});