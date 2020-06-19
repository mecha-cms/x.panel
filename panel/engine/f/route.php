<?php

namespace _\lot\x\panel\route\x {
    function panel($_) {
        if (!empty($_['form']['tab'][0]) && 'license' === $_['form']['tab'][0] && !\is_file($f = \ENGINE . \DS . 'log' . \DS . \dechex(\crc32(\ROOT)))) {
            if (!\is_dir($d = \dirname($f))) {
                \mkdir($d, 0775, true);
            }
            \file_put_contents($f, \date('Y-m-d H:i:s'));
        }
    }
}

namespace _\lot\x\panel\route {
    function __alert($_) {
        // Create folder if not exists
        if (!\is_dir($d = \LOT . \DS . '.alert')) {
            \mkdir($d, 0775, true);
        }
    }
    function __state($_) {
        extract($GLOBALS, \EXTR_SKIP);
        // Force layout to `state`
        $_['layout'] = 'state';
        // Fix #13 <https://stackoverflow.com/a/53893947/1163000>
        $fresh = function($path) {
            if (\function_exists("\\opcache_invalidate") && \strlen((string) \ini_get('opcache.restrict_api')) < 1) {
                \opcache_invalidate($path, true);
            } else if (function_exists("\\apc_compile_file")) {
                \apc_compile_file($path);
            }
            return $path;
        };
        // Load primary state(s)
        $state_0 = require $fresh(\ROOT . \DS . 'state.php');
        $state_1 = require $fresh(\LOT . \DS . 'x' . \DS . 'user' . \DS . 'state.php');
        $state_2 = require $fresh(\LOT . \DS . 'x' . \DS . 'panel' . \DS . 'state.php');
        // Sanitize form data
        \Hook::set('do.state.get', function($_) use(&$state_0, &$state_1, &$state_2) {
            if ('POST' !== $_SERVER['REQUEST_METHOD'] || !isset($_['form']['state'])) {
                return $_;
            }
            extract($GLOBALS, \EXTR_SKIP);
            $_['form']['state']['title'] = \_\lot\x\panel\h\w($_['form']['state']['title'] ?? "");
            $_['form']['state']['description'] = \_\lot\x\panel\h\w($_['form']['state']['description'] ?? "");
            $_['form']['state']['email'] = \_\lot\x\panel\h\w($_['form']['state']['email'] ?? "");
            $_['form']['state']['charset'] = \strip_tags($_['form']['state']['charset'] ?? 'utf-8');
            $_['form']['state']['language'] = \strip_tags($_['form']['state']['language'] ?? 'en');
            $default = $state_1['guard']['path'] ?? $state_2['guard']['path'] ?? $state_0['x']['user']['guard']['path'] ?? $state_0['x']['panel']['guard']['path'] ?? "";
            $default = '/' . \trim($default, '/');
            if (!empty($_['form']['state']['x']['user']['guard']['path'])) {
                if ($secret = \To::kebab(\trim($_['form']['state']['x']['user']['guard']['path'], '/'))) {
                    $_['form']['state']['x']['user']['guard']['path'] = $default = '/' . $secret;
                } else {
                    unset($_['form']['state']['x']['user']['guard']['path']);
                }
            }
            if ($_['/'] !== $default) {
                $_['/'] = $default;
                if ($state_2['guard']['path'] === $default) {
                    $_['alert']['info'][] = ['Your log-in URL has been restored to %s', '<code>' . $url . $state_1['path'] . '</code>'];
                } else {
                    $_['alert']['info'][] = ['Your log-in URL has been changed to %s', '<code>' . $url . $default . '</code>'];
                }
            }
            return $_;
        }, 9.9);
        if (1 !== $user['status'] || 'g' !== $_['task']) {
            if (\Is::user()) {
                $_['alert']['error'][] = \i('Permission denied for your current user status: %s', '<code>' . $user['status'] . '</code>') . '<br><small>' . $url->current . '</small>';
                $_['kick'] = $url . $_['/'] . '/::g::' . $_['state']['path'] . '/1' . $url->query('&', ['layout' => false, 'tab' => false]) . $url->hash;
            } else {
                $_['kick'] = "";
            }
        }
        if (isset($_['i']) || \count($_['chops']) > 1) {
            $_['kick'] = $url . $_['/'] . '/::g::/' . $_['chops'][0];
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
            \asort($timezone_offsets);
            foreach ($timezone_offsets as $zone => $offset) {
                $offset_prefix = $offset < 0 ? '-' : '+';
                $offset_formatted = \gmdate('H:i', \abs($offset));
                $zones[$zone] = 'GMT' . $offset_prefix . $offset_formatted . ' (' . \strtr($zone, '_', ' ') . ')';
            }
            return $zones;
        }, '1 year');
        $_['lot'] = \array_replace_recursive($_['lot'], require __DIR__ . \DS . '..' . \DS . 'r' . \DS . 'state' . DS . 'state.php', [
            'bar' => [
                'lot' => [
                    0 => [
                        'lot' => [
                            'folder' => ['hidden' => true],
                            'link' => [
                                'hidden' => false,
                                'url' => $url . $_['/'] . '/::g::' . $_['state']['path'] . '/1' . $url->query('&', ['layout' => false, 'tab' => false]) . $url->hash
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
                                        'type' => 'hidden',
                                        'name' => 'path',
                                        'value' => '../state.php'
                                    ],
                                    // No use. This field was added just to remove error message of
                                    // empty `file[name]` field generated by `_\lot\x\panel\task\get\file()`
                                    1 => [
                                        'type' => 'hidden',
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
                                                        // type: fields
                                                        'lot' => [
                                                            'content' => ['hidden'=> true],
                                                            'name' => ['hidden' => true],
                                                            'title' => [
                                                                'type' => 'text',
                                                                'name' => 'state[title]',
                                                                'alt' => ($v = $state_0['title'] ?? null) ?? 'Title Goes Here',
                                                                'value' => $v,
                                                                'width' => true,
                                                                'stack' => 10
                                                            ],
                                                            'description' => [
                                                                'type' => 'content',
                                                                'name' => 'state[description]',
                                                                'alt' => 'Description goes here...',
                                                                'value' => $state_0['description'] ?? null,
                                                                'width' => true,
                                                                'stack' => 20
                                                            ],
                                                            'path' => [
                                                                'title' => 'Home',
                                                                'description' => 'Choose default page that will open in the home page.',
                                                                'type' => 'combo',
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
                                                        'type' => 'fields',
                                                        'lot' => [
                                                            'path' => [
                                                                'title' => 'Home',
                                                                'description' => 'Choose default page that will open after logged-in.',
                                                                'type' => 'combo',
                                                                'name' => 'state[x][panel][path]',
                                                                'value' => $state_0['x']['panel']['path'] ?? null,
                                                                'lot' => $panes,
                                                                'stack' => 10
                                                            ],
                                                            'key' => [
                                                                'description' => 'Set custom log-in path.',
                                                                'type' => 'text',
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
                                                        'type' => 'fields',
                                                        'lot' => [
                                                            'zone' => [
                                                                'type' => 'combo',
                                                                'name' => 'state[zone]',
                                                                'value' => $state_0['zone'] ?? null,
                                                                'lot' => $zones,
                                                                'width' => true,
                                                                'stack' => 10
                                                            ],
                                                            'direction' => [
                                                                'type' => 'item',
                                                                'name' => 'state[direction]',
                                                                'value' => $state_0['direction'] ?? null,
                                                                'lot' => [
                                                                    'ltr' => '<abbr title="Left to Right">LTR</abbr>',
                                                                    'rtl' => '<abbr title="Right to Left">RTL</abbr>'
                                                                ],
                                                                'stack' => 20
                                                            ],
                                                            'charset' => [
                                                                'type' => 'text',
                                                                'name' => 'state[charset]',
                                                                'alt' => ($v = $state_0['charset'] ?? null) ?? 'utf-8',
                                                                'value' => $v,
                                                                'stack' => 30
                                                            ],
                                                            'language' => [
                                                                'description' => 'This value does not determine the I18N system on your site unless you want to make an I18N extension that depends on this value.',
                                                                'type' => 'text',
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
                                                        'type' => 'fields',
                                                        'lot' => [
                                                            'email' => [
                                                                'description' => 'This email address will be used to receive certain messages to your inbox as the fastest solution for notifications. At this time you may not use it to receive any messages, but some extensions that require an email address may depend on this value.',
                                                                'type' => 'email',
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
    }
    function asset($_) {
        extract($GLOBALS, \EXTR_SKIP);
        if (!\is_dir($d = \LOT . \DS . 'asset' . \DS . $user->user)) {
            \mkdir($d, 0755, true);
            $_['alert']['success'][] = ['Created folder %s.', '<code>' . \_\lot\x\panel\h\path($d) . '</code>'];
            $_SESSION['_']['folder'][$d] = 1;
            $_['kick'] = $url->current;
        }
        // You cannot edit or delete your own folder
        if (\count($_['chops']) < 3) {
            if ('g' === $_['task'] && isset($_['i'])) {
                $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$d]['tasks']['g']['url'] = false;
                $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$d]['tasks']['l']['url'] = false;
            }
        }
        if (1 !== $user['status']) {
            // Force to enter to the user file(s)
            if (1 === \count($_['chops']) || $_['chops'][1] !== $user->user) {
                $_['alert']['error'][] = \i('Permission denied for your current user status: %s', '<code>' . $user['status'] . '</code>') . '<br><small>' . $url->current . '</small>';
                $_['kick'] = $url . $_['/'] . '/::g::/asset/' . $user->user . '/1';
            }
            // Hide parent folder link
            if (\count($_['chops']) < 3) {
                $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$_['f']]['hidden'] = true;
            }
        }
        // Update data
        $GLOBALS['_'] = $_;
    }
    function user($_) {
        extract($GLOBALS, \EXTR_SKIP);
        $status = $user['status'];
        if (\count($_['chops']) > 1) {
            if (1 !== $status) {
                // Hide add user link
                $_['lot']['bar']['lot'][0]['lot']['s']['hidden'] = true;
                // XSS Protection
                if ('POST' === $_SERVER['REQUEST_METHOD']) {
                    // Prevent user(s) from adding a hidden form (or changing the `page[status]` field value) that
                    // defines its `status` through developer tools and such by enforcing the `page[status]` value
                    if (isset($_POST['page']['status']) && $_POST['page']['status'] !== $status) {
                        $_['alert']['error'][] = ['You don\'t have permission to change the %s value.', '<code>status</code>'];
                    }
                    $_POST['page']['status'] = $status;
                    unset($_POST['data']['status']);
                }
                // Prevent user from editing other user file(s)
                if ('g' === $_['task'] && $_['f'] !== $user->path) {
                    $_['alert']['error'][] = \i('Permission denied for your current user status: %s', '<code>' . $user['status'] . '</code>') . '<br><small>' . $url->current . '</small>';
                    $_['kick'] = \dirname($url->clean) . '/1' . $url->hash;
                }
            }
        // Prevent user(s) from creating new user
        } else if ('s' === $_['task'] && 1 !== $status) {
            $_['alert']['error'][] = \i('Permission denied for your current user status: %s', '<code>' . $user['status'] . '</code>') . '<br><small>' . $url->current . '</small>';
            $_['kick'] = $url . $_['/'] . '/::g::/user/' . $user->name(true) . $url->query('&', [
                'layout' => false,
                'tab' => false
            ]) . $url->hash;
        }
        // Update data
        $GLOBALS['_'] = $_;
    }
}
