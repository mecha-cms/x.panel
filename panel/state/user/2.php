<?php

return [
    'bar' => [
        'folder' => [
            'asset' => [
                'url' => $url . $_['/'] . '/::g::/asset/' . $user->user . '/1' . $url->query('&', [
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
                'url' => $url . $_['/'] . '/::g::/user/' . $user->name(true) . $url->query('&', [
                    'tab' => false,
                    'type' => false
                ]) . $url->hash
            ],
            'x' => false
        ],
        'link' => function($task, $path) use($user) {
            return $user->name(true) === $path ? [
                'url' => $url . $_['/'] . '/::g::' . $_['state']['path'] . '/1' . $url->query('&', [
                    'tab' => false,
                    'type' => false
                ]) . $url->hash
            ] : true;
        },
        's' => false,
        'site' => [
            'state' => false
        ]
    ],
    'route' => [
        'asset' => false,
        'asset/*' => function($task, $path) use($_, $user) {
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
        'user/*' => function($path) use($user) {
            return $user->name(true) === $path;
        },
        'x' => false,
        'x/*' => false,
        '.state' => false,
        '.state/*' => false
    ],
    'task' => [
        'user/*' => function($path) use($_, $user) {
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
    ]
];
