<?php

require __DIR__ . DS . 'page.php';

// Disable page children
Config::reset('panel.$.page.tools.s');

// Add exit button for super admin
Config::set('panel.$.page.tools', [
    'r' => [
        'data' => function($file) use($user) {
            return [
                'x' => '@' . Path::N($file) === $user->key
            ];
        }
    ],
    'exit' => [
        'data' => function($file) use($user) {
            return [
                'x' => '@' . Path::N($file) === $user->key,
                'hidden' => !file_exists(Path::F($file) . DS . 'token.data'),
                'description' => 'Force log out @' . Path::N($file),
                'task' => '950abfd9'
            ];
        },
        'title' => false,
        'icon' => [['M19,21V19H15V17H19V15L22,18L19,21M10,4A4,4 0 0,1 14,8A4,4 0 0,1 10,12A4,4 0 0,1 6,8A4,4 0 0,1 10,4M10,14C11.15,14 12.25,14.12 13.24,14.34C12.46,15.35 12,16.62 12,18C12,18.7 12.12,19.37 12.34,20H2V18C2,15.79 5.58,14 10,14Z']],
        'stack' => 20
    ]
]);

Hook::set('page.url', function($url) {
    $path = $this->path;
    return $path && strpos($path, USER . DS) === 0 ? $GLOBALS['URL']['$'] . '/' . Extend::state('user', 'path') . '/' . $this->slug : $url;
});

Hook::set('page.title', function($title) {
    $path = $this->path;
    return $path && strpos($path, USER . DS) === 0 ? $this->{'$'} : $title;
});

Hook::set('page.description', function($description) {
    $path = $this->path;
    return $path && strpos($path, USER . DS) === 0 ? '@' . $this->slug : $description;
});