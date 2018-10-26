<?php

Config::set('panel.$.page.tools', [
    'enter' => null,
    'status' => [
        'data' => function($path) use($config, $language) {
            $id = pathinfo($path, PATHINFO_FILENAME);
            return [
                'hidden' => $id === $config->language,
                'description' => $language->activate,
                'icon' => [['M19,19H5V5H15V3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V11H19M7.91,10.08L6.5,11.5L11,16L21,6L19.59,4.58L11,13.17L7.91,10.08Z']],
                'query' => [
                    'lot' => ['language']
                ]
            ];
        },
        'title' => false,
        'c' => 'x',
        'query' => [
            'a' => 'set_config',
            'token' => $token
        ],
        'stack' => 9.9
    ]
]);

Hook::set('page.url', function($url) {
    $path = $this->path;
    return $path && strpos($path, LANGUAGE . DS) === 0 ? false : $url;
});