<?php

namespace x\panel\route {
    function __license($_) {
        // Disable page offset feature and request type other than `GET`
        if (isset($_['i']) || 'g' !== $_['task']) {
            $_['kick'] = $_['/'] . '/::g::/.license';
            return $_;
        }
        // Hide search form
        $_['lot']['bar']['lot'][0]['lot']['search']['skip'] = true;
        if (!\is_file($f = \ENGINE . \DS . 'log' . \DS . \dechex(\crc32(\ROOT)))) {
            if (!\is_dir($d = \dirname($f))) {
                \mkdir($d, 0775, true);
            }
            \file_put_contents($f, \date('Y-m-d H:i:s'));
        }
        $content = <<<HTML
<h3>General Agreement</h3>
<p>You are free to use this application, either for commercial or for non-commercial purposes. You will only be charged a fee when distributing Mecha along with this control panel feature to your clients who pay for your modified Mecha products.</p>
<p>In other words, use it for free, and pay only if you get paid. If you make a commercial product with this extension included (e.g. getting paid from a client who want to have a website made of this product), then I would kindly ask you to give a small financial support for about 20 USD per product to keep this project floating around the world wide web.</p>
<p>You have the right to determine the price of your project without any interference from me. You may be able to discuss this additional cost with your client, honestly, without the need to keep anything confidential. I want everything to be transparent so that no one feels aggrieved.</p>
<p>This agreement applies only to the first person (the developer who created the product). After that, you (the developer) may assign a separate license that is still within the scope of the <a href="https://www.gnu.org/licenses/gpl-faq.html" rel="nofollow" target="_blank">GNU General Public License Version 3</a> to your product. For example, making an agreement on how to distribute and resell the purchased products.</p>
<p>Your custom themes and extensions included in the product are not affected by this agreement.</p>
<h3>Dealing with Mass Production Activities</h3>
<p>If you have a business mechanism that allows your clients to download packages after making a payment, and you don&#x2019;t want to be tied down to this revenue sharing, then you will need to remove the control panel feature from your downloadable package. Please provide a clear guidance separately on how to install the base control panel feature for people who want to download and use your packages.</p>
<p>Support system and forums will always be available free of charge on <a href="https://github.com/mecha-cms/mecha/discussions" rel="nofollow" target="_blank">GitHub</a>.</p>
<h3>Notes</h3>
<p>If you are from Indonesia and are having problems with the current rupiah exchange rate against the dollar, you are allowed to consider 20 USD as 200,000 IDR.</p>
<p><a class="button" href="https://paypal.me/tatautaufik" target="_blank">Donate for 20 USD</a></p>
<p>Thank you 💕️</p>
HTML;
        $_['title'] = 'End-User License Agreement';
        $_['lot']['desk']['lot']['form']['lot'][1]['title'] = $_['title'];
        $_['lot']['desk']['lot']['form']['lot'][1]['description'] = 'This End-User License Agreement (EULA) is a legal agreement between you (either as an individual or on behalf of an entity) and Mecha, regarding your use of Mecha&#x2019;s control panel extension. This license agreement does not apply when you use Mecha without the control panel extension.';
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['gap'] = false;
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['page'] = [
            'content' => $content,
            'stack' => 10
        ];
        return $_;
    }
    function __state($_) {
        extract($GLOBALS, \EXTR_SKIP);
        // Disable page offset and page children feature
        if (isset($_['i']) || \count($_['chop']) > 1) {
            $_['kick'] = $_['/'] . '/::g::/' . $_['id'];
        }
        // Load primary state(s)
        $state_r = require \x\panel\to\fresh(\ROOT . \DS . 'state.php');
        $state_user = require \x\panel\to\fresh(\LOT . \DS . 'x' . \DS . 'user' . \DS . 'state.php');
        $state_panel = require \x\panel\to\fresh(\LOT . \DS . 'x' . \DS . 'panel' . \DS . 'state.php');
        // Sanitize the form data
        if ('post' === $_['form']['type'] && isset($_['form']['lot']['state'])) {
            $_['form']['lot']['state']['title'] = \x\panel\to\w($_['form']['lot']['state']['title'] ?? "");
            $_['form']['lot']['state']['description'] = \x\panel\to\w($_['form']['lot']['state']['description'] ?? "");
            $_['form']['lot']['state']['email'] = \x\panel\to\w($_['form']['lot']['state']['email'] ?? "");
            $_['form']['lot']['state']['charset'] = \strip_tags($_['form']['lot']['state']['charset'] ?? 'utf-8');
            $_['form']['lot']['state']['language'] = \strip_tags($_['form']['lot']['state']['language'] ?? 'en');
            $def = $state_user['guard']['path'] ?? $state_panel['guard']['path'] ?? $state_r['x']['user']['guard']['path'] ?? $state_r['x']['panel']['guard']['path'] ?? $state_user['path'] ?? "";
            $def = '/' . \trim($def, '/');
            if (!empty($_['form']['lot']['state']['x']['user']['guard']['path'])) {
                if ($secret = \To::kebab(\trim($_['form']['lot']['state']['x']['user']['guard']['path'], '/'))) {
                    $_['form']['lot']['state']['x']['user']['guard']['path'] = $def = '/' . $secret;
                } else {
                    unset($_['form']['lot']['state']['x']['user']['guard']['path']);
                }
            }
            if ($_['/'] !== $url . $def) {
                if ($state_panel['guard']['path'] === $def) {
                    $_['alert']['info'][] = ['Your log-in URL has been restored to %s', '<code>' . $url . $state_user['path'] . '</code>'];
                } else {
                    $_['alert']['info'][] = ['Your log-in URL has been changed to %s', '<code>' . $url . $def . '</code>'];
                }
            }
            \x\panel\to\fresh(\ROOT . \DS . 'state.php');
            \x\panel\to\fresh(\LOT . \DS . 'x' . \DS . 'user' . \DS . 'state.php');
            \x\panel\to\fresh(\LOT . \DS . 'x' . \DS . 'panel' . \DS . 'state.php');
            $_['form']['lot']['kick'] = ($_['/'] = $url . $def) . '/::g::/.state' . $url->query('&', [
                'tab' => $_['form']['lot']['tab'] ?? false
            ]);
        }
        $_ = \array_replace_recursive($_ ?? [], require __DIR__ . \DS . '..' . \DS . 'r' . \DS . 'type' . \DS . 'state.php');
        // `http://127.0.0.1/panel/::g::/.state`
        if (1 === \count($_['chop'])) {
            $languages = $panes = $paths = [];
            if (isset($state->x->language)) {
                $labels = require \LOT . \DS . 'x' . \DS . 'panel' . \DS . 'state' . \DS . 'language.php';
                foreach (\glob(\LOT . \DS . 'x' . \DS . 'language' . \DS . 'state' . \DS . '*.php', \GLOB_NOSORT) as $language) {
                    $label = $labels[$n = \basename($language, '.php')] ?? \S . $n . \S;
                    if (false !== \strpos($label, '(') && \preg_match('/^\s*([^\(]+)\s*\(\s*([^)]+)\s*\)\s*$/', $label, $m)) {
                        $label = [
                            'title' => \S . $m[1] . \S,
                            'description' => \S . $m[2] . \S
                        ];
                    }
                    $languages[$n] = $label;
                }
            }
            \asort($languages);
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
            $_['lot'] = \array_replace_recursive($_['lot'] ?? [], [
                'bar' => [
                    'lot' => [
                        0 => [
                            'lot' => [
                                'folder' => ['skip' => true],
                                'link' => [
                                    'url' => $_['/'] . '/::g::' . $_['state']['path'] . '/1' . $url->query('&', [
                                        'tab' => false,
                                        'type' => false
                                    ]) . $url->hash,
                                    'skip' => false
                                ],
                                's' => ['skip' => true],
                                'search' => ['skip' => true] // Hide search form
                            ]
                        ]
                    ]
                ],
                'desk' => [
                    'lot' => [
                        'form' => [
                            'data' => [
                                // No use. This field was added just to remove error message of
                                // empty `file[name]` field generated by `x\panel\task\get\file()`
                                'file' => ['name' => 'state.php'],
                                'path' => '../state.php'
                            ],
                            'lot' => [
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
                                                                'content' => ['skip' => true],
                                                                'name' => ['skip' => true],
                                                                'title' => [
                                                                    'type' => 'title',
                                                                    'name' => 'state[title]',
                                                                    'hint' => ($v = $state_r['title'] ?? null) ?? 'Title Goes Here',
                                                                    'value' => $v,
                                                                    'width' => true,
                                                                    'stack' => 10
                                                                ],
                                                                'description' => [
                                                                    'type' => 'description',
                                                                    'name' => 'state[description]',
                                                                    'hint' => 'Description goes here...',
                                                                    'value' => $state_r['description'] ?? null,
                                                                    'width' => true,
                                                                    'stack' => 20
                                                                ],
                                                                'path' => [
                                                                    'title' => 'Home',
                                                                    'description' => 'Choose default page that will open in the home page.',
                                                                    'type' => 'option',
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
                                                                    'type' => 'option',
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
                                                                    'hint' => $state_user['guard']['path'] ?? $state_panel['guard']['path'] ?? $state_user['path'] ?? null,
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
                                                                    'type' => 'option',
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
                                                                    'hint' => ($v = $state_r['charset'] ?? null) ?? 'utf-8',
                                                                    'value' => $v,
                                                                    'stack' => 30
                                                                ],
                                                                'language' => [
                                                                    'description' => 'This value does not determine the I18N system on your site unless you want to make an I18N extension that depends on this value.',
                                                                    'type' => $languages ? 'option' : 'text',
                                                                    'name' => 'state[language]',
                                                                    'pattern' => "^([a-z\\d]+)(-[a-z\\d]+)*$",
                                                                    'hint' => ($v = $state_r['language'] ?? null) ?? 'en',
                                                                    'value' => $v,
                                                                    'lot' => $languages,
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
                                                                'l' => ['skip' => true], // Hide delete button
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
        }
        return $_;
    }
    function asset($_) {
        extract($GLOBALS, \EXTR_SKIP);
        if (!\is_dir($d = \LOT . \DS . 'asset' . \DS . $user->user)) {
            \mkdir($d, 0755, true);
            $_['alert']['success'][] = ['Created folder %s.', '<code>' . \x\panel\from\path($d) . '</code>'];
            $_SESSION['_']['folder'][$d] = 1;
            $_['kick'] = $url->current;
            // Update data
            $GLOBALS['_'] = $_;
        }
        $_ = \x\panel\_error_route_check();
        if (!empty($_['is']['error'])) {
            return $_;
        }
        // You cannot edit or delete your own folder
        \Hook::set('_', function($_) use($d) {
            extract($GLOBALS, \EXTR_SKIP);
            if (\count($_['chop']) < 3 && 'g' === $_['task'] && isset($_['i'])) {
                unset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$d]['tasks']['g']['url']);
                unset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$d]['tasks']['l']['url']);
            }
            if (1 !== $user->status) {
                // Hide parent folder link
                if (\count($_['chop']) < 3 && 'g' === $_['task'] && isset($_['i'])) {
                    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$_['f']]['skip'] = true;
                }
            }
            return $_;
        }, 10.2);
        return $_;
    }
    function user($_) {
        $_ = \x\panel\_error_route_check();
        if (!empty($_['is']['error'])) {
            return $_;
        }
        extract($GLOBALS, \EXTR_SKIP);
        $status = $user->status;
        if (\count($_['chop']) > 1) {
            if (1 !== $status) {
                // XSS Protection
                if ('post' === $_['form']['type']) {
                    // Prevent user(s) from adding a hidden form (or changing the `page[status]` field value) that
                    // defines its `status` through developer tools and such by enforcing the `page[status]` value
                    if (isset($_['form']['lot']['page']['status']) && $status !== $_['form']['lot']['page']['status']) {
                        $_['alert']['error'][] = ['You don\'t have permission to change the %s value.', '<code>status</code>'];
                    }
                    // Also secure up the external `status` data variant
                    if (isset($_['form']['lot']['data']['status']) && $status !== $_['form']['lot']['data']['status']) {
                        $_['alert']['error'][] = ['You don\'t have permission to change the %s value.', '<code>status</code>'];
                    }
                    $_['form']['lot']['page']['status'] = $status;
                }
            }
        }
        return $_;
    }
}