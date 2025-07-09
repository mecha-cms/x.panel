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
    $about = new Page(is_file($f = $layout . D . 'about.page') ? $f : null);
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
    $route_panel = $state_r['x']['panel']['route'] ?? $state_panel['route'] ?? 'panel';
    $route_user = $state_r['x']['user']['guard']['route'] ?? $state_user['guard']['route'] ?? $state_r['x']['user']['route'] ?? $state_user['route'] ?? 'user';
    $route_panel = '/' . trim($route_panel, '/');
    $route_user = '/' . trim($route_user, '/');
    if (!empty($_POST['state']['x']['panel']['route'])) {
        if ($v = To::kebab(trim($_POST['state']['x']['panel']['route'], '/'))) {
            $_POST['state']['x']['panel']['route'] = $route_panel = '/' . $v;
        } else {
            unset($_POST['state']['x']['panel']['route']);
        }
    } else {
        $route_panel = $state_panel['route'] ?? '/panel';
        $route_panel_reset = true;
    }
    if ($route_panel === ($state_r['x']['panel']['route'] ?? $state_panel['route'])) {
        if (!empty($route_panel_reset) && !empty($state_r['x']['panel']['route'])) {
            $_['alert']['info'][] = ['Your panel base URL has been restored to %s', '<code>' . $url . $state_panel['route'] . '</code>'];
        }
    } else if (empty($route_panel_reset)) {
        $_['alert']['info'][] = ['Your panel base URL has been changed to %s', '<code>' . $url . $route_panel . '</code>'];
    } else {
        $_['alert']['info'][] = ['Your panel base URL has been restored to %s', '<code>' . $url . $state_panel['route'] . '</code>'];
    }
    if (!empty($_POST['state']['x']['user']['guard']['route'])) {
        if ($v = To::kebab(trim($_POST['state']['x']['user']['guard']['route'], '/'))) {
            $_POST['state']['x']['user']['guard']['route'] = $route_user = '/' . $v;
        } else {
            unset($_POST['state']['x']['user']['guard']['route']);
        }
    } else {
        $route_user = $state_user['guard']['route'] ?? $state_user['route'] ?? '/user';
        $route_user_reset = true;
    }
    if ($route_user === ($state_r['x']['user']['guard']['route'] ?? $state_user['guard']['route'] ?? $state_r['x']['user']['route'] ?? $state_user['route'])) {
        if (!empty($route_user_reset) && !empty($state_r['x']['user']['guard']['route'])) {
            $_['alert']['info'][] = ['Your user log-in URL has been restored to %s', '<code>' . $url . $state_user['route'] . '</code>'];
        }
    } else if (empty($route_user_reset)) {
        $_['alert']['info'][] = ['Your user log-in URL has been changed to %s', '<code>' . $url . $route_user . '</code>'];
    } else {
        $_['alert']['info'][] = ['Your user log-in URL has been restored to %s', '<code>' . $url . $state_user['route'] . '</code>'];
    }
    x\panel\_cache_let($file->path);
    x\panel\_cache_let(LOT . D . 'x' . D . 'user' . D . 'state.php');
    x\panel\_cache_let(LOT . D . 'x' . D . 'panel' . D . 'state.php');
    $_POST['kick'] = [
        'base' => $url . $route_panel,
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
    $panels['get/' . $n . '/1'] = 'x' === $n ? 'Extension' : ('y' === $n ? 'Layout' : To::title($n));
}

foreach (glob(LOT . D . 'page' . D . '*.{archive,page}', GLOB_NOSORT | GLOB_BRACE) as $path) {
    $routes['/' . pathinfo($path, PATHINFO_FILENAME)] = S . (new Page($path))->title . S;
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
                                                            'name' => 'state[route]',
                                                            'stack' => 50,
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
                                                            'description' => 'Set custom panel base path.',
                                                            'hint' => $state_panel['route'] ?? null,
                                                            'name' => 'state[x][panel][route]',
                                                            'stack' => 10,
                                                            'type' => 'route',
                                                            'value' => $state_r['x']['panel']['route'] ?? null
                                                        ],
                                                        'user' => [
                                                            'description' => 'Set custom user log-in path.',
                                                            'hint' => $state_user['guard']['route'] ?? $state_user['route'] ?? null,
                                                            'name' => 'state[x][user][guard][route]',
                                                            'stack' => 20,
                                                            'type' => 'route',
                                                            'value' => $state_r['x']['user']['guard']['route'] ?? $state_user['guard']['route'] ?? null
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