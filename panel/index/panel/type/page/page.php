<?php

$count = ($f = $_['file']) ? q(g(dirname($f) . D . pathinfo($f, PATHINFO_FILENAME), 'page')) : 0;
$folder = $f ? dirname($f) . D . pathinfo($f, PATHINFO_FILENAME) : P;

if ('POST' === $_SERVER['REQUEST_METHOD']) {
    $chunk = $_POST['data']['chunk'] ?? $_POST['page']['chunk'] ?? null;
    $x = $_POST['page']['x'] ?? 'page';
    // Having chunk value less than `1` will not create a `chunk.data` file.
    // Instead, it will create a placeholder page to hide the pages.
    if (is_int($chunk) && $chunk < 1 && is_dir($folder)) {
        unset($_POST['data']['chunk'], $_POST['page']['chunk']);
        file_put_contents($ff = $folder . D . '.' . $x, "");
        chmod($ff, 0600);
    } else if (is_file($ff = $folder . D . '.' . $x)) {
        unlink($ff);
    }
}

$_['lot']['bar']['lot'][0]['lot']['set']['url'] = [
    'part' => 0,
    'path' => 'get' === $_['task'] ? dirname($_['path']) : $_['path'],
    'query' => [
        'query' => null,
        'stack' => null,
        'tab' => null,
        'type' => 'page/page'
    ],
    'task' => 'set'
];

$chunk = $state->x->page->page->chunk ?? 5;
$deep = $state->x->page->page->deep ?? 0;
$sort = $state->x->page->page->sort ?? [1, 'path'];

$page_chunk = $page['chunk'] ?? null;
$page_deep = $page['deep'] ?? null;
$page_sort = $page['sort'] ?? null;

$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['state'] = [
    'lot' => [
        'fields' => [
            'lot' => [
                'chunk' => [
                    'active' => $count > 0, // Disable this field if child page(s) count is `0`
                    'description' => ['Number of %s to show. Set value to %s to hide the pages.', ['pages', '<code>0</code>']],
                    'hint' => $chunk,
                    'min' => 0,
                    'name' => 'data[chunk]',
                    'stack' => 10,
                    'step' => 1,
                    'type' => 'number',
                    'value' => is_file($folder . D . '.archive') || is_file($folder . D . '.page') ? 0 : ($page_chunk === $chunk ? null : $page_chunk)
                ],
                'deep' => [
                    'active' => $count > 0,
                    'description' => ['Scan %s recursively. Set value to the maximum directory level to scan.', ['pages']],
                    'hint' => $deep,
                    'min' => 0,
                    'name' => 'data[deep]',
                    'stack' => 20,
                    'step' => 1,
                    'type' => 'number',
                    'value' => $page_deep === $deep ? null : $page_deep,
                ],
                'sort' => [
                    'active' => $count > 0,
                    'block' => true,
                    'lot' => [
                        '[-1,"name"]' => ['title' => ['Sort %1$s %2$s by %3$s', ['pages', 'descending', 'slug']]],
                        '[-1,"path"]' => ['title' => ['Sort %1$s %2$s by %3$s', ['pages', 'descending', 'path']]],
                        '[-1,"time"]' => ['title' => ['Sort %1$s %2$s by %3$s', ['pages', 'descending', 'time']]],
                        '[-1,"title"]' => ['title' => ['Sort %1$s %2$s by %3$s', ['pages', 'descending', 'title']]],
                        '[1,"name"]' => ['title' => ['Sort %1$s %2$s by %3$s', ['pages', 'ascending', 'slug']]],
                        '[1,"path"]' => ['title' => ['Sort %1$s %2$s by %3$s', ['pages', 'ascending', 'path']]],
                        '[1,"time"]' => ['title' => ['Sort %1$s %2$s by %3$s', ['pages', 'ascending', 'time']]],
                        '[1,"title"]' => ['title' => ['Sort %1$s %2$s by %3$s', ['pages', 'ascending', 'title']]],
                    ],
                    'name' => 'data[sort]',
                    'stack' => 30,
                    'type' => 'item',
                    'value' => $page_sort === $sort ? null : json_encode($page_sort)
                ]
            ],
            'type' => 'fields'
        ]
    ],
    'skip' => 'get' !== $_['task'],
    'stack' => 30
];

$_['lot']['desk']['lot']['form']['lot'][2]['lot']['fields']['lot'][0]['lot']['tasks']['lot']['set']['title'] = 'set' === $_['task'] ? 'Publish' : 'Update';

return $_;