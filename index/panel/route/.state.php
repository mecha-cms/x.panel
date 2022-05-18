<?php

// Disable page offset feature
if (!empty($_['part']) || 'get' !== $_['task']) {
    $_['kick'] = [
        'part' => 0,
        'path' => '.state',
        'task' => 'get'
    ];
    return $_;
}

if (!array_key_exists('type', $_GET) && !isset($_['type'])) {
    $_['type'] = 'state';
}

$_['status'] = 200;

// Load primary state(s)
$state_r = require x\panel\_cache_let($file = PATH . D . 'state.php');
$state_user = require x\panel\_cache_let(LOT . D . 'x' . D . 'user' . D . 'state.php');
$state_panel = require x\panel\_cache_let(LOT . D . 'x' . D . 'panel' . D . 'state.php');

// Sanitize the form data
if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['state'])) {
    $_POST['state']['description'] = x\panel\to\w($_POST['state']['description'] ?? "");
    $_POST['state']['email'] = x\panel\to\w($_POST['state']['email'] ?? "");
    $_POST['state']['title'] = x\panel\to\w($_POST['state']['title'] ?? "");
    $route = $state_r['x']['user']['guard']['route'] ?? $state_user['guard']['route'] ?? $state_r['x']['user']['route'] ?? $state_user['route'] ?? "";
    $route = '/' . trim($route, '/');
    if (!empty($_POST['state']['x']['user']['guard']['route'])) {
        if ($v = To::kebab(trim($_POST['state']['x']['user']['guard']['route'], '/'))) {
            $_POST['state']['x']['user']['guard']['route'] = $route = '/' . $v;
        } else {
            unset($_POST['state']['x']['user']['guard']['route']);
        }
    } else {
        $reset = true;
    }
    if ($route === ($state_r['x']['user']['guard']['route'] ?? $state_user['guard']['route'] ?? $state_r['x']['user']['route'] ?? $state_user['route'])) {
        if (!empty($reset) && (!empty($state_r['x']['user']['guard']['route']) || !empty($state_r['x']['user']['route']))) {
            $_['alert']['info'][$file] = ['Your log-in URL has been restored to %s', '<code>' . $url . $state_user['route'] . '</code>'];
        }
    } else {
        $_['alert']['info'][$file] = ['Your log-in URL has been changed to %s', '<code>' . $url . $route . '</code>'];
    }
    x\panel\_cache_let(PATH . D . 'state.php');
    x\panel\_cache_let(LOT . D . 'x' . D . 'user' . D . 'state.php');
    x\panel\_cache_let(LOT . D . 'x' . D . 'panel' . D . 'state.php');
    $_POST['kick'] = [
        'hash' => $_POST['hash'] ?? null,
        'part' => 0,
        'path' => '.state',
        'query' => x\panel\_query_set($_POST['query'] ?? []),
        'task' => 'get'
    ];
}

