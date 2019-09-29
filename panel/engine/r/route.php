<?php namespace _\lot\x\panel;

function route($lot) {
    if (!\Is::user()) {
        // TODO: Show 404 page to confuse URL guesser
        \Guard::kick("");
    }
    extract($GLOBALS, \EXTR_SKIP);
    $GLOBALS['t'][] = $language->panel;
    $n = \ltrim($_['chop'][0], '_.-');
    $GLOBALS['t'][] = isset($_['path']) ? $language->{$n === 'x' ? 'extension' : $n} : null;
    \State::set([
        'has' => [
            'parent' => \count($_['chop']) > 1,
        ],
        'is' => [
            'error' => false,
            'page' => !isset($_['i']),
            'pages' => isset($_['i'])
        ]
    ]);
    if ($_['task'] === 'g' && (
        !isset($_['f']) ||
        !\is_dir($_['f']) && isset($_['i'])
    )) {
        $this->status(404);
        $this->content(__DIR__ . \DS . 'content' . \DS . '404.php');
    }
    $this->content(__DIR__ . \DS . 'content' . \DS . 'panel.php');
}

\Route::set($_['/'] . '/*', 200, __NAMESPACE__ . "\\route", 20);

\Route::set($_['/'] . '/:task/.state', 200, function($lot, $type) {
    extract($GLOBALS, \EXTR_SKIP);
    if (isset($_['i'])) {
        // Force as item page
        \Guard::kick($url->clean . $url->query . $url->hash);
    }
    if (!\Is::user()) {
        // TODO: Show 404 page to confuse URL guesser
        \Guard::kick("");
    }
    $i18n = \extension_loaded('intl');
    $panels = $paths = $skins = [];
    foreach (\glob(\LOT . \DS . '*', \GLOB_NOSORT | \GLOB_ONLYDIR) as $panel) {
        $n = \ltrim($b = \basename($panel), '_.-');
        $panels['/' . $b] = $language->{$n === 'x' ? 'extension' : $n};
    }
    foreach (\glob(\PAGE . \DS . '*.{archive,draft,page}', \GLOB_NOSORT | GLOB_BRACE) as $path) {
        $paths['/' . \pathinfo($path, \PATHINFO_FILENAME)] = (new \Page($path))->title;
    }
    foreach (\glob(\CONTENT . \DS . '*' . \DS . 'about.page', \GLOB_NOSORT) as $skin) {
        $skins[\basename(\dirname($skin))] = (new \Page($skin))->title;
    }
    \asort($panels);
    \asort($paths);
    \asort($skins);
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
    $GLOBALS['_']['lot'] = require __DIR__ . \DS . 'state' . \DS . 'state.php';
    $GLOBALS['_']['lot']['bar']['lot'][0]['lot']['folder']['url'] = $url . $_['/'] . '/::g::/page/1' . $url->query('&', ['content' => false, 'tab' => false]) . $url->hash;
    $GLOBALS['_']['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot'] = \array_replace_recursive([
        'file' => [
            'icon' => 'M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z',
            'title' => false,
            'description' => $language->site,
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
                            'value' => 'state.php' // No use, added to prevent error of empty name field
                        ],
                        'title' => [
                            'type' => 'Text',
                            'name' => 'state[title]',
                            'alt' => $state->title ?? $language->fieldAltTitle,
                            'value' => $state->title,
                            'width' => true,
                            'stack' => 10
                        ],
                        'description' => [
                            'type' => 'Content',
                            'name' => 'state[description]',
                            'alt' => $language->fieldAltDescription,
                            'value' => $state->description,
                            'width' => true,
                            'stack' => 20
                        ],
                        'skin' => [
                            'type' => 'Combo',
                            'name' => 'state[name]',
                            'value' => $state->name,
                            'lot' => $skins,
                            'stack' => 30
                        ],
                        'path' => [
                            'description' => 'Default home page path.',
                            'type' => 'Combo',
                            'name' => 'state[path]',
                            'value' => $state->path,
                            'lot' => $paths,
                            'stack' => 40
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
                            'title' => $language->home,
                            'description' => 'Default home page path of this control panel.',
                            'type' => 'Combo',
                            'name' => 'state[x][panel][/]',
                            'value' => $state->x->panel->{'/'} ?? null,
                            'lot' => $panels,
                            'stack' => 10
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
                        'locale' => [
                            'active' => $i18n,
                            'description' => $i18n ? 'This value determine the translation for date and time format. Every country has different locale name, and it might be vary on every operating system. Please consult to the administrator or search for thing like &ldquo;PHP locale name for country XYZ&rdquo; with your favorite search engine.' : 'Please enable PHP internationalization extension on your environment.',
                            'type' => 'Text',
                            'name' => 'state[locale]',
                            'alt' => $state->locale,
                            'value' => $state->locale,
                            'stack' => 30
                        ]
                    ],
                    'stack' => 10
                ]
            ],
            'stack' => 30
        ]
    ], $GLOBALS['_']['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot'] ?? []);
    $GLOBALS['t'][] = $language->panel;
    $GLOBALS['t'][] = $language->state;
    \State::set([
        'has' => [
            'parent' => \count($_['chop']) > 1,
        ],
        'is' => [
            'error' => false,
            'page' => true,
            'pages' => false
        ]
    ]);
    $this->content(__DIR__ . \DS . 'content' . \DS . 'panel.php');
}, 10);