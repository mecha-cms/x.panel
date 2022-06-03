<?php

Hook::set('_', function($_) use($state) {
    if (
        !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot']) &&
        !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type']) &&
        'pages' === $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type']
    ) {
        foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
            $path = strtr($k, [
                LOT . D => "",
                D => '/'
            ]);
            if (!empty($v['tasks']['set']['url'])) {
                $v['tasks']['set']['url'] = [
                    'part' => 0,
                    'path' => dirname($path) . '/' . pathinfo($path, PATHINFO_FILENAME),
                    'query' => x\panel\_query_set(['type' => 'page/page']),
                    'task' => 'set'
                ];
            }
        }
    }
    return $_;
}, 10.1);

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url'] = [
    'part' => 0,
    'query' => x\panel\_query_set(['type' => 'page/page']),
    'task' => 'set'
];

return $_;