if (false === strpos($_['path'], '/')) {
    $panes = $routes = [];
    foreach (glob(LOT . D . '*', GLOB_NOSORT | GLOB_ONLYDIR) as $panel) {
        $n = basename($panel);
        if (false !== strpos('_.-', $n[0])) {
            continue;
        }
        $panes['/' . $n . '/1'] = 'x' === $n ? 'Extension' : ('y' === $n ? 'Layout' : To::title($n));
    }
    foreach (glob(LOT . D . 'page' . D . '*.{archive,page}', GLOB_NOSORT | GLOB_BRACE) as $path) {
        $routes['/' . pathinfo($path, PATHINFO_FILENAME)] = S . (new Page($path))->title . S;
    }
    asort($panes);
    asort($routes);
    $zones = (static function() {
        $zones = [];
        $regions = [
            \DateTimeZone::AFRICA,
            \DateTimeZone::AMERICA,
            \DateTimeZone::ANTARCTICA,
            \DateTimeZone::ASIA,
            \DateTimeZone::ATLANTIC,
            \DateTimeZone::AUSTRALIA,
            \DateTimeZone::EUROPE,
            \DateTimeZone::INDIAN,
            \DateTimeZone::PACIFIC,
        ];
        $timezones = [];
        $timezone_offsets = [];
        foreach ($regions as $region) {
            $timezones = \array_merge($timezones, \DateTimeZone::listIdentifiers($region));
        }
        foreach ($timezones as $timezone) {
            $tz = new \DateTimeZone($timezone);
            $timezone_offsets[$timezone] = $tz->getOffset(new \DateTime);
        }
        asort($timezone_offsets);
        foreach ($timezone_offsets as $zone => $offset) {
            $offset_prefix = $offset < 0 ? '-' : '+';
            $offset_formatted = gmdate('H:i', \abs($offset));
            $zones[$zone] = strtr($zone, '_', ' ') . ' ' . $offset_prefix . $offset_formatted;
        }
        return $zones;
    })();
    $bar = [
        'lot' => [
            0 => [
                'lot' => [
                    'search' => ['skip' => true], // Hide search form
                    'set' => ['skip' => true]
                ]
            ]
        ]
    ];
    $desk = [
        'lot' => [
            'form' => [
                'lot' => [
                    1 => [
                        'lot' => [
                            'tabs' => [
                                'lot' => [
                                    'file' => [
                                        'lot' => [
                                            'fields' => [
                                                // `fields`
                                                'lot' => [
                                                    'content' => ['skip' => true],
                                                    'name' => ['skip' => true],
                                                    'zone' => [
                                                        'lot' => $zones,
                                                        'name' => 'state[zone]',
                                                        'stack' => 10,
                                                        'type' => 'option',
                                                        'value' => $state_r['zone'] ?? null,
                                                        'width' => true
                                                    ],
                                                    'title' => [
                                                        'focus' => true,
                                                        'hint' => ($v = $state_r['title'] ?? null) ?? 'Title Goes Here',
                                                        'name' => 'state[title]',
                                                        'stack' => 20,
                                                        'type' => 'title',
                                                        'value' => $v,
                                                        'width' => true
                                                    ],
                                                    'description' => [
                                                        'hint' => 'Description goes here...',
                                                        'name' => 'state[description]',
                                                        'stack' => 30,
                                                        'type' => 'description',
                                                        'value' => $state_r['description'] ?? null,
                                                        'width' => true
                                                    ],
                                                    'route' => [
                                                        'description' => 'Choose default page that will open in the home page.',
                                                        'lot' => $routes,
                                                        'name' => 'state[route]',
                                                        'stack' => 40,
                                                        'title' => 'Home',
                                                        'type' => 'option',
                                                        'value' => $state_r['route'] ?? null,
                                                    ]
                                                ]
                                            ]
                                        ],
                                        'value' => 'site'
                                    ],
                                    'panel' => [
                                        'lot' => [
                                            'fields' => [
                                                'lot' => [
                                                    'route' => [
                                                        'description' => 'Choose default page that will open after logged-in.',
                                                        'lot' => $panes,
                                                        'name' => 'state[x][panel][route]',
                                                        'stack' => 10,
                                                        'title' => 'Home',
                                                        'type' => 'option',
                                                        'value' => $state_r['x']['panel']['route'] ?? $state_panel['route'] ?? null
                                                    ],
                                                    'key' => [
                                                        'description' => 'Set custom log-in path.',
                                                        'hint' => $state_user['guard']['route'] ?? $state_user['route'] ?? null,
                                                        'name' => 'state[x][user][guard][route]',
                                                        'pattern' => "^/([a-z\\d]+)(-[a-z\\d]+)*$",
                                                        'stack' => 20,
                                                        'type' => 'text',
                                                        'value' => $state_r['x']['user']['guard']['route'] ?? $state_user['guard']['route'] ?? null
                                                    ]
                                                ],
                                                'stack' => 10,
                                                'type' => 'fields'
                                            ]
                                        ],
                                        'stack' => 20
                                    ],
                                    'alert' => [
                                        'lot' => [
                                            'fields' => [
                                                'lot' => [
                                                    'email' => [
                                                        'description' => 'This email address will be used to receive certain messages to your inbox as the fastest solution for notifications. At this time you may not use it to receive any messages, but some extensions that require an email address may depend on this value.',
                                                        'name' => 'state[email]',
                                                        'stack' => 10,
                                                        'type' => 'email',
                                                        'value' => $state_r['email'] ?? null
                                                    ]
                                                ],
                                                'stack' => 10,
                                                'type' => 'fields'
                                            ]
                                        ],
                                        'stack' => 40,
                                        'title' => 'Notifications'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    2 => [
                        'lot' => [
                            'fields' => [
                                'lot' => [
                                    0 => [
                                        'lot' => [
                                            'tasks' => [
                                                'lot' => [
                                                    'let' => ['skip' => true], // Hide delete button
                                                    'set' => ['description' => ['Save to %s', ".\\state.php"]]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'values' => [
                    // Store the file to `.\state.php`
                    'file' => ['name' => 'state.php'],
                    'path' => '..' // Parent folder
                ]
            ]
        ]
    ];
    Hook::set('_', function($_) use($bar, $desk) {
        $_['lot'] = array_replace_recursive($_['lot'] ?? [], [
            'bar' => $bar,
            'desk' => $desk
        ]);
        return $_;
    }, 0);
}

return $_;