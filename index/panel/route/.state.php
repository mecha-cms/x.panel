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

lot('file', $file = new File(is_file($v = PATH . D . 'state.php') ? $v : null));

$_['status'] = 200;
if (!array_key_exists('type', $_GET) && !isset($_['type'])) {
    $_['type'] = 'state';
}

if (false !== strpos($_['path'] ?? "", '/')) {
    $_['status'] = 404;
    $_['type'] = 'void';
    return $_;
}

// Load primary state(s)
$state_r = require x\panel\_cache_let($file->path);
$state_user = require x\panel\_cache_let(LOT . D . 'x' . D . 'user' . D . 'state.php');
$state_panel = require x\panel\_cache_let(LOT . D . 'x' . D . 'panel' . D . 'state.php');

$layouts = [];
$layouts_current = null;

foreach (glob(LOT . D . 'y' . D . '*', GLOB_NOSORT | GLOB_ONLYDIR) as $layout) {
    if (!is_file($layout . D . '.index.php') && !is_file($layout . D . 'index.php')) {
        continue;
    }
    $n = basename($layout);
    if (false !== strpos('._', $n[0])) {
        continue;
    }
    $about = new Page($f = exist($layout . D . 'index.{' . x\page\x() . '}', 1) ?: null);
    $layouts[$n] = $about->title ?? x\panel\from\path($layout);
    if (null === $layouts_current) {
        $layouts_current = is_file($layout . D . 'index.php') ? $n : null;
    }
}

// Sanitize the form data
if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['state'])) {
    // Update current layout
    if (isset($_POST['y']) && is_string($y = $_POST['y']) && $_['token'] === ($_POST['token'] ?? 0)) {
        $folder_y = LOT . D . 'y' . D . $y;
        if ($layouts_current !== $y && (is_file($folder_y . D . '.index.php') || is_file($folder_y . D . 'index.php'))) {
            foreach (glob(dirname($folder_y) . D . '*', GLOB_NOSORT | GLOB_ONLYDIR) as $layout) {
                if (is_file($f = $layout . D . 'index.php')) {
                    rename($f, $layout . D . '.index.php');
                }
            }
            if (rename($f = $folder_y . D . '.index.php', $folder_y . D . 'index.php')) {
                $_['alert']['success'][$f] = ['%s %s successfully attached.', ['Layout', '<code>' . $y . '</code>']];
            } else {
                $_['alert']['info'][$f] = ['%s %s could not be attached.', ['Layout', '<code>' . $y . '</code>']];
            }
        }
    }
    $_POST['state']['description'] = x\panel\to\w($_POST['state']['description'] ?? "");
    $_POST['state']['email'] = x\panel\to\w($_POST['state']['email'] ?? "");
    $_POST['state']['title'] = x\panel\to\w($_POST['state']['title'] ?? "");
    $sub_panel = $state_r['x']['panel']['sub'] ?? $state_panel['sub'] ?? 'panel';
    $sub_user = $state_r['x']['user']['guard']['sub'] ?? $state_user['guard']['sub'] ?? $state_r['x']['user']['sub'] ?? $state_user['sub'] ?? 'user';
    $sub_panel = '/' . trim($sub_panel, '/');
    $sub_user = '/' . trim($sub_user, '/');
    if (!empty($_POST['state']['x']['panel']['sub'])) {
        if ($v = To::kebab(trim($_POST['state']['x']['panel']['sub'], '/'))) {
            $_POST['state']['x']['panel']['sub'] = $sub_panel = '/' . $v;
        } else {
            unset($_POST['state']['x']['panel']['sub']);
        }
    } else {
        $sub_panel = $state_panel['sub'] ?? '/panel';
        $sub_panel_reset = true;
    }
    if ($sub_panel === ($state_r['x']['panel']['sub'] ?? $state_panel['sub'])) {
        if (!empty($sub_panel_reset) && !empty($state_r['x']['panel']['sub'])) {
            $_['alert']['info'][] = ['Your panel base link has been restored to %s', '<code>' . $link . $state_panel['sub'] . '</code>'];
        }
    } else if (empty($sub_panel_reset)) {
        $_['alert']['info'][] = ['Your panel base link has been changed to %s', '<code>' . $link . $sub_panel . '</code>'];
    } else {
        $_['alert']['info'][] = ['Your panel base link has been restored to %s', '<code>' . $link . $state_panel['sub'] . '</code>'];
    }
    if (!empty($_POST['state']['x']['user']['guard']['sub'])) {
        if ($v = To::kebab(trim($_POST['state']['x']['user']['guard']['sub'], '/'))) {
            $_POST['state']['x']['user']['guard']['sub'] = $sub_user = '/' . $v;
        } else {
            unset($_POST['state']['x']['user']['guard']['sub']);
        }
    } else {
        $sub_user = $state_user['guard']['sub'] ?? $state_user['sub'] ?? '/user';
        $sub_user_reset = true;
    }
    if ($sub_user === ($state_r['x']['user']['guard']['sub'] ?? $state_user['guard']['sub'] ?? $state_r['x']['user']['sub'] ?? $state_user['sub'])) {
        if (!empty($sub_user_reset) && !empty($state_r['x']['user']['guard']['sub'])) {
            $_['alert']['info'][] = ['Your user log-in link has been restored to %s', '<code>' . $link . $state_user['sub'] . '</code>'];
        }
    } else if (empty($sub_user_reset)) {
        $_['alert']['info'][] = ['Your user log-in link has been changed to %s', '<code>' . $link . $sub_user . '</code>'];
    } else {
        $_['alert']['info'][] = ['Your user log-in link has been restored to %s', '<code>' . $link . $state_user['sub'] . '</code>'];
    }
    x\panel\_cache_let($file->path);
    x\panel\_cache_let(LOT . D . 'x' . D . 'user' . D . 'state.php');
    x\panel\_cache_let(LOT . D . 'x' . D . 'panel' . D . 'state.php');
    $_POST['kick'] = [
        'base' => $link->base($sub_panel),
        'hash' => $_POST['hash'] ?? null,
        'part' => 0,
        'path' => '.state',
        'query' => x\panel\_query_set($_POST['query'] ?? []),
        'task' => 'get'
    ];
}

