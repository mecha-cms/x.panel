<?php

namespace _\lot\x\panel\route {
    function __alert($_) {
        // Create folder if not exists
        if (!\is_dir($d = \LOT . \DS . '.alert')) {
            \mkdir($d, 0775, true);
        }
    }
    function __license($_) {
        if ('g' !== $_['task']) {
            $_['kick'] = $GLOBALS['url'] . $_['/'] . '/::g::/.license';
            return $_;
        }
        if (!\is_file($f = \ENGINE . \DS . 'log' . \DS . \dechex(\crc32(\ROOT)))) {
            if (!\is_dir($d = \dirname($f))) {
                \mkdir($d, 0775, true);
            }
            \file_put_contents($f, \date('Y-m-d H:i:s'));
        }
        $content = <<<HTML
<h3>General Agreement</h3>
<p>You are free to use this application personally, either for commercial or for non-commercial purposes. You will only be charged a fee when distributing Mecha along with this control panel feature to your clients who pay for your modified Mecha products.</p>
<p>In other words, use it for free and pay only if you get paid. If you make a commercial product with this extension included (e.g. getting paid from a client who wants to have a website made of this product or getting paid from a client who has gotten a website from you made of this product), then I would kindly ask you to give a small financial support for about 15 USD per product to keep this project floating around the world wide web.</p>
<p>You have the right to determine the price of your project without any interference from me. You may be able to discuss this additional cost with your clients, honestly, without the need to keep anything confidential. I want everything to be transparent so that no one feels aggrieved.</p>
<p>This agreement applies only to the first person (the developer who created the product). After that, the developer may assign a separate license to their product. For example, making an agreement on how to distribute and resell the purchased products.</p>
<p>Your custom themes and extensions included in the product are not affected by this license.</p>
<h3>Dealing with Mass Production Activities</h3>
<p>If you have a business mechanism that allows your clients to download packages after making a payment, and you don&#x2019;t want to be tied down to this revenue sharing, then you will need to remove the control panel feature from your downloadable package. Please provide clear guidances separately on how to install the base control panel feature for people who want to download and use your packages.</p>
<h3>Notes</h3>
<p>If you are from Indonesia and are having problems with the current rupiah exchange rate against the dollar, you are allowed to consider 15 USD as 150,000 IDR.</p>
<p><a class="button" href="https://paypal.me/tatautaufik" target="_blank">Donate for 15 USD</a></p>
<p>Thank you 💕️</p>
HTML;
        $_['lot']['title'] = 'End-User License Agreement';
        $_['lot']['desk']['lot']['form']['lot'][1]['title'] = 'End-User License Agreement';
        $_['lot']['desk']['lot']['form']['lot'][1]['description'] = 'This End-User License Agreement (EULA) is a legal agreement between you (either as an individual or on behalf of an entity) and Mecha, regarding your use of Mecha&#x2019;s control panel extension. This license agreement does not apply when you use Mecha without the control panel feature.';
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['page'] = [
            'content' => $content,
            'stack' => 10
        ];
        return $_;
    }
    function __state($_) {
        extract($GLOBALS, \EXTR_SKIP);
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
        $state_r = require $fresh(\ROOT . \DS . 'state.php');
        $state_user = require $fresh(\LOT . \DS . 'x' . \DS . 'user' . \DS . 'state.php');
        $state_panel = require $fresh(\LOT . \DS . 'x' . \DS . 'panel' . \DS . 'state.php');
        // Sanitize form data
        \Hook::set('do.state.get', function($_) use($fresh, &$state_r, &$state_user, &$state_panel) {
            if ('POST' !== $_SERVER['REQUEST_METHOD'] || !isset($_['form']['state'])) {
                return $_;
            }
            extract($GLOBALS, \EXTR_SKIP);
            $_['form']['state']['title'] = \_\lot\x\panel\h\w($_['form']['state']['title'] ?? "");
            $_['form']['state']['description'] = \_\lot\x\panel\h\w($_['form']['state']['description'] ?? "");
            $_['form']['state']['email'] = \_\lot\x\panel\h\w($_['form']['state']['email'] ?? "");
            $_['form']['state']['charset'] = \strip_tags($_['form']['state']['charset'] ?? 'utf-8');
            $_['form']['state']['language'] = \strip_tags($_['form']['state']['language'] ?? 'en');
            $def = $state_user['guard']['path'] ?? $state_panel['guard']['path'] ?? $state_r['x']['user']['guard']['path'] ?? $state_r['x']['panel']['guard']['path'] ?? $state_user['path'] ?? "";
            $def = '/' . \trim($def, '/');
            if (!empty($_['form']['state']['x']['user']['guard']['path'])) {
                if ($secret = \To::kebab(\trim($_['form']['state']['x']['user']['guard']['path'], '/'))) {
                    $_['form']['state']['x']['user']['guard']['path'] = $def = '/' . $secret;
                } else {
                    unset($_['form']['state']['x']['user']['guard']['path']);
                }
            }
            if ($_['/'] !== $def) {
                if ($state_panel['guard']['path'] === $def) {
                    $_['alert']['info'][] = ['Your log-in URL has been restored to %s', '<code>' . $url . $state_user['path'] . '</code>'];
                } else {
                    $_['alert']['info'][] = ['Your log-in URL has been changed to %s', '<code>' . $url . $def . '</code>'];
                }
            }
            $fresh(\ROOT . \DS . 'state.php');
            $fresh(\LOT . \DS . 'x' . \DS . 'user' . \DS . 'state.php');
            $fresh(\LOT . \DS . 'x' . \DS . 'panel' . \DS . 'state.php');
            // TODO
            $_['form']['kick'] = $url . ($_['/'] = $def) . '/::g::/.state' . $url->query;
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
        if (isset($_['i']) || \count($_['chops']) > 2) {
            $_['kick'] = $url . $_['/'] . '/::g::/' . $_['chops'][0];
        }
        $_['lot'] = \array_replace_recursive($_['lot'] ?? [], require __DIR__ . \DS . '..' . \DS . 'r' . \DS . 'state' . \DS . 'state.php');
        // `http://127.0.0.1/panel/::g::/.state`
        if (1 === \count($_['chops'])) {
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
            $_['lot'] = \array_replace_recursive($_['lot'], [
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
                                                                    'alt' => ($v = $state_r['title'] ?? null) ?? 'Title Goes Here',
                                                                    'value' => $v,
                                                                    'width' => true,
                                                                    'stack' => 10
                                                                ],
                                                                'description' => [
                                                                    'type' => 'content',
                                                                    'name' => 'state[description]',
                                                                    'alt' => 'Description goes here...',
                                                                    'value' => $state_r['description'] ?? null,
                                                                    'width' => true,
                                                                    'stack' => 20
                                                                ],
                                                                'path' => [
                                                                    'title' => 'Home',
                                                                    'description' => 'Choose default page that will open in the home page.',
                                                                    'type' => 'combo',
                                                                    'name' => 'state[path]',
                                                                    'value' => $state_r['path'] ?? null,
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
                                                                    'value' => $state_r['x']['panel']['path'] ?? $state_panel['path'] ?? null,
                                                                    'lot' => $panes,
                                                                    'stack' => 10
                                                                ],
                                                                'key' => [
                                                                    'description' => 'Set custom log-in path.',
                                                                    'type' => 'text',
                                                                    'name' => 'state[x][user][guard][path]',
                                                                    'pattern' => "^/([a-z\\d]+)(-[a-z\\d]+)*$",
                                                                    'alt' => $state_user['guard']['path'] ?? $state_user['path'] ?? null,
                                                                    'value' => $state_r['x']['user']['guard']['path'] ?? $state_user['guard']['path'] ?? null,
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
                                                                    'value' => $state_r['zone'] ?? null,
                                                                    'lot' => $zones,
                                                                    'width' => true,
                                                                    'stack' => 10
                                                                ],
                                                                'direction' => [
                                                                    'type' => 'item',
                                                                    'name' => 'state[direction]',
                                                                    'value' => $state_r['direction'] ?? null,
                                                                    'lot' => [
                                                                        'ltr' => '<abbr title="Left to Right">LTR</abbr>',
                                                                        'rtl' => '<abbr title="Right to Left">RTL</abbr>'
                                                                    ],
                                                                    'stack' => 20
                                                                ],
                                                                'charset' => [
                                                                    'type' => 'text',
                                                                    'name' => 'state[charset]',
                                                                    'alt' => ($v = $state_r['charset'] ?? null) ?? 'utf-8',
                                                                    'value' => $v,
                                                                    'stack' => 30
                                                                ],
                                                                'language' => [
                                                                    'description' => 'This value does not determine the I18N system on your site unless you want to make an I18N extension that depends on this value.',
                                                                    'type' => 'text',
                                                                    'name' => 'state[language]',
                                                                    'pattern' => "^([a-z\\d]+)(-[a-z\\d]+)*$",
                                                                    'alt' => ($v = $state_r['language'] ?? null) ?? 'en',
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
                                                                    'value' => $state_r['email'] ?? null,
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
        } else if (2 === \count($_['chops'])) {
            // TODO: Custom state editor for extension(s)
            if (\is_file($f = \LOT . \DS . 'x' . \DS . $_['chops'][1] . \DS . 'state.php')) {
                \test(require $f);
                exit;
            }
        }
        return $_;
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
        return $_;
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
                    if (isset($_['form']['page']['status']) && $_['form']['page']['status'] !== $status) {
                        $_['alert']['error'][] = ['You don\'t have permission to change the %s value.', '<code>status</code>'];
                    }
                    $_['form']['page']['status'] = $status;
                    unset($_POST['data']['status'], $_['form']['data']['status']);
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
        return $_;
    }
}
