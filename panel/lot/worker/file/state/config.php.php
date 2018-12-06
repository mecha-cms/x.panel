<?php

$authors = $languages = $shields = $zones = [];

foreach (glob(USER . DS . '*.page', GLOB_NOSORT) as $v) {
    $k = new User($v);
    $authors['@' . Path::N($v)] = $k->key . ' (' . $k->{'$'} . ')';
}

foreach (glob(LANGUAGE . DS . '*.page', GLOB_NOSORT) as $v) {
    $languages[Path::N($v)] = (new Page($v))->title;
}

foreach (glob(SHIELD . DS . '*' . DS . 'about.page', GLOB_NOSORT) as $v) {
    $shields[basename(dirname($v))] = (new Page($v))->title;
}

call_user_func(function() use(&$zones) {
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
        $tz = new DateTimeZone($timezone);
        $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
    }
    asort($timezone_offsets);
    foreach($timezone_offsets as $zone => $offset) {
        $offset_prefix = $offset < 0 ? '-' : '+';
        $offset_formatted = gmdate('H:i', abs($offset));
        $zones[$zone] = 'GMT' . $offset_prefix . $offset_formatted . ' &#x00B7; ' . strtr($zone, '_', ' ');
    }
});

$key = 'panel.desk.body.tab.file.field.file[?]';
Config::set($key . '[zone]', [
    'type' => 'select',
    'values' => $zones
]);
Config::set($key . '[language]', [
    'type' => 'select',
    'width' => false,
    'values' => $languages
]);
Config::set($key . '[charset].width', false);
Config::set($key . '[direction]', [
    'type' => 'select',
    'width' => false,
    'values' => [
        'ltr' => 'LTR (Left to Right)',
        'rtl' => 'RTL (Right to Left)'
    ]
]);
Config::set($key . '[description]', [
    'type' => 'textarea',
    'width' => true
]);
Config::set($key . '[shield]', [
    'type' => 'select',
    'values' => $shields
]);

Config::reset($key . '[page]');
$defaults = Config::get('page', [], true);
Hook::set('on.ready', function() use($authors, $defaults, $language) {
    if (isset($defaults['author']) && !isset($authors[$defaults['author']])) {
        $authors[$defaults['author']] = $defaults['author'];
    }
    $editors = (array) $language->o_page_editor;
    Config::set('panel.desk.body.tab', [
        'page' => [
            'field' => [
                'file[?][page][title]' => [
                    'key' => 'title',
                    'type' => 'text',
                    'width' => true,
                    'value' => $defaults['title'] ?? null,
                    'stack' => 10
                ],
                'file[?][page][content]' => [
                    'key' => 'content',
                    'type' => 'source',
                    'width' => true,
                    'height' => true,
                    'value' => $defaults['content'] ?? null,
                    'stack' => 10.1
                ],
                'file[?][page][author]' => [
                    'key' => 'author',
                    'type' => 'select',
                    'width' => true,
                    'value' => $defaults['author'] ?? null,
                    'values' => $authors,
                    'kind' => ['select-input'],
                    'stack' => 10.2
                ],
                'file[?][page][type]' => [
                    'key' => 'type',
                    'type' => 'select',
                    'value' => $defaults['type'] ?? null,
                    'values' => (array) $language->o_page_type,
                    'kind' => ['select-input'],
                    'stack' => 10.3
                ],
                'file[?][page][editor]' => $editors ? [
                    'key' => 'editor',
                    'type' => 'select',
                    'value' => $defaults['editor'] ?? null,
                    'values' => concat(["" => ""], $editors),
                    'stack' => 10.4
                ] : null,
            ],
            'stack' => 10.1
        ]
    ]);
}, .2);

// You can’t delete this file
Config::set('panel.desk.footer.tool.r.x', true);