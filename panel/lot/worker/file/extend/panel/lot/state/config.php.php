<?php

$redirects = $skins = [];

foreach (glob(LOT . DS . '*', GLOB_NOSORT | GLOB_ONLYDIR) as $v) {
    $redirects[$v = basename($v)] = $language->{str_replace('.', "\\.", $v)};
}

foreach (glob(EXTEND . DS . $chops[0] . DS . 'lot' . DS . 'asset' . DS . 'css' . DS . 'panel' . DS . '*.min.css', GLOB_NOSORT) as $v) {
    $skins[basename($v, '.min.css')] = basename($v);
}
$skins[""] = "";

Config::set('panel.desk.body.tab.file.field.file[?][$]', [
    'title' => $language->home,
    'type' => 'select',
    'values' => $redirects
]);

$states = [];

foreach (['data', 'file', 'page', 'skin', 'style'] as $v) {
    $states[$v] = a(e(Config::get('panel.desk.body.tab.file.field.file[?][' . $v . '].value', null, true)));
    Config::reset('panel.desk.body.tab.file.field.file[?][' . $v . ']');
}

$fonts = [];
if ($google_fonts = File::exist(EXTEND . DS . $chops[0] . DS . 'lot' . DS . 'state' . DS . 'google-fonts.php')) {
    foreach (require $google_fonts as $v) {
        $fonts[$v] = $v;
    }
}
$fonts['0'] = "";
Config::set('panel.desk.body.tab.skin', [
    'field' => [
        'file[?][skin]' => [
            'key' => 'skin',
            'type' => 'select',
            'value' => $states['skin'] ?? null,
            'values' => $skins,
            'stack' => 10
        ],
        'file[?][style][fonts][0]' => [
            'title' => $language->font_body,
            'type' => count($fonts) > 1 ? 'select' : 'text',
            'value' => $states['style']['fonts'][0] ?? null,
            'values' => $fonts,
            'kind' => ['select-input'],
            'stack' => 10.1
        ],
        'file[?][style][fonts][1]' => [
            'title' => $language->font_header,
            'type' => count($fonts) > 1 ? 'select' : 'text',
            'value' => $states['style']['fonts'][1] ?? null,
            'values' => $fonts,
            'kind' => ['select-input'],
            'stack' => 10.2
        ],
        'file[?][style][fonts][2]' => [
            'title' => $language->font_quote,
            'type' => count($fonts) > 1 ? 'select' : 'text',
            'value' => $states['style']['fonts'][2] ?? null,
            'values' => $fonts,
            'kind' => ['select-input'],
            'stack' => 10.3
        ],
        'file[?][style][fonts][3]' => [
            'title' => $language->font_code,
            'type' => count($fonts) > 1 ? 'select' : 'text',
            'value' => $states['style']['fonts'][3] ?? null,
            'values' => $fonts,
            'kind' => ['select-input'],
            'stack' => 10.4
        ],
        'file[?][style][width]' => [
            'title' => $language->width_editor,
            'type' => 'number',
            'range' => [600, 1280],
            'value' => $states['style']['width'] ?? null,
            'stack' => 10.5
        ]
    ],
    'stack' => 10.1
]);

Config::set('panel.desk.body.tab.view-file', [
    'title' => $language->files,
    'field' => [
        'file[?][file][chunk]' => [
            'key' => 'chunk',
            'type' => 'number',
            'range' => [1, 100],
            'value' => $states['file']['chunk'] ?? null,
            'stack' => 10
        ],
        'file[?][file][kin]' => [
            'key' => 'kin',
            'type' => 'number',
            'range' => [1, 5],
            'value' => $states['file']['kin'] ?? null,
            'stack' => 10.1
        ]
    ],
    'stack' => 10.2
]);

