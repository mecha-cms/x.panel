<?php

// Force layout to `state`
$GLOBALS['_']['layout'] = $_['layout'] = 'state';

// Fix #13 <https://stackoverflow.com/a/53893947/1163000>
$fresh = function($path) {
    if (function_exists('opcache_invalidate') && strlen((string) ini_get('opcache.restrict_api')) < 1) {
        opcache_invalidate($path, true);
    } else if (function_exists('apc_compile_file')) {
        apc_compile_file($path);
    }
    return $path;
};

// Load primary state(s)
$state_0 = require $fresh(ROOT . DS . 'state.php');
$state_1 = require $fresh(LOT . DS . 'x' . DS . 'user' . DS . 'state.php');
$state_2 = require $fresh(LOT . DS . 'x' . DS . 'panel' . DS . 'state.php');

// Sanitize form data
Hook::set('do.state.get', function($_, $lot) use(&$state_0, &$state_1, &$state_2) {
    if ('POST' !== $_SERVER['REQUEST_METHOD'] || !isset($lot['state'])) {
        return $_;
    }
    extract($GLOBALS, EXTR_SKIP);
    $lot['state']['title'] = _\lot\x\panel\h\w($lot['state']['title'] ?? "");
    $lot['state']['description'] = _\lot\x\panel\h\w($lot['state']['description'] ?? "");
    $lot['state']['email'] = _\lot\x\panel\h\w($lot['state']['email'] ?? "");
    $lot['state']['charset'] = strip_tags($lot['state']['charset'] ?? 'utf-8');
    $lot['state']['language'] = strip_tags($lot['state']['language'] ?? 'en');
    $default = $state_1['guard']['path'] ?? $state_2['guard']['path'] ?? $state_0['x']['user']['guard']['path'] ?? $state_0['x']['panel']['guard']['path'] ?? "";
    $default = '/' . trim($default, '/') . '/';
    if (!empty($lot['state']['x']['user']['guard']['path'])) {
        if ($secret = To::kebab(trim($lot['state']['x']['user']['guard']['path'], '/'))) {
            $lot['state']['x']['user']['guard']['path'] = '/' . $secret;
            $default = '/' . $secret . '/';
        } else {
            unset($lot['state']['x']['user']['guard']['path']);
        }
    }
    if ($_['/'] !== $default) {
        $_['/'] = $default;
        if ($default === $state_2['guard']['path'] . '/') {
            $_['alert']['info'][] = ['Your log-in URL has been restored to %s', '<code>' . $url . $state_1['path'] . '</code>'];
        } else {
            $_['alert']['info'][] = ['Your log-in URL has been changed to %s', '<code>' . $url . substr($default, 0, -1) . '</code>'];
        }
    }
    $_POST = $lot; // Update data
    return $_;
}, 9.9);

if (1 !== $user['status'] || 'g' !== $_['task']) {
    if (Is::user()) {
        $_['alert']['error'][] = i('Permission denied for your current user status: %s', '<code>' . $user['status'] . '</code>') . '<br><small>' . $url->current . '</small>';
        $_['kick'] = $url . $_['/'] . '::g::' . $_['state']['path'] . '/1' . $url->query('&', ['layout' => false, 'tab' => false]) . $url->hash;
    } else {
        $_['kick'] = "";
    }
}

if (isset($_['i']) || count($_['chops']) > 1) {
    $_['kick'] = $url . $_['/'] . '::g::/' . $_['chops'][0];
}

$panes = $paths = [];

foreach (\glob(\LOT . \DS . '*', \GLOB_NOSORT | \GLOB_ONLYDIR) as $panel) {
    $n = \basename($panel);
    if (false !== \strpos('_.-', $n[0])) {
        continue;
    }
    $panes['/' . $n] = 'x' === $n ? 'Extension' : \To::title($n);
}

foreach (\glob(\LOT . \DS . 'page' . \DS . '*.{archive,page}', \GLOB_NOSORT | \GLOB_BRACE) as $path) {
    $paths['/' . \pathinfo($path, \PATHINFO_FILENAME)] = \S . (new \Page($path))->title . \S;
}

\asort($panes);
\asort($paths);

$zones = \Cache::hit(__FILE__, function() {
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
        $offset_formatted = \gmdate('H:i', \abs($offset));
        $zones[$zone] = 'GMT' . $offset_prefix . $offset_formatted . ' (' . \strtr($zone, '_', ' ') . ')';
    }
    return $zones;
}, '1 year');

