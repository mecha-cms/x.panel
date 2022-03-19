<?php

Hook::set('_', function($_) use($state) {
    if (
        !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot']) &&
        !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type']) &&
        'pages' === $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type']
    ) {
        $can_comment = isset($state->x->comment);
        foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
            $path = strtr($k, [LOT . D => "", D => '/']);
            if (!empty($v['tasks']['set']['url'])) {
                $v['tasks']['set']['url'] = [
                    'part' => 0,
                    'path' => dirname($path) . '/' . pathinfo($path, PATHINFO_FILENAME),
                    'query' => [
                        'query' => null,
                        'stack' => null,
                        'tab' => null,
                        'type' => 'page/page'
                    ],
                    'task' => 'set'
                ];
            }
            if ($can_comment && $count = q(g($c = strtr(dirname($k) . D . pathinfo($k, PATHINFO_FILENAME), [
                LOT . D . 'page' . D => LOT . D . 'comment' . D
            ]), 'archive,draft,page'))) {
                $v['tasks']['comment'] = [
                    'description' => [0 === $count ? '0 Comments' : (1 === $count ? '1 Comment' : '%d Comments'), $count],
                    'icon' => 'M4,4H9.5C9.25,4.64 9.09,5.31 9.04,6H4V16H10V19.08L13.08,16H18V13.23L20,15.23V16A2,2 0 0,1 18,18H13.9L10.2,21.71C10,21.9 9.75,22 9.5,22H9A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V6C2,4.89 2.9,4 4,4M15.5,2C18,2 20,4 20,6.5C20,7.38 19.75,8.2 19.31,8.89L22.41,12L21,13.39L17.89,10.31C17.2,10.75 16.38,11 15.5,11C13,11 11,9 11,6.5C11,4 13,2 15.5,2M15.5,4A2.5,2.5 0 0,0 13,6.5A2.5,2.5 0 0,0 15.5,9A2.5,2.5 0 0,0 18,6.5A2.5,2.5 0 0,0 15.5,4Z',
                    'stack' => 21,
                    'title' => 'Comments',
                    'url' => [
                        'part' => 1,
                        'path' => strtr($c, [LOT . D => "", D => '/']),
                        'query' => null,
                        'task' => 'get'
                    ]
                ];
            }
        }
    }
    return $_;
}, 10.1);

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url'] = [
    'part' => 0,
    'query' => [
        'query' => null,
        'stack' => null,
        'tab' => null,
        'type' => 'page/page'
    ],
    'task' => 'set'
];

return $_;