$panels = $routes = [];

foreach (glob(LOT . D . '*', GLOB_NOSORT | GLOB_ONLYDIR) as $panel) {
    $n = basename($panel);
    if (false !== strpos('._', $n[0])) {
        continue;
    }
    $panels['get/' . rawurlencode($n) . '/1'] = 'x' === $n ? 'Extension' : ('y' === $n ? 'Layout' : To::title($n));
}

$routes['item'] = [
    'lot' => [],
    'title' => 'Item'
];

$routes['items'] = [
    'lot' => [],
    'title' => 'Items'
];

foreach (glob(LOT . D . 'page' . D . '*.{' . x\page\x() . '}', GLOB_BRACE | GLOB_NOSORT) as $path) {
    $n = pathinfo($path, PATHINFO_FILENAME);
    $routes['item']['lot'][$k = '/' . (0 === strpos($n, '#') ? substr($n, 1) : $n)] = $v = S . (new Page($path))->title . S;
    if (glob(dirname($path) . $k . D . '*.{' . x\page\x() . '}', GLOB_BRACE | GLOB_NOSORT)) {
        $routes['items']['lot'][$k . '/1'] = $v;
    }
}

asort($layouts);
asort($panels);
asort($routes);

$zones = (static function () {
    $zones = [];
    $regions = [
        DateTimeZone::AFRICA,
        DateTimeZone::AMERICA,
        DateTimeZone::ANTARCTICA,
        DateTimeZone::ASIA,
        DateTimeZone::ATLANTIC,
        DateTimeZone::AUSTRALIA,
        DateTimeZone::EUROPE,
        DateTimeZone::INDIAN,
        DateTimeZone::PACIFIC
    ];
    $time_zones = [];
    $time_zone_offsets = [];
    foreach ($regions as $region) {
        $time_zones = array_merge($time_zones, DateTimeZone::listIdentifiers($region));
    }
    foreach ($time_zones as $time_zone) {
        $tz = new DateTimeZone($time_zone);
        $time_zone_offsets[$time_zone] = $tz->getOffset(new DateTime);
    }
    asort($time_zone_offsets);
    foreach ($time_zone_offsets as $zone => $offset) {
        $offset_prefix = $offset < 0 ? '-' : '+';
        $offset_formatted = gmdate('H:i', abs($offset));
        $zones[$zone] = strtr($zone, '_', ' ') . ' ' . $offset_prefix . $offset_formatted;
    }
    return $zones;
})();

