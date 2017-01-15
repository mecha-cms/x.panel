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
    if ($files = Get::pages($folder, 'draft,page,archive', $sort[0], $sort[1], 'path')) {
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
    if ($parent[0] && ($files = Get::pages($folder_parent, 'draft,page,archive', $sort[0], $sort[1], 'path'))) {
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
                'title' => $language->{$n},
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
            Shield::abort(); // Is root …
        }
        if ($file) {
            $x = new Page($file);
            Message::info('Editing <strong>' . $x->title . '</strong> page.');
            Guardian::kick($state['path'] . '/::g::/' . implode('/', $chops)); // Is exists …
        }
        Lot::set('page', [
            new Page(null, [
                'slug' => $chop_e
            ], '::' . $sgr . '::page'),
            new Page
        ]); // New page …
    } else if ($sgr === 'g') {
        $pages = [[], []];
        if ($files = Get::pages($folder, 'draft,page,archive', $sort[0], $sort[1], 'path')) {
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
                     '0' => [
                        2 => ['classes' => null, 'css' => ['display' => 'none']]
                     ],
                     '1' => [
                        1 => '&#x276F;',
                        2 => ['rel' => 'next', 'classes' => ['button']]
                    ]
                ]
            ], '::' . $sgr . '::/page' . Anemon::NS . 'pager')],
            'pages' => $pages
        ]);
    }
} else if ($sgr !== 's') {
    Shield::abort();
}