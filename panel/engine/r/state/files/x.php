<?php

// `http://127.0.0.1/panel/::g::/x/1`
$GLOBALS['_']['content'] = $_['content'] = 'page';

$lot = require __DIR__ . DS . '..' . DS . $_['content'] . 's.php';

$lot['bar']['lot'][0]['lot']['search']['hidden'] = true; // Hide search form

$pages = [];
$count = 0;

if (is_dir($folder = LOT . strtr($_['path'], '/', DS))) {
    $before = $url . $_['/'] . '::';
    foreach (g($folder, 'page', 1) as $k => $v) {
        if ($v === 0) {
            continue;
        }
        $after = '::' . strtr($kk = dirname($k), [
            LOT => "",
            DS => '/'
        ]);
        $n = basename($kk);
        $page = new Page($k);
        $image = glob($kk . DS . 'lot' . DS . 'asset' . DS . '{gif,jpg,jpeg,png}' . DS . $n . '.{gif,jpg,jpeg,png}', GLOB_BRACE | GLOB_NOSORT);
        $image = $image[0] ?? null;
        $pages[$k] = [
            'path' => $k,
            'type' => 'Page',
            'title' => _\lot\x\panel\h\w($page->title),
            'time' => $page->time . "",
            'update' => $page->update . "",
            'description' => _\lot\x\panel\h\w($page->description),
            'image' => $image ? To::URL($image) : null,
            'link' => $page->url,
            'tasks' => [
                'g' => [
                    'title' => $language->doEdit,
                    'description' => $language->doEdit,
                    'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                    'url' => $before . 'g' . $after . $url->query('&', ['tab' => false]) . $url->hash,
                    'stack' => 20
                ],
                'l' => [
                    'title' => $language->doDelete,
                    'description' => $language->doDelete,
                    'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                    'url' => $before . 'l' . $after . $url->query('&', ['tab' => false, 'token' => $_['token']]),
                    'stack' => 30
                ]
            ]
        ];
        ++$count;
    }
    $pages = (new Anemon($pages))->sort($_['sort'], true)->get();
    $lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] = $pages;
    $lot['desk']['lot']['form']['lot'][2]['lot']['pager']['count'] = $count;
}

return $lot;