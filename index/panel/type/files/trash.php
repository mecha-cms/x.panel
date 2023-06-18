<?php

if (!isset($_with_hooks) || $_with_hooks) {
    Hook::set('_', function ($_) use ($state) {
        if (
            !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot']) &&
            !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['type']) &&
            'files' === $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['type']
        ) {
            $is_root = 0 === substr_count($_['path'], '/');
            foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'] as $k => &$v) {
                $path = strtr($k, [
                    LOT . D => "",
                    D => '/'
                ]);
                unset($v['tasks']['get']);
                if (!empty($v['tasks']['let']['url']['query']['trash'])) {
                    $v['tasks']['let']['description'] = 'Delete permanently';
                    $v['tasks']['let']['icon'] = 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8.46,11.88L9.87,10.47L12,12.59L14.12,10.47L15.53,11.88L13.41,14L15.53,16.12L14.12,17.53L12,15.41L9.88,17.53L8.47,16.12L10.59,14L8.46,11.88M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z';
                    unset($v['tasks']['let']['url']['query']['trash']);
                    if ($is_root) {
                        if (is_dir($k)) {
                            $stats = [0, 0];
                            foreach (g($k, null, true) as $kk => $vv) {
                                ++$stats[$vv];
                            }
                            $v['description'] = x\panel\to\elapse(new Time(strtr($v['title'], [S => ""])));
                        }
                        if (false === strpos($v['title'], '/')) {
                            if ($stats[1] > 1) {
                                $v['title'] = S . basename(g($k, 0, true)->key() ?? "") . S;
                            } else if ($stats[0] > 1) {
                                $v['title'] = S . basename(g($k, 0, true)->key() ?? "") . S;
                            } else {
                                $v['title'] = S . basename(g($k, 1, true)->key() ?? "") . S;
                                $v['type'] = 'file';
                            }
                        }
                        $v['tasks']['recover'] = [
                            'description' => 'Recover this state',
                            'icon' => 'M14,14H16L12,10L8,14H10V18H14V14M6,7H18V19C18,19.5 17.8,20 17.39,20.39C17,20.8 16.5,21 16,21H8C7.5,21 7,20.8 6.61,20.39C6.2,20 6,19.5 6,19V7M19,4V6H5V4H8.5L9.5,3H14.5L15.5,4H19Z',
                            'stack' => 10,
                            'title' => 'Recover',
                            'url' => [
                                'path' => $path,
                                'query' => x\panel\_query_set(['token' => $_['token']]),
                                'task' => 'fire/recover'
                            ]
                        ];
                    }
                }
                if ($is_root && !empty($v['url'])) {
                    $v['url']['query']['deep'] = true;
                }
            }
            if ($is_root) {
                // Sort folder(s) by its `path` property, descending
                \krsort($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot']);
            }
        }
        return $_;
    }, 10.1);
}

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['skip'] = true;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['file']['skip'] = true;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['folder']['skip'] = true;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['let'] = [
    'active' => q(g($_['folder'] ?? P)) > 0,
    'icon' => 'M15,16H19V18H15V16M15,8H22V10H15V8M15,12H21V14H15V12M11,10V18H5V10H11M13,8H3V18A2,2 0 0,0 5,20H11A2,2 0 0,0 13,18V8M14,5H11L10,4H6L5,5H2V7H14V5Z',
    'stack' => 10,
    'title' => 'Clear',
    'type' => 'link',
    'url' => [
        'query' => x\panel\_query_set(['token' => $_['token']]),
        'task' => 'fire/flush'
    ]
];

return $_;