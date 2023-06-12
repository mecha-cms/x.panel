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
                            $v['description'] = implode(', ', [
                                i('%d folder' . (1 === $stats[0] ? "" : 's'), $stats[0]),
                                i('%d file' . (1 === $stats[1] ? "" : 's'), $stats[1])
                            ]);
                        }
                        if (false === strpos($v['title'], '/')) {
                            $v['title'] = S . x\panel\to\elapse(new Time(strtr($v['title'], [S => ""]))) . S;
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
    'icon' => 'M5,13H19V11H5M3,17H17V15H3M7,7V9H21V7',
    'skip' => 0 === q(g($_['folder'] ?? P)),
    'stack' => 10,
    'title' => 'Flush',
    'type' => 'link',
    'url' => [
        'query' => x\panel\_query_set(['token' => $_['token']]),
        'task' => 'fire/flush'
    ]
];

return $_;