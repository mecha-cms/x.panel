<?php

if ($c === 's' && HTTP::get('tab.0') === 'blob' && (HTTP::is('get', 'tabs.0') && !HTTP::get('tabs.0'))) {
    if (!$path) {
        if (Extend::exist('package')) {
            Config::reset('panel.desk.body.tab.blob.field.package');
            Config::set('panel.desk.body.tab.blob.field.hints', [
                'key' => 'hints',
                'title' => false,
                'type' => 'content',
                'value' => $language->field_description_package__($id),
                'stack' => 10.1
            ]);
            // Force extract
            Config::set('panel.desk.body.tab.blob.field.package[bucket]', [
                'key' => 'bucket',
                'type' => 'hidden',
                'value' => 0,
                'stack' => 0
            ]);
            Config::set('panel.desk.body.tab.blob.field.package[extract]', [
                'key' => 'extract',
                'type' => 'hidden',
                'value' => 1,
                'stack' => 0
            ]);
        }
    }
}

if ($chops && strpos($path, $chops[0] . '/state/config.php') === 0 && !HTTP::is('get', 'view')) {
    require __DIR__ . DS . 'state.php';
    Hook::set('on.ready', function() use($config, $language) {
        $pages = [];
        foreach (Get::pages(PAGE, 'page,archive', [1, 'path'], 'path') as $v) {
            $pages[Path::N($v)] = (new Page($v))->title;
        }
        Config::reset('panel.desk.body.tab.file.field.file[?][page]');
        Config::set('panel.desk.body.tab.file.field', [
            'file[?][path]' => [
                'key' => 'home',
                'type' => 'select',
                'width' => true,
                'value' => $config->path,
                'values' => $pages,
                'kind' => ['select-input'],
                'stack' => 10
            ]
        ]);
        Config::set('panel.desk.body.tab.page', [
            'field' => [
                'file[?][page][sort][0]' => [
                    'key' => 'order',
                    'title' => $language->sort[0],
                    'type' => 'radio[]',
                    'value' => $config->page->sort[0] ?? null,
                    'values' => [
                       '-1' => 'Z &#x2192; A',
                        '1' => 'A &#x2192; Z'
                    ],
                    'stack' => 10
                ],
                'file[?][page][sort][1]' => [
                    'key' => 'by',
                    'title' => $language->sort[1],
                    'type' => 'text',
                    'value' => $config->page->sort[1],
                    'placeholder' => $config->page->sort[1] ?: 'time',
                    'stack' => 10.1
                ]
            ],
            'stack' => 10.1
        ]);
    }, .2);
}