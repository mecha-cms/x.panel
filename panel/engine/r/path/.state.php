<?php

// Force layout to `state`
$GLOBALS['_']['layout'] = $_['layout'] = 'state';

// Sanitize form data
Hook::set('do.state.get', function($_, $lot) {
    if ('POST' !== $_SERVER['REQUEST_METHOD'] || !isset($lot['state'])) {
        return $_;
    }
    extract($GLOBALS, EXTR_SKIP);
    $lot['state']['title'] = _\lot\x\panel\h\w($lot['state']['title'] ?? "");
    $lot['state']['description'] = _\lot\x\panel\h\w($lot['state']['description'] ?? "");
    $lot['state']['charset'] = strip_tags($lot['state']['charset'] ?? 'utf-8');
    $lot['state']['language'] = strip_tags($lot['state']['language'] ?? 'en');
    $user_state = require LOT . DS . 'x' . DS . 'user' . DS . 'state.php';
    $panel_state = require LOT . DS . 'x' . DS . 'panel' . DS . 'state.php';
    $core_state = require ROOT . DS . 'state.php';
    $default = $user_state['guard']['path'] ?? $panel_state['guard']['path'] ?? $core_state['x']['user']['guard']['path'] ?? $core_state['x']['panel']['guard']['path'] ?? "";
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
        if ($default === $panel_state['guard']['path'] . '/') {
            $_['alert']['info'][] = ['Your log-in URL has been restored to %s', '<code>' . $url . $user_state['path'] . '</code>'];
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

Route::set($_['/'] . '\:\:g\:\:/.state', 200, function() {
    extract($GLOBALS, EXTR_SKIP);
    if (isset($_['i'])) {
        // Force as item page
        Guard::kick($url->clean . $url->query . $url->hash);
    }
    $panes = $paths = [];
    foreach (glob(LOT . DS . '*', GLOB_NOSORT | GLOB_ONLYDIR) as $panel) {
        $n = basename($panel);
        if (false !== strpos('_.-', $n[0])) {
            continue;
        }
        $panes['/' . $n] = 'x' === $n ? 'Extension' : ucfirst($n);
    }
    foreach (glob(LOT . DS . 'page' . DS . '*.{archive,page}', GLOB_NOSORT | GLOB_BRACE) as $path) {
        $paths['/' . pathinfo($path, PATHINFO_FILENAME)] = S . (new Page($path))->title . S;
    }
    asort($panes);
    asort($paths);
    $zones = Cache::hit(__FILE__, function() {
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
            $timezones = array_merge($timezones, \DateTimeZone::listIdentifiers($region));
        }
        foreach ($timezones as $timezone) {
            $tz = new \DateTimeZone($timezone);
            $timezone_offsets[$timezone] = $tz->getOffset(new \DateTime);
        }
        asort($timezone_offsets);
        foreach ($timezone_offsets as $zone => $offset) {
            $offset_prefix = $offset < 0 ? '-' : '+';
            $offset_formatted = gmdate('H:i', abs($offset));
            $zones[$zone] = 'GMT' . $offset_prefix . $offset_formatted . ' (' . strtr($zone, '_', ' ') . ')';
        }
        return $zones;
    }, '1 year');
    $_['lot'] = array_replace_recursive(require __DIR__ . DS . '..' . DS . 'state' . DS . 'state.php', $_['lot']);
    $_['lot']['bar']['lot'][0]['lot']['folder']['hidden'] = true;
    $_['lot']['bar']['lot'][0]['lot']['link']['hidden'] = false;
    $_['lot']['bar']['lot'][0]['lot']['link']['url'] = $url . $_['/'] . '::g::' . $_['state']['path'] . '/1' . $url->query('&', ['layout' => false, 'tab' => false]) . $url->hash;
    $_['lot']['bar']['lot'][0]['lot']['s']['hidden'] = true;
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot'] = array_replace_recursive([
        'file' => [
            'icon' => 'M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z',
            'title' => false,
            'description' => 'Site',
            'name' => 'site',
            'lot' => [
                'fields' => [
                    // type: Fields
                    'lot' => [
                        '0' => [
                            'type' => 'Hidden',
                            'name' => 'path',
                            'value' => '/../state.php'
                        ],
                        '1' => [
                            'type' => 'Hidden',
                            'name' => 'file[name]',
                            // No use. This field was added just to remove error message of
                            // empty `file[name]` field generated by `_\lot\x\panel\task\get\file()`
                            'value' => 'state.php'
                        ],
                        'content' => ['hidden'=> true],
                        'name' => ['hidden' => true],
                        'title' => [
                            'type' => 'Text',
                            'name' => 'state[title]',
                            'alt' => ($v = $state->title) ?? 'Title Goes Here',
                            'value' => $v,
                            'width' => true,
                            'stack' => 10
                        ],
                        'description' => [
                            'type' => 'Content',
                            'name' => 'state[description]',
                            'alt' => 'Description goes here...',
                            'value' => $state->description,
                            'width' => true,
                            'stack' => 20
                        ],
                        'path' => [
                            'title' => 'Home',
                            'description' => 'Choose default page that will open in the home page.',
                            'type' => 'Combo',
                            'name' => 'state[path]',
                            'value' => $state->path,
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
                            'value' => $state->x->panel->path ?? null,
                            'lot' => $panes,
                            'stack' => 10
                        ],
                        'key' => [
                            'description' => 'Set custom log-in path.',
                            'type' => 'Text',
                            'name' => 'state[x][user][guard][path]',
                            'pattern' => "^/([a-z\\d]+)(-[a-z\\d]+)*$",
                            'alt' => $state->x->user->path ?? null,
                            'value' => $state->x->user->guard->path ?? null,
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
                            'value' => $state->zone,
                            'lot' => $zones,
                            'width' => true,
                            'stack' => 10
                        ],
                        'direction' => [
                            'type' => 'Item',
                            'name' => 'state[direction]',
                            'value' => $state->direction,
                            'lot' => [
                                'ltr' => '<abbr title="Left to Right">LTR</abbr>',
                                'rtl' => '<abbr title="Right to Left">RTL</abbr>'
                            ],
                            'stack' => 20
                        ],
                        'charset' => [
                            'type' => 'Text',
                            'name' => 'state[charset]',
                            'alt' => ($v = $state->charset) ?? 'utf-8',
                            'value' => $v,
                            'stack' => 30
                        ],
                        'language' => [
                            'description' => 'This value does not determine the I18N system on your site unless you want to make an I18N extension that depends on this value.',
                            'type' => 'Text',
                            'name' => 'state[language]',
                            'pattern' => "^([a-z\\d]+)(-[a-z\\d]+)*$",
                            'alt' => ($v = $state->language) ?? 'en',
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
                            'value' => $state->email,
                            'stack' => 10
                        ]
                    ],
                    'stack' => 10
                ]
            ],
            'stack' => 40
        ]
    ], $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot'] ?? []);
    $_['lot']['desk']['lot']['form']['lot'][2]['lot']['fields']['lot'][0]['lot']['tasks']['lot']['s']['description'] = ['Save to %s', ".\\state.php"];
    $_['lot']['bar']['lot'][0]['lot']['search']['hidden'] = true; // Hide search form
    $_['lot']['desk']['lot']['form']['lot'][2]['lot']['fields']['lot'][0]['lot']['tasks']['lot']['l']['hidden'] = true; // Hide delete button
    $GLOBALS['_'] = $_;
    $GLOBALS['t'][] = i('Panel');
    $GLOBALS['t'][] = i('State');
    State::set([
        'has' => [
            'parent' => count($_['chops']) > 1,
        ],
        'is' => [
            'error' => false,
            'page' => true,
            'pages' => false
        ]
    ]);
    $this->view('panel');
}, 10);