$_['lot'] = \array_replace_recursive($_['lot'], require __DIR__ . \DS . '..' . \DS . 'state' . DS . 'state.php', [
    'bar' => [
        'lot' => [
            0 => [
                'lot' => [
                    'folder' => ['hidden' => true],
                    'link' => [
                        'hidden' => false,
                        'url' => $url . $_['/'] . '::g::' . $_['state']['path'] . '/1' . $url->query('&', ['layout' => false, 'tab' => false]) . $url->hash
                    ],
                    's' => ['hidden' => true],
                    'search' => ['hidden' => true] // Hide search form
                ]
            ]
        ]
    ],
    'desk' => [
        'lot' => [
            'form' => [
                'lot' => [
                    'fields' => [
                        'lot' => [
                            0 => [
                                'type' => 'Hidden',
                                'name' => 'path',
                                'value' => '/../state.php'
                            ],
                            // No use. This field was added just to remove error message of
                            // empty `file[name]` field generated by `_\lot\x\panel\task\get\file()`
                            1 => [
                                'type' => 'Hidden',
                                'name' => 'file[name]',
                                'value' => 'state.php'
                            ]
                        ]
                    ],
                    1 => [
                        'lot' => [
                            'tabs' => [
                                'lot' => [
                                    'file' => [
                                        'icon' => 'M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z',
                                        'title' => false,
                                        'description' => 'Site',
                                        'name' => 'site',
                                        'lot' => [
                                            'fields' => [
                                                // type: Fields
                                                'lot' => [
                                                    'content' => ['hidden'=> true],
                                                    'name' => ['hidden' => true],
                                                    'title' => [
                                                        'type' => 'Text',
                                                        'name' => 'state[title]',
                                                        'alt' => ($v = $state_0['title'] ?? null) ?? 'Title Goes Here',
                                                        'value' => $v,
                                                        'width' => true,
                                                        'stack' => 10
                                                    ],
                                                    'description' => [
                                                        'type' => 'Content',
                                                        'name' => 'state[description]',
                                                        'alt' => 'Description goes here...',
                                                        'value' => $state_0['description'] ?? null,
                                                        'width' => true,
                                                        'stack' => 20
                                                    ],
                                                    'path' => [
                                                        'title' => 'Home',
                                                        'description' => 'Choose default page that will open in the home page.',
                                                        'type' => 'Combo',
                                                        'name' => 'state[path]',
                                                        'value' => $state_0['path'] ?? null,
                                                        'lot' => $paths,
                                                        'stack' => 30
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'panel' => [
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => [
                                                    'path' => [
                                                        'title' => 'Home',
                                                        'description' => 'Choose default page that will open after logged-in.',
                                                        'type' => 'Combo',
                                                        'name' => 'state[x][panel][path]',
                                                        'value' => $state_0['x']['panel']['path'] ?? null,
                                                        'lot' => $panes,
                                                        'stack' => 10
                                                    ],
                                                    'key' => [
                                                        'description' => 'Set custom log-in path.',
                                                        'type' => 'Text',
                                                        'name' => 'state[x][user][guard][path]',
                                                        'pattern' => "^/([a-z\\d]+)(-[a-z\\d]+)*$",
                                                        'alt' => $state_1['guard']['path'] ?? $state_1['path'] ?? null,
                                                        'value' => $state_0['x']['user']['guard']['path'] ?? null,
                                                        'stack' => 20
                                                    ]
                                                ],
                                                'stack' => 10
                                            ]
                                        ],
                                        'stack' => 20
                                    ],
                                    'locale' => [
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => [
                                                    'zone' => [
                                                        'type' => 'Combo',
                                                        'name' => 'state[zone]',
                                                        'value' => $state_0['zone'] ?? null,
                                                        'lot' => $zones,
                                                        'width' => true,
                                                        'stack' => 10
                                                    ],
                                                    'direction' => [
                                                        'type' => 'Item',
                                                        'name' => 'state[direction]',
                                                        'value' => $state_0['direction'] ?? null,
                                                        'lot' => [
                                                            'ltr' => '<abbr title="Left to Right">LTR</abbr>',
                                                            'rtl' => '<abbr title="Right to Left">RTL</abbr>'
                                                        ],
                                                        'stack' => 20
                                                    ],
                                                    'charset' => [
                                                        'type' => 'Text',
                                                        'name' => 'state[charset]',
                                                        'alt' => ($v = $state_0['charset'] ?? null) ?? 'utf-8',
                                                        'value' => $v,
                                                        'stack' => 30
                                                    ],
                                                    'language' => [
                                                        'description' => 'This value does not determine the I18N system on your site unless you want to make an I18N extension that depends on this value.',
                                                        'type' => 'Text',
                                                        'name' => 'state[language]',
                                                        'pattern' => "^([a-z\\d]+)(-[a-z\\d]+)*$",
                                                        'alt' => ($v = $state_0['language'] ?? null) ?? 'en',
                                                        'value' => $v,
                                                        'stack' => 40
                                                    ]
                                                ],
                                                'stack' => 10
                                            ]
                                        ],
                                        'stack' => 30
                                    ],
                                    'alert' => [
                                        'title' => 'Notifications',
                                        'lot' => [
                                            'fields' => [
                                                'type' => 'Fields',
                                                'lot' => [
                                                    'email' => [
                                                        'description' => 'This email address will be used to receive certain messages to your inbox as the fastest solution for notifications. At this time you may not use it to receive any messages, but some extensions that require an email address may depend on this value.',
                                                        'type' => 'Email',
                                                        'name' => 'state[email]',
                                                        'value' => $state_0['email'] ?? null,
                                                        'stack' => 10
                                                    ]
                                                ],
                                                'stack' => 10
                                            ]
                                        ],
                                        'stack' => 40
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
                                                    'l' => ['hidden' => true], // Hide delete button
                                                    's' => [
                                                        'description' => ['Save to %s', ".\\state.php"]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
]);

// Update data
$GLOBALS['_'] = $_;
