<?php

if ($c === 'g' && !$panel->file || HTTP::get('view') === 'file') {
    return;
}

$f = LOT . DS . $id . DS . $panel->path;
if ($c === 's' && is_file($f)) {
    Guardian::kick(str_replace('::s::', '::g::', $url->current . $url->query));
}

$page = new Page(is_file($f) ? $f : null, extend([
    'author' => null,
    'content' => null,
    'description' => null,
    'link' => null,
    'time' => null,
    'title' => null,
    'type' => null
], (array) Config::get($id, [], true)), false);

// Remove folder and blob tab(s)
Config::reset('panel.desk.body.tabs.folder');
Config::reset('panel.desk.body.tabs.blob');
// Remove all submit button(s)
Config::reset('panel.desk.footer.tools');

// Modify file tab field(s)
Config::set('panel.$.slug', ['page[title]:slug']);
Config::set('panel.desk.body.tabs.file', [
    'title' => $language->{$id},
    'fields' => [
        'file[content]' => null,
        'name' => null,
        'page[title]' => [
            'key' => 'title',
            'type' => 'text',
            'value' => $page->title,
            'width' => true,
            'stack' => 10
        ],
        'slug' => [
            'type' => 'text',
            'pattern' => '^[a-z\\d]+(-[a-z\\d]+)*$',
            'value' => $page->slug,
            'width' => true,
            'stack' => 10.1
        ],
        'page[content]' => [
            'key' => 'content',
            'type' => 'editor',
            'value' => $page->content,
            'width' => true,
            'height' => true,
            'stack' => 10.2
        ],
        'page[description]' => [
            'key' => 'description',
            'type' => 'textarea',
            'value' => $page->description,
            'width' => true,
            'stack' => 10.3
        ],
        'page[type]' => [
            'key' => 'type',
            'type' => 'select',
            'kind' => ['select-input'],
            'value' => $page->type,
            'stack' => 10.4
        ],
        'file[consent]' => [
            'type' => 'hidden',
            'hidden' => false,
            'value' => '0600'
        ]
    ],
    'stack' => 10 // WHY?!
]);

// Add setting(s) tab for page with children
if ($c === 'g' && glob(Path::F($page->path) . DS . '*.{draft,page,archive}', GLOB_BRACE | GLOB_NOSORT)) {
    Config::set('panel.desk.body.tabs.config', [
        'title' => $language->settings,
        'fields' => [
            'page[sort][0]' => [
                'key' => 'order',
                'title' => $language->sort[0],
                'type' => 'radio[]',
                'value' => $page->sort[0],
                'values' => [
                    '-1' => 'Z &#x2192; A',
                    '1' => 'A &#x2192; Z'
                ],
                'stack' => 10
            ],
            'page[sort][1]' => [
                'key' => 'by',
                'title' => $language->sort[1],
                'type' => 'text',
                'value' => $page->sort[1],
                'placeholder' => $page->sort[1] ?: 'time',
                'stack' => 10.1
            ]
        ],
        'stack' => 10.01
    ]);
}

// Add custom field(s) tab
Config::set('panel.desk.body.tabs.data', [
    'title' => $language->datas,
    'fields' => [
        'page[link]' => [
            'key' => 'link',
            'type' => 'text',
            'pattern' => '^([\\/?#]\\S+|\\/\\/\\S+|https?:\\/\\/\\S+)$',
            'value' => $page->link,
            'placeholder' => '&#x200C;' . $url->protocol . '&#x200C;' . $url->host . '&#x200C;',
            'width' => true,
            'stack' => 10
        ],
        'data[time]' => $c === 'g' ? [
            'key' => 'time',
            'type' => 'text',
            'pattern' => '^[1-9]\\d{3,}-(0\\d|1[0-2])-(0\\d|[1-2]\\d|3[0-1]) ([0-1]\\d|2[0-4])(:([0-5]\\d|60)){2}$',
            'value' => $page->time,
            'placeholder' => $page->time ?: date(DATE_WISE),
            'stack' => 10.1
        ] : null,
        '!:' => $c === 'g' ? [
            'key' => 'datas',
            'type' => 'source',
            'width' => true,
            'placeholder' => 'key: value',
            'stack' => 10.2
        ] : null,
        '!+' => $c === 'g' ? [
            'key' => 'datas',
            'type' => 'content',
            'stack' => 10.3
        ] : null
    ],
    'stack' => 10.1
]);

