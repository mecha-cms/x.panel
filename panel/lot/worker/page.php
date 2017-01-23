<?php

$step = $step - 1;
$sort = $state['sort'];
$chunk = $state['chunk'];
$is_r_post = Request::is('post');

Panel::set('page.types.HTML', 'HTML');

Hook::set('::' . $sgr . '::page.url', function($content, $lot) use($state) {
    $s = Path::F($lot['path'], LOT);
    return rtrim(__url__('url') . '/' . $state['path'] . '/::g::/' . ltrim(To::url($s), '/'), '/');
});

$folder = LOT . DS . $path;
$chop_e = end($chops);
$is_index_view = is_numeric(Path::B($url->path)); // Force index view by appending page offset to the end of URL

if (substr($path, -3) === '/d+' || strpos($path, '/d:') !== false) {
    $data_folder = Path::D($folder);
    $s = explode('/d:', $path, 2);
    $lot = ['key' => isset($s[1]) ? To::key($s[1]) : ""];
    $kins = $page = [[], []];
    if ($sgr === 's') {
        if ($is_r_post && !Message::$x) {
            array_pop($chops);
            $data_path = $data_folder . DS . $_POST['key'] . '.data';
            $is_new = !file_exists($data_path);
            if (!$is_new && $_POST['x'] === 'trash') {
                File::open($data_path)->renameTo($_POST['key'] . '.trash');
                Message::success('Data <code>' . $_POST['key'] . '</code> successfully deleted.');
                Guardian::kick($state['path'] . '/::g::/' . implode('/', $chops));
            } else {
                File::write($_POST['content'])->saveTo($data_path, 0600);
                Message::success('Data <code>' . $_POST['key'] . '</code> successfully ' . ($is_new ? 'created' : 'updated') . '.');
                Guardian::kick($state['path'] . '/::g::/' . implode('/', $chops) . '/d:' . $_POST['key']);
            }
        }
        Lot::set('data', [
            new Page(null, $lot, '::' . $sgr . '::data'),
            new Page(null, $lot, 'data')
        ]);
    } else {
        if (($content = File::open($data_folder . DS . $lot['key'] . '.data')->read(false)) === false) {
            Shield::abort();
        } else {
            $lot['content'] = $content;
            Lot::set('data', [
                new Page(null, $lot, '::' . $sgr . '::data'),
                new Page(null, $lot, 'data')
            ]);
        }
    }
    if ($files = g($data_folder, 'data')) {
        $files = array_filter($files, function($v) use($lot) {
            return Path::N($v) !== $lot['key'];
        });
        foreach ($files as $v) {
            $lot = ['key' => Path::N($v)];
            $kins[0][] = new Page(null, $lot, '::' . $sgr . '::data');
            $kins[1][] = new Page(null, $lot, 'data');
        }
    }
    $kin_very_much = $files && count($files) > $chunk;
    if ($file = File::exist([
        $data_folder . '.draft',
        $data_folder . '.page',
        $data_folder . '.archive'
    ])) {
        $page[0] = new Page($file, [], '::' . $sgr . '::page');
        $page[1] = new Page($file);
    }
    Lot::set([
        'page' => $page,
        'kins' => $kins,
        'kin_very_much' => $kin_very_much // TODO
    ]);
} else if (is_dir($folder)) {
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
    $child_very_much = $files && count($files) > $chunk;
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
    $kin_very_much = $files && count($files) > $chunk;
    if ($files = g($folder, 'data')) {
        foreach ($files as $v) {
            $lot = ['key' => Path::N($v)];
            $datas[0][] = new Page(null, $lot, '::' . $sgr . '::data');
            $datas[1][] = new Page(null, $lot, 'data');
        }
    }
    $data_very_much = $files && count($files) > $chunk; // TODO
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
            if ($is_r_post && !Message::$x) {
                $defaults = [
                    'title' => false,
                    'description' => false,
                    'author' => false,
                    'type' => false,
                    'link' => false
                ];
                Guardian::kick(str_replace('::s::', '::g::', $url->current));
            }
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
}