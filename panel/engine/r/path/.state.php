<?php

// Sanitize form data
if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['state'])) {
    $_POST['state']['title'] = _\lot\x\panel\h\w($_POST['state']['title'] ?? "");
    $_POST['state']['description'] = _\lot\x\panel\h\w($_POST['state']['description'] ?? "");
    $_POST['state']['charset'] = strip_tags($_POST['state']['charset'] ?? 'utf-8');
    $_POST['state']['language'] = strip_tags($_POST['state']['language'] ?? 'en');
    $user_state = require X . DS . 'user' . DS . 'state.php';
    $panel_state = require X . DS . 'panel' . DS . 'state.php';
    $core_state = require ROOT . DS . 'state.php';
    $default = $user_state['guard']['path'] ?? $panel_state['guard']['path'] ?? $core_state['x']['user']['guard']['path'] ?? $core_state['x']['panel']['guard']['path'] ?? "";
    $default = '/' . trim($default, '/') . '/';
    if (!empty($_POST['state']['x']['user']['guard']['path'])) {
        if ($secret = To::kebab(trim($_POST['state']['x']['user']['guard']['path'], '/'))) {
            $_POST['state']['x']['user']['guard']['path'] = '/' . $secret;
            $default = '/' . $secret . '/';
        } else {
            unset($_POST['state']['x']['user']['guard']['path']);
        }
    }
    if ($_['/'] !== $default) {
        $GLOBALS['_']['/'] = $default;
        $GLOBALS['_']['alert']['info'][] = ['Your log-in URL has been changed to %s', ['<code>' . $url . substr($default, 0, -1) . '</code>']];
    }
}

if (1 !== $user['status'] || 'g' !== $_['task']) {
    if (Is::user()) {
        Alert::error(i('Permission denied for your current user status: %s', '<code>' . $user['status'] . '</code>') . '<br><small>' . $url->current . '</small>');
        Guard::kick($url . $_['/'] . '::g::' . $_['state']['path'] . '/1' . $url->query('&', ['layout' => false, 'tab' => false]) . $url->hash);
    } else {
        Guard::kick("");
    }
}

$GLOBALS['_']['layout'] = $_['layout'] = 'state';

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
    foreach (glob(PAGE . DS . '*.{archive,page}', GLOB_NOSORT | GLOB_BRACE) as $path) {
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
    $GLOBALS['_']['lot'] = array_replace_recursive(require __DIR__ . DS . '..' . DS . 'state' . DS . 'state.php', $_['lot']);
    $GLOBALS['_']['lot']['bar']['lot'][0]['lot']['folder']['hidden'] = true;
    $GLOBALS['_']['lot']['bar']['lot'][0]['lot']['link']['hidden'] = false;
    $GLOBALS['_']['lot']['bar']['lot'][0]['lot']['link']['url'] = $url . $_['/'] . '::g::' . $_['state']['path'] . '/1' . $url->query('&', ['layout' => false, 'tab' => false]) . $url->hash;
    $GLOBALS['_']['lot']['bar']['lot'][0]['lot']['s']['hidden'] = true;
    $GLOBALS['_']['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot'] = array_replace_recursive([
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
                            'alt' => $state->title ?? 'Title Goes Here',
                            'value' => $state->title,
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
                            'alt' => $_['state']['guard']['path'],
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
                            'alt' => ($v = $site->charset) ?? 'utf-8',
                            'value' => $v,
                            'stack' => 30
                        ],
                        'language' => [
                            'description' => 'This value does not determine the I18N system on your site unless you want to make an I18N extension that depends on this value.',
                            'type' => 'Text',
                            'name' => 'state[language]',
                            'alt' => ($v = $site->language) ?? 'en',
                            'value' => $v,
                            'stack' => 40
                        ]
                    ],
                    'stack' => 10
                ]
            ],
            'stack' => 30
        ]
    ], $GLOBALS['_']['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot'] ?? []);
    $GLOBALS['_']['lot']['desk']['lot']['form']['lot'][2]['lot']['fields']['lot'][0]['lot']['tasks']['lot']['s']['description'] = ['Save to %s', ".\\state.php"];
    $GLOBALS['_']['lot']['bar']['lot'][0]['lot']['search']['hidden'] = true; // Hide search form
    $GLOBALS['_']['lot']['desk']['lot']['form']['lot'][2]['lot']['fields']['lot'][0]['lot']['tasks']['lot']['l']['hidden'] = true; // Hide delete button
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