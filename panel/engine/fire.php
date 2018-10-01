<?php

$a = explode('/', $url->path);
array_shift($a); // remove namespace
array_shift($a); // remove command

if (count($a) === 0) {
    exit;
}

$path = implode('/', $a);

$folders = glob(LOT . DS . '*', GLOB_ONLYDIR | GLOB_NOSORT);

sort($folders);

$icons = $language->panel->icon;

$i = 0;
$links = [];
foreach ($folders as $v) {
    $n = basename($v);
    $links[$n] = [
        'icon' => [[isset($icons->{$n}) ? (isset($icons->{$n}->{'$'}) ? $icons->{$n}->{'$'} : $icons->{$n}) : ""]],
        'active' => strpos($path . '/', $n . '/') === 0,
        'path' => $n,
        'stack' => 10 + $i
    ];
    $i += .1;
}

Config::set('panel.nav.lot', [
    'title' => false,
    'icon' => [['M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z']],
    '+' => $links,
    'stack' => 10
]);

$i = 0;
$links = [];
foreach (glob(EXTEND . DS . '*' . DS . 'index.php', GLOB_NOSORT) as $v) {
    $directory = Path::F(dirname($v), LOT, '/');
    $n = basename($directory);
    $f = dirname($v);
    $f = File::exist([
        $f . DS . 'about.page',
        $f . DS . 'about.' . $config->language . '.page'
    ]);
    $title = Page::open($f)->get('title', $n);
    $links[$title] = [
        'title' => $title,
        'icon' => [""],
        'active' => strpos($path . '/', $directory . '/') === 0,
        'path' => $directory . '/1'
    ];
}
ksort($links);
$links_a = [];
foreach ($links as $v) {
    $v['stack'] = 10 + $i;
    $links_a[basename(dirname($v['path']))] = $v;
    $i += .1;
}

Config::set('panel.nav.lot.+.extend.+', $links_a);

$i = 0;
$links = [];
foreach (glob(EXTEND . DS . 'plugin' . DS . 'lot' . DS . 'worker' . DS . '*' . DS . 'index.php', GLOB_NOSORT) as $v) {
    $dir = Path::F(dirname($v), LOT, '/');
    $f = dirname($v);
    $f = File::exist([
        $f . DS . 'about.page',
        $f . DS . 'about.' . $config->language . '.page'
    ]);
    $title = Page::open($f)->get('title', Path::N($dir));
    $links[$title] = [
        'title' => $title,
        'icon' => [""],
        'active' => strpos($path . '/', $dir . '/') === 0,
        'path' => $dir . '/1'
    ];
}
ksort($links);
$links_a = [];
foreach ($links as $v) {
    $v['stack'] = 10 + $i;
    $links_a[basename(dirname($v['path']))] = $v;
    $i += .1;
}

Config::set('panel.nav.lot.+.extend.+.plugin.+', $links_a);

Config::set('panel.nav.search', [
    'content' => panel\nav_li_search([
        'title' => $language->{$a[0]},
        'path' => $path . '/1'
    ], $a[0]),
    'stack' => 10.1
]);

Config::set('panel.nav.site', [
    '+' => [
        'config' => [
            'path' => 'state/config',
            'icon' => [[$icons->config]],
            'stack' => 10
        ],
        'view' => [
            'url' => "",
            'icon' => [['M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z']],
            'target' => '_blank',
            'stack' => 10.1
        ]
    ],
    'stack' => 20
]);

Config::set('panel.nav.message', [
    'title' => false,
    'icon' => [['M21,19V20H3V19L5,17V11C5,7.9 7.03,5.17 10,4.29C10,4.19 10,4.1 10,4A2,2 0 0,1 12,2A2,2 0 0,1 14,4C14,4.1 14,4.19 14,4.29C16.97,5.17 19,7.9 19,11V17L21,19M14,21A2,2 0 0,1 12,23A2,2 0 0,1 10,21']],
    'kind' => ['right'],
    'stack' => 10.2
]);

if ($query = HTTP::get('q')) {
    panel\message('info', $language->message_info_search(To::text($query)));
    Lot::set('message', Message::get());
}