return array_replace_recursive($_, [
    'lot' => [
        'bar' => [
            // `bar`
            'lot' => [
                0 => [
                    // `links`
                    'lot' => [
                        'search' => ['skip' => true], // Hide search form
                        'set' => ['skip' => true]
                    ]
                ]
            ]
        ],
        'desk' => [
            // `desk`
            'lot' => [
                'form' => [
                    // `form/post`
                    'lot' => [
                        1 => [
                            // `section`
                            'lot' => [
                                'tabs' => [
                                    // `tabs`
                                    'lot' => [
                                        'file' => [
                                            // `tab`
                                            'lot' => [
                                                'fields' => [
                                                    // `fields`
                                                    'lot' => [
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
                                                            'name' => 'state[title]',
                                                            'stack' => 20,
                                                            'type' => 'title',
                                                            'value' => $state_r['title'] ?? null,
                                                            'width' => true
                                                        ],
                                                        'description' => [
                                                            'name' => 'state[description]',
                                                            'stack' => 30,
                                                            'type' => 'description',
                                                            'value' => $state_r['description'] ?? null,
                                                            'width' => true
                                                        ],
                                                        'layout' => [
                                                            'lot' => $layouts,
                                                            'name' => 'y',
                                                            'stack' => 40,
                                                            'type' => 'option',
                                                            'value' => $layouts_current
                                                        ],
                                                        'route' => [
                                                            'description' => 'Choose default page that will open in the home page.',
                                                            'lot' => $routes,
                                                            'name' => 'state[home]',
                                                            'stack' => 50,
                                                            'title' => 'Home',
                                                            'type' => 'option',
                                                            'value' => $state_r['home'] ?? null,
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
                                                        'sub' => [
                                                            'description' => 'Set custom panel base path.',
                                                            'hint' => $state_panel['sub'] ?? null,
                                                            'name' => 'state[x][panel][sub]',
                                                            'stack' => 10,
                                                            'type' => 'route',
                                                            'value' => $state_r['x']['panel']['sub'] ?? null
                                                        ],
                                                        'user' => [
                                                            'description' => 'Set custom user log-in path.',
                                                            'hint' => $state_user['guard']['sub'] ?? $state_user['sub'] ?? null,
                                                            'name' => 'state[x][user][guard][sub]',
                                                            'stack' => 20,
                                                            'type' => 'route',
                                                            'value' => $state_r['x']['user']['guard']['sub'] ?? $state_user['guard']['sub'] ?? null
                                                        ],
                                                        'kick' => [
                                                            'description' => 'Choose default page that will open after logged-in.',
                                                            'lot' => $panels,
                                                            'name' => 'state[x][panel][kick]',
                                                            'stack' => 30,
                                                            'title' => 'Home',
                                                            'type' => 'option',
                                                            'value' => $state_r['x']['panel']['kick'] ?? $state_panel['kick'] ?? null
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
                                                        'sync' => [
                                                            'description' => is_file($versions = ENGINE . D . 'log' . D . 'git' . D . 'versions' . D . 'mecha-cms.php') ? ['Last synced %s.', x\panel\to\ago(filemtime($versions))] : null,
                                                            'flex' => false,
                                                            'lot' => [
                                                                0 => 'Never',
                                                                2592000 => ['Every %s', 'month'], // `strtotime('+1 month') - time()`
                                                                3600 => ['Every %s', 'hour'], // `strtotime('+1 hour') - time()`
                                                                604800 => ['Every %s', 'week'], // `strtotime('+1 week') - time()`
                                                                86400 => ['Every %s', 'day'] // `strtotime('+1 day') - time()`
                                                            ],
                                                            'name' => 'state[x][panel][sync]',
                                                            'stack' => 10,
                                                            'type' => 'item',
                                                            'value' => $state_r['x']['panel']['sync'] ?? $state_panel['sync'] ?? null
                                                        ],
                                                        'email' => [
                                                            'description' => 'This email address will be used to receive certain messages to your inbox as the fastest solution for notifications. At this time you may not use it to receive any messages, but some extensions that require an email address may depend on this value.',
                                                            'name' => 'state[email]',
                                                            'stack' => 20,
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
        ]
    ]
]);