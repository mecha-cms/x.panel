<?php

return [
    'bar' => [
        0 => [
            'folder' => [
                'asset' => [
                    'url' => $_['/'] . '/::g::/asset/' . $user->user . '/1' . $url->query('&', [
                        'tab' => false,
                        'type' => false
                    ]) . $url->hash
                ],
                'block' => false,
                'cache' => false,
                'layout' => false,
                'route' => false,
                'trash' => false,
                'user' => [
                    'url' => $_['/'] . '/::g::/user/' . $user->name(true) . $url->query('&', [
                        'tab' => false,
                        'type' => false
                    ]) . $url->hash
                ],
                'x' => false
            ],
            'link' => static function($task, $path) use($_, $url, $user) {
                return 'user/' . $user->name(true) === $path ? [
                    'url' => $_['/'] . '/::g::' . $_['state']['path'] . '/1' . $url->query('&', [
                        'tab' => false,
                        'type' => false
                    ]) . $url->hash
                ] : true;
            },
            's' => false
        ],
        1 => [
            'site' => [
                'state' => false
            ]
        ]
    ],
    'route' => [
        'asset' => false,
        'asset/*' => static function($task, $path) use($_, $user) {
            if ($path === $user->user && 'g' === $task) {
                return isset($_['i']);
            }
            return 0 === strpos($path . '/', $user->user . '/');
        },
        'block' => false,
        'block/*' => false,
        'cache' => false,
        'cache/*' => false,
        'layout' => false,
        'layout/*' => false,
        'route' => false,
        'route/*' => false,
        'trash' => false,
        'trash/*' => false,
        'user' => false,
        'user/*' => static function($path) use($user) {
            return $user->name(true) === $path;
        },
        'x' => false,
        'x/*' => false,
        '.state' => false,
        '.state/*' => false
    ],
    'task' => [
        'user/*' => static function($path) use($_, $user) {
            $tasks = [
                'f' => false,
                'g' => true,
                'l' => false, // Disable delete current user (suicide)
                's' => false
            ];
            if (0 === strpos($path . '/', $user->name . '/') && 'data' === $_['type']) {
                $tasks['g'] = true; // Allow user to update data
                $tasks['l'] = true; // Allow user to delete data
                $tasks['s'] = true; // Allow user to create data
                return $tasks;
            }
            if ($user->name(true) === $path) {
                $tasks['g'] = true; // Allow user to update their account
                return $tasks;
            }
            return false;
        }
    ],
    // Prevent user(s) from modifying the `type` value from URL
    'type' => false
];