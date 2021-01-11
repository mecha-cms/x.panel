<?php

return [
    'bar' => [
        'folder' => [
            'asset' => [
                'url' => $url . $_['/'] . '/::g::/asset/' . $user->user . $url->query('&', [
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
        'link' => [
            'url' => $url . $_['/'] . '/::g::' . $_['state']['path'] . '/1' . $url->query('&', [
                'tab' => false,
                'type' => false
            ]) . $url->hash
        ],
        's' => false,
        'site' => [
            'state' => false
        ]
    ],
    'route' => [
        '.state' => false,
        '.state/*' => false,
        'asset' => false,
        'asset/*' => function($path) use($user) {
            if (0 === strpos($path . '/', $user->user . '/')) {
                return true;
            }
            return false;
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
        'x/*' => false
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
