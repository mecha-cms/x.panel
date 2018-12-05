<?php

if ($c === 'g' && !$panel->file || HTTP::get('view') === 'file') {
    return;
}

$file = $panel->file ?: $panel->folder;
if ($c === 's' && $is_file = is_file($file)) {
    Guardian::kick(str_replace('::s::', '::g::', $url->current . $url->query));
}

$page = new Page($is_file ? $file : null, extend([
    'author' => null,
    'content' => null,
    'description' => null,
    'link' => null,
    'time' => null,
    'title' => null,
    'type' => Config::get('page.type', 'HTML') // Inherit `page.type` state or `HTML`
], (array) Config::get($id, [], true)), false);

// Remove folder and blob tab(s)
Config::reset('panel.desk.body.tab.folder');
Config::reset('panel.desk.body.tab.blob');
// Remove all submit button(s)
Config::reset('panel.desk.footer.tool');

// Modify file tab field(s)
Config::set('panel.+.slug', ['page[title]:slug']);
Config::set('panel.desk.body.tab.file', [
    'title' => $language->{str_replace('.', "\\.", $id)},
    'field' => [
        'file[content]' => null,
        'name' => null,
        'page[title]' => [
            'key' => 'title',
            'type' => 'text',
            'value' => $page->title,
            'placeholder' => $c === 's' ? $language->field_hint_page_title : null,
            'width' => true,
            'stack' => 10
        ],
        'slug' => [
            'type' => 'text',
            'pattern' => '^[a-z\\d]+(-[a-z\\d]+)*$',
            'value' => $page->slug,
            'placeholder' => $c === 's' ? $language->field_hint_slug : null,
            'width' => true,
            'stack' => 10.1
        ],
        'page[content]' => [
            'key' => 'content',
            'type' => 'source',
            'value' => $page->content,
            'placeholder' => $language->field_hint_file_content,
            'width' => true,
            'height' => true,
            'stack' => 10.2
        ],
        'page[description]' => [
            'key' => 'description',
            'type' => 'textarea',
            'value' => $page->description,
            'placeholder' => $language->field_hint_page_description__($language->{str_replace('.', "\\.", $id)}),
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
        'page[author]' => [
            'key' => 'author',
            'type' => 'hidden',
            'value' => $c === 's' ? $user->key : ($page->author->key ?? $page->author) . "",
            'stack' => 0
        ]
    ],
    'stack' => 10 // WHY?!
]);

// Add setting(s) tab for page with children
if ($c === 'g' && glob(Path::F($page->path) . DS . '*.{draft,page,archive}', GLOB_BRACE | GLOB_NOSORT)) {
    Config::set('panel.desk.body.tab.config', [
        'title' => false,
        'icon' => [$svg['state']],
        'field' => [
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
            ],
            'page:view' => [
                'key' => 'view',
                'type' => 'radio[]',
                'value' => File::exist([
                    Path::F($file) . DS . '$.page',
                    Path::F($file) . DS . '$.archive'
                ]) ? 'page' : 'pages',
                'values' => (array) $language->o_page_view,
                'stack' => 10.2
            ]
        ],
        'stack' => 9.9
    ]);
}