$image_feature = Extend::exist('image');
$_asset = str_replace(ROOT, '.', ASSET) . DS;
$_date = explode('.', date('Y.m.d.H.i.s'));
$_extension = 'jpg';
$_hash = Guardian::hash();
$_id = sprintf('%u', time());
$_name = To::slug($language->image);
$_uid = uniqid();
Config::set('panel.desk.body.tab.view-page', [
    'title' => $language->pages,
    'field' => [
        'file[?][page][chunk]' => [
            'key' => 'chunk',
            'type' => 'number',
            'range' => [1, 100],
            'value' => $states['page']['chunk'] ?? null,
            'stack' => 10
        ],
        'file[?][page][kin]' => [
            'key' => 'kin',
            'type' => 'number',
            'range' => [1, 5],
            'value' => $states['page']['kin'] ?? null,
            'stack' => 10.1
        ],
        'file[?][page][snippet]' => [
            'key' => 'snippet',
            'type' => 'number',
            'range' => [50, 300],
            'value' => $states['page']['snippet'] ?? null,
            'stack' => 10.2
        ],
        'file[?][page][sort][0]' => [
            'key' => 'order',
            'title' => $language->sort[0],
            'type' => 'radio[]',
            'value' => $states['page']['sort'][0] ?? 1,
            'values' => [
               '-1' => 'Z &#x2192; A',
                '1' => 'A &#x2192; Z'
            ],
            'stack' => 10.3
        ],
        'file[?][page][sort][1]' => [
            'key' => 'by',
            'title' => $language->sort[1],
            'type' => 'text',
            'value' => $states['page']['sort'][1] ?? null,
            'placeholder' => $states['page']['sort'][1] ?? 'time',
            'stack' => 10.4
        ],
        'file[?][page][image][width]' => $image_feature ? [
            'key' => 'width',
            'title' => $language->page_image_width,
            'description' => $language->field_description_page_image_width,
            'type' => 'number',
            'range' => [72, 1600],
            'value' => $states['page']['image']['width'] ?? null,
            'stack' => 10.5
        ] : null,
        'file[?][page][image][height]' => $image_feature ? [
            'key' => 'height',
            'title' => $language->page_image_height,
            'description' => $language->field_description_page_image_height,
            'type' => 'number',
            'range' => [72, 1600],
            'value' => $states['page']['image']['height'] ?? null,
            'stack' => 10.6
        ] : null,
        'file[?][page][image][directory]' => [
            'key' => 'directory',
            'title' => $language->page_image_directory,
            'description' => $language->field_description_page_image_directory('<code>' . rtrim($_asset, DS) . '</code>'),
            'type' => 'select',
            'value' => $states['page']['image']['directory'] ?? null,
            'values' => [
                // None
                "" => rtrim($_asset, DS),
                // Extension
                '%{extension}%' => $_asset . $_extension,
                // Extension + Time
                '%{extension}%/%{date.year}%' => $_asset . $_extension . DS . $_date[0],
                '%{extension}%/%{date.year}%-%{date.month}%' => $_asset . $_extension . DS . $_date[0] . '-' . $_date[1],
                '%{extension}%/%{date.year}%-%{date.month}%-%{date.day}%' => $_asset . $_extension . DS . $_date[0] . '-' . $_date[1] . '-' . $_date[2],
                '%{extension}%/%{date.year}%/%{date.month}%' => $_asset . $_extension . DS . $_date[0] . DS . $_date[1],
                '%{extension}%/%{date.year}%/%{date.month}%/%{date.day}%' => $_asset . $_extension . DS . $_date[0] . DS . $_date[1] . DS . $_date[2],
                '%{date.year}%' => $_asset . $_date[0],
                '%{date.year}%-%{date.month}%' => $_asset . $_date[0] . '-' . $_date[1],
                '%{date.year}%-%{date.month}%-%{date.day}%' => $_asset . $_date[0] . '-' . $_date[1] . '-' . $_date[2],
                '%{date.year}%/%{date.month}%' => $_asset . $_date[0] . DS . $_date[1],
                '%{date.year}%/%{date.month}%/%{date.day}%' => $_asset . $_date[0] . DS . $_date[1] . DS . $_date[2],
            ],
            'kind' => ['select-input'],
            'stack' => 10.7
        ],
        'file[?][page][image][name]' => [
            'key' => 'name',
            'title' => $language->page_image_name,
            'description' => $language->field_description_page_image_name,
            'type' => 'select',
            'value' => $states['page']['image']['name'] ?? null,
            'values' => [
                // Time
                '%{date.year}%-%{date.month}%-%{date.day}%-%{date.hour}%-%{date.minute}%-%{date.second}%.%{extension}%' => $_date[0] . '-' . $_date[1] . '-' . $_date[2] . '-' . $_date[3] . '-' . $_date[4] . '-' . $_date[5] . '.' . $_extension,
                // ID
                '%{id}%.%{extension}%' => $_id . '.' . $_extension,
                // Unique ID
                '%{uid}%.%{extension}%' => $_uid . '.' . $_extension,
                // Hash
                '%{hash}%.%{extension}%' => $_hash . '.' . $_extension,
                // Name
                '%{name}%.%{extension}%' => $_name . '.' . $_extension,
                // Name + Time
                '%{name}%-%{date.year}%-%{date.month}%-%{date.day}%.%{extension}%' => $_name . '-' . $_date[0] . '-' . $_date[1] . '-' . $_date[2] . '.' . $_extension,
                // Name + ID
                '%{name}%-%{id}%.%{extension}%' => $_name . '-' . $_id . '.' . $_extension,
                // Name + Unique ID
                '%{name}%-%{uid}%.%{extension}%' => $_name . '-' . $_uid . '.' . $_extension,
                // Name + Hash
                '%{name}%-%{hash}%.%{extension}%' => $_name . '-' . $_hash . '.' . $_extension,
            ],
            'kind' => ['select-input'],
            'stack' => 10.8
        ]
    ],
    'stack' => 10.3
]);

Config::set('panel.desk.body.tab.view-data', [
    'title' => $language->datas,
    'field' => [
        'file[?][data][chunk]' => [
            'key' => 'chunk',
            'type' => 'number',
            'range' => [1, 100],
            'value' => $states['data']['chunk'] ?? null,
            'stack' => 10
        ],
        'file[?][data][kin]' => [
            'key' => 'kin',
            'type' => 'number',
            'range' => [1, 5],
            'value' => $states['data']['kin'] ?? null,
            'stack' => 10.1
        ]
    ],
    'stack' => 10.4
]);

// You can’t delete this file
Config::set('panel.desk.footer.tool.r.x', true);