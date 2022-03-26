<?php

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

if ('get' === $_['task']) {
    $count = ($f = $_['file']) ? q(g(dirname($f) . D . pathinfo($f, PATHINFO_FILENAME), 'page')) : 0;
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['state'] = [
        'lot' => [
            'fields' => [
                'lot' => [
                    'pages' => [
                        'active' => $count > 0, // Disable this field if child page(s) count is `0`
                        'block' => true,
                        'lot' => [
                            '[-1,"time"]' => 'Sort child pages descending by time',
                            '[-1,"title"]' => 'Sort child pages descending by title',
                            '[1,"time"]' => 'Sort child pages ascending by time',
                            '[1,"title"]' => 'Sort child pages ascending by title',
                        ],
                        'name' => 'data[sort]',
                        'stack' => 10,
                        'type' => 'item',
                        'value' => json_encode($page['sort'] ?? [-1, 'time'])
                    ],
                ],
                'type' => 'fields'
            ]
        ],
        'stack' => 30
    ];
}

$_['lot']['desk']['lot']['form']['lot'][2]['lot']['fields']['lot'][0]['lot']['tasks']['lot']['set']['title'] = 'set' === $_['task'] ? 'Publish' : 'Update';

return $_;