// Add custom field(s) tab
Config::set('panel.desk.body.tab.data', [
    'title' => $language->datas,
    'field' => [
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
            'value' => $page->time . "",
            'placeholder' => $page->time . "" ?: date(DATE_WISE),
            'stack' => 10.1
        ] : null,
        '!:' => $c === 'g' ? [
            'key' => 'datas',
            'type' => 'source',
            'syntax' => 'yaml',
            'width' => true,
            'placeholder' => $language->{'field_hint_:'},
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

Hook::set('on.ready', function() use($c, $file, $id, $language, $page, $state, $r, $url) {
    Config::set('panel.nav.search', [
        'content' => fn\panel\nav_li_search([
            'title' => $language->{str_replace('.', "\\.", $id)},
            'path' => Path::R(Path::F($file), LOT, '/') . '/1'
        ], $id)
    ]);
    // Add image field
    if (Extend::exist('image')) {
        $image = $page->image;
        Config::set('panel.desk.body.tab.image', [
            'field' => $image ? [
                'image' => [
                    'type' => 'content',
                    'value' => '<p>' . HTML::a(HTML::img($image, basename($image)), $r . '/::g::/' . Path::R(To::path($image), LOT, '/'), true) . '<br>' . Form::toggle('page[image:x]', 1, false, $language->remove) . '</p>',
                    'stack' => 10
                ],
                'page[image]' => [
                    'key' => 'image',
                    'type' => 'hidden',
                    'value' => $image,
                    'stack' => 0
                ]
            ] : [
                'image' => [
                    'type' => 'blob',
                    'stack' => 10
                ],
                'image[width]' => [
                   'key' => 'width',
                   'type' => 'number',
                   'range' => [72, 1600],
                   'value' => $state['page']['image']['width'] ?? null,
                   'stack' => 10.1
                ],
                'image[height]' => [
                   'key' => 'height',
                   'type' => 'number',
                   'range' => [72, 1600],
                   'value' => $state['page']['image']['height'] ?? null,
                   'stack' => 10.2
                ]
            ],
            'stack' => 10.09
        ]);
    }
    // Add tag(s) field
    if (Extend::exist('tag')) {
        Config::set('panel.desk.body.tab.file.field.tags', [
            'type' => 'text',
            'pattern' => '^([a-z\\d]+([ -][a-z\\d]+)*)(\\s*,\\s*[a-z\\d]+([ -][a-z\\d]+)*)*$',
            'kind' => ['tags'],
            'value' => implode(', ', (array) ($page->query ?? (new Page($file))->query)),
            'placeholder' => $language->field_hint_page_query,
            'width' => true,
            'stack' => 10.31
        ]);
    }
    // Add art direction tab
    if (Plugin::exist('art')) {
        Config::set('panel.desk.body.tab.art', [
            'field' => [
                'data[css]' => [
                    'key' => 'css',
                    'title' => '<abbr title="Cascading Style Sheet">CSS</abbr>',
                    'type' => 'source',
                    'value' => $page->css,
                    'placeholder' => $language->field_hint_page_css,
                    'width' => true,
                    'height' => true,
                    'stack' => 10
                ],
                'data[js]' => [
                    'key' => 'js',
                    'title' => '<abbr title="JavaScript">JS</abbr>',
                    'type' => 'source',
                    'value' => $page->js,
                    'placeholder' => $language->field_hint_page_js,
                    'width' => true,
                    'height' => true,
                    'stack' => 10.1
                ]
            ],
            'stack' => 10.2
        ]);
    }
    // Other(s)
    $types = (array) $language->o_page_type;
    if ($page->type && !isset($types[$page->type])) {
        $types[$page->type] = $page->type;
    }
    Config::set('panel.desk.body.tab.file.field.page[type].values', $types);
    // Add data(s) field
    if ($c === 'g') {
        $datas = glob(Path::F($file) . DS . '*.data', GLOB_NOSORT);
        $removes = [];
        foreach ((array) Config::get('panel.desk.body.tab', [], true) as $v) {
            if (!isset($v['field'])) continue;
            foreach ($v['field'] as $kk => $vv) {
                $kk = ltrim($kk, '.!*');
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
        $headers = Page::apart(file_get_contents($file));
        $query = [
            'query' => [
                'tab' => false,
                'view' => 'data',
                'x' => Path::X($url->path)
            ]
        ];
        Config::set('panel.+.file.tool', [
            'g' => $query,
            'r' => $query
        ]);
        foreach ($headers as $k => $v) {
            if (isset($removes[$k . '.data'])) {
                unset($headers[$k]);
            }
        }
        if ($headers) {
            Config::set('panel.desk.body.tab.data.field.!:.value', To::YAML($headers));
        } else {
            Config::set('panel.desk.body.tab.data.field.!:.hidden', true);
        }
        Config::set('panel.desk.body.tab.data.field.!+.value', ($datas ? fn\panel\files($datas, 'datas') : "") . '<p>' . fn\panel\a([
            'title' => $language->create,
            'icon' => [['M2,16H10V14H2M18,14V10H16V14H12V16H16V20H18V16H22V14M14,6H2V8H14M14,10H2V12H14V10Z']],
            'c' => 's',
            'url' => str_replace('::g::', '::s::', Path::F($url->path)),
            'query' => $query['query'],
            'kind' => ['button', 'text']
        ], 'a-data') . '</p>');
        // Change main add icon
        Config::set('panel.nav.s', [
            'description' => $language->new__($language->{str_replace('.', "\\.", $id)}),
            'icon' => [['M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z']],
            'query' => [
                'tab' => false,
                'tabs' => false
            ]
        ]);
    }
}, .2);

// Re-create submit button(s)
$x = $page->state;
$buttons = [
    'page' => 'publish',
    'draft' => 'save',
    'archive' => $c === 'g' ? 'do_archive' : null
];

if ($c === 'g') {
    unset($buttons[$x]);
    $buttons = [$x => 'update'] + $buttons;
}

$i = 0;
foreach ($buttons as $k => $v) {
    if (!isset($v)) continue;
    $buttons[$k] = [
        'title' => $language->{$v},
        'name' => 'x',
        'type' => 'submit',
        'value' => $k,
        'stack' => 10 + $i
    ];
    $i += .1;
}

// Only user with status `1` that has delete access
if ($c === 'g' && $user->status === 1) {
    $buttons['trash'] = [
        'title' => $language->delete,
        'name' => 'a',
        'type' => 'submit',
        'value' => -2,
        'stack' => 10 + $i
    ];
}

Config::set('panel.desk.footer.tool', $buttons);