Hook::set('on.ready', function() use($c, $language, $page, $panel, $token, $url) {
    $id = $panel->id;
    $path = trim($panel->id . '/' . $panel->path, '/');
    Config::set('panel.nav.search', [
        'content' => fn\panel\nav_li_search([
            'title' => $language->{$id},
            'path' => Path::F($path) . '/1'
        ], $id)
    ]);
    $pref = 'panel.desk.body.tabs.file.fields.';
    // Add tag(s) field
    if (Extend::exist('tag')) {
        Config::set($pref . 'tags', [
            'type' => 'text',
            'pattern' => '^([a-z\\d]+([ -][a-z\\d]+)*)(\\s*,\\s*[a-z\\d]+([ -][a-z\\d]+)*)*$',
            'kind' => ['tags'],
            'value' => $page->query,
            'width' => true,
            'stack' => 10.31
        ]);
    }
    // Add art direction tab
    if (Plugin::exist('art')) {
        Config::set('panel.desk.body.tabs.art', [
            'fields' => [
                'data[css]' => [
                    'title' => '<abbr title="Cascading Style Sheet">CSS</abbr>',
                    'type' => 'source',
                    'value' => $page->css,
                    'width' => true,
                    'height' => true,
                    'stack' => 10
                ],
                'data[js]' => [
                    'title' => '<abbr title="JavaScript">JS</abbr>',
                    'type' => 'source',
                    'value' => $page->js,
                    'width' => true,
                    'height' => true,
                    'stack' => 10.1
                ]
            ],
            'stack' => 10.2
        ]);
    }
    // Other(s)
    $types = (array) $language->o_page_types;
    if ($page->type && !isset($types[$page->type])) {
        $types[$page->type] = $page->type;
    }
    Config::set($pref . 'page[type].values', $types);
    // Add data(s) field
    if ($c === 'g') {
        $datas = glob(Path::F($page->path) . DS . '*.data', GLOB_NOSORT);
        $removes = [];
        foreach ((array) Config::get('panel.desk.body.tabs', [], true) as $v) {
            if (!isset($v['fields'])) continue;
            foreach ($v['fields'] as $kk => $vv) {
                if (strpos($kk, 'data[') === 0 || strpos($kk, 'page[') === 0) {
                    $removes[substr(explode(']', $kk)[0], 5) . '.data'] = 1;
                }
            }
        }
        foreach ($datas as $k => $v) {
            if (isset($removes[basename($v)])) {
                unset($datas[$k]);
            }
        }
        $headers = Page::apart(file_get_contents($page->path));
        $query = [
            'query' => [
                'tab' => false,
                'view' => 'data',
                'x' => Path::X($url->path)
            ]
        ];
        Config::set('panel.$.file.tools', [
            'g' => $query,
            'r' => $query
        ]);
        $pref = 'panel.desk.body.tabs.data.fields.';
        foreach ($headers as $k => $v) {
            if (isset($removes[$k . '.data'])) {
                unset($headers[$k]);
            }
        }
        if ($headers) {
            Config::set($pref . '!:.value', To::YAML($headers));
        } else {
            Config::set($pref . '!:.hidden', true);
        }
        Config::set($pref . '!+.value', ($datas ? fn\panel\files($datas, 'datas') : "") . '<p>' . fn\panel\a([
            'title' => $language->create,
            'icon' => [['M2,16H10V14H2M18,14V10H16V14H12V16H16V20H18V16H22V14M14,6H2V8H14M14,10H2V12H14V10Z']],
            'c' => 's',
            'url' => str_replace('::g::', '::s::', Path::F($url->path)),
            'query' => $query['query'],
            'kind' => ['button', 'text']
        ], 'a-data') . '</p>');
        // Change main add icon
        Config::set('panel.nav.s.icon', [['M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z']]);
    }
}, 1);

// Re-create submit button(s)
$x = $page->state;
$buttons = [
    'page' => 'publish',
    'draft' => 'save',
    'archive' => 'archive'
];

if ($c === 'g') {
    unset($buttons[$x]);
    $buttons = [$x => 'update'] + $buttons;
}

$i = 0;
foreach ($buttons as $k => $v) {
    $buttons[$k] = [
        'title' => $language->{$v},
        'name' => 'x',
        'type' => 'submit',
        'value' => $k,
        'stack' => 10 + $i
    ];
    $i += .1;
}

if ($c === 'g') {
    $buttons['trash'] = [
        'title' => $language->delete,
        'name' => 'a',
        'type' => 'submit',
        'value' => -2,
        'stack' => 10 + $i
    ];
}

Config::set('panel.desk.footer.tools', $buttons);