<?php

$step = $step - 1;
$sort = $state['sort'];
$chunk = $state['chunk'];

Panel::set('page.types.HTML', 'HTML');

Hook::set('::' . $sgr . '::page.url', function($content, $lot) use($state) {
    $s = Path::F($lot['path'], LOT);
    return rtrim(__url__('url') . '/' . $state['path'] . '/::g::/' . ltrim(To::url($s), '/'), '/');
});

$folder = LOT . DS . $path;
$chop_e = end($chops);
$is_index_view = is_numeric(Path::B($url->path)); // Force index view by appending page offset to the end of URL
if ($sgr === 's' || is_dir($folder)) {
    $file = File::exist([
        $folder . '.draft',
        $folder . '.page',
        $folder . '.archive'
    ]);
    $parent = $childs = $kins = $datas = [[], []]; // Why “child(s)” and “data(s)”? Please open `lot\language\en-us.page` for info
    if ($files = Get::pages($folder, 'draft,page,archive', $sort, 'path')) {
        foreach (Anemon::eat($files)->chunk($chunk, 0) as $v) {
            $childs[0][] = new Page($v, [], '::' . $sgr . '::page');
            $childs[1][] = new Page($v);
        }
    }
    $child_very_much = count($files) > $chunk;
    $folder_parent = Path::D($folder);
    if ($file_parent = File::exist([
        $folder_parent . '.draft',
        $folder_parent . '.page',
        $folder_parent . '.archive'
    ])) {
        $parent = [
            new Page($file_parent, [], '::' . $sgr . '::page'),
            new Page($file_parent)
        ];
    }
    if ($parent[0] && ($files = Get::pages($folder_parent, 'draft,page,archive', $sort, 'path'))) {
        $files = array_filter($files, function($v) use($chop_e) {
            return Path::N($v) !== $chop_e;
        });
        foreach (Anemon::eat($files)->chunk($chunk, 0) as $v) {
            $kins[0][] = new Page($v, [], '::' . $sgr . '::page');
            $kins[1][] = new Page($v);
        }
    }
    $kin_very_much = count($files) > $chunk;
    if ($files = glob($folder . DS . '*.data')) {
        foreach ($files as $v) {
            $n = Path::N($v);
            $datas[0][] = (object) ['slug'=> $n];
            $datas[1][] = (object) [
                'title' => isset($language->panel->data->{$n}) ? $language->panel->data->{$n} : $language->{$n},
                'slug' => $n
            ];
        }
    }
    $data_very_much = count($files) > $chunk; // TODO
    Lot::set([
        'parent' => $parent,
        'childs' => $childs,
        'kins' => $kins,
        'datas' => $datas,
        'child_very_much' => $child_very_much,
        'kin_very_much' => $kin_very_much,
        'data_very_much' => $data_very_much
    ]);
    if ($sgr === 'g' && $file && !$is_index_view) {
        Lot::set('page', [
            new Page($file, [], '::' . $sgr . '::page'),
            new Page($file)
        ]);
    } else if ($sgr === 's') {
        if (!isset($chops[1])) {
            Shield::abort(); // Is root page …
        } else if ($file) {
            $title = (new Date())->{str_replace('-', '_', $site->language)};
            Lot::set('page', [
                new Page(null, [ // New page …
                    'title' => $title
                ], '::' . $sgr . '::page'),
                new Page(null, [
                    'title' => $title,
                    'slug' => $chop_e
                ])
            ]);
        }
    } else if ($sgr === 'g') {
        $pages = [[], []];
        if ($files = Get::pages($folder, 'draft,page,archive', $sort, 'path')) {
            if ($q = Request::get('q')) {
                $files = array_filter($files, function($v) use($q) {
                    $v = Path::N($v);
                    foreach (explode(' ', l(urldecode($q))) as $q) {
                        if (strpos($v, $q) !== false) {
                            return true;
                        }
                    }
                    return false;
                });
            }
            foreach (Anemon::eat($files)->chunk($chunk, $step) as $v) {
                $pages[0][] = new Page($v, [], '::' . $sgr . '::page');
                $pages[1][] = new Page($v);
            }
        }
        if (empty($pages[0])) {
            Shield::abort();
        }
        $site->type = 'pages';
        Lot::set([
            'pager' => [new Elevator($files ?: [], $chunk, $step, $url . '/' . $state['path'] . '/::' . $sgr . '::/' . $path, [
                'direction' => [
                   '-1' => 'previous',
                    '0' => false,
                    '1' => 'next'
                ],
                'union' => [
                   '-2' => [
                        2 => ['rel' => null, 'classes' => ['button', 'x']]
                    ],
                   '-1' => [
                        1 => '&#x276E;',
                        2 => ['rel' => 'prev', 'classes' => ['button']]
                    ],
                    '1' => [
                        1 => '&#x276F;',
                        2 => ['rel' => 'next', 'classes' => ['button']]
                    ]
                ]
            ], '::' . $sgr . '::pages')],
            'pages' => $pages
        ]);
    }
} else if ($sgr !== 's') {
    Shield::abort();
}