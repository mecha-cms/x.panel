<?php

$_ = x\panel\type\files\trash(array_replace_recursive($_, [
    'lot' => [
        'desk' => [
            // `desk`
            'lot' => [
                'form' => [
                    // `form/post`
                    'lot' => [
                        0 => [
                            // `section`
                            'lot' => [
                                'tasks' => [
                                    // `tasks/button`
                                    'lot' => [
                                        'let' => [
                                            'active' => $folder->exist && q(g($folder->path)) > 0
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
]));

if (!empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['skip'])) {
    return $_;
}

if (!isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['type'])) {
    return $_;
}

if (0 !== strpos($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['type'] . '/', 'files/')) {
    return $_;
}

if (!is_dir($folder = $_['folder'] ?? P)) {
    return $_;
}

$is_root = 0 === substr_count($_['path'], '/');
$token = $_['token'] ?? null;

foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'] as $k => &$v) {
    $path = strtr($k, [
        LOT . D => "",
        D => '/'
    ]);
    if (isset($v['tasks']['get'])) {
        $v['tasks']['get']['skip'] = true;
    }
    if (!empty($v['tasks']['let']['url']['query']['trash'])) {
        $v['tasks']['let']['description'] = 'Delete permanently';
        $v['tasks']['let']['icon'] = 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8.46,11.88L9.87,10.47L12,12.59L14.12,10.47L15.53,11.88L13.41,14L15.53,16.12L14.12,17.53L12,15.41L9.88,17.53L8.47,16.12L10.59,14L8.46,11.88M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z';
        $v['tasks']['let']['url']['query']['trash'] = false;
        if ($is_root) {
            if (is_dir($k)) {
                $stats = [0, 0];
                foreach (g($k, null, true) as $kk => $vv) {
                    ++$stats[$vv];
                }
                $v['description'] = x\panel\to\ago(new Time(strtr($v['title'], [S => ""])));
            }
            if (false === strpos($v['title'], '/')) {
                if (1 === $stats[1]) {
                    $v['title'] = S . basename(g($k, 1, true)->key() ?? "") . S;
                    $v['type'] = 'file';
                } else {
                    $v['title'] = S . basename(g($k, 0, true)->key() ?? "") . S;
                }
            }
            $v['tasks']['recover'] = [
                'description' => 'Recover this state',
                'icon' => 'M14,14H16L12,10L8,14H10V18H14V14M6,7H18V19C18,19.5 17.8,20 17.39,20.39C17,20.8 16.5,21 16,21H8C7.5,21 7,20.8 6.61,20.39C6.2,20 6,19.5 6,19V7M19,4V6H5V4H8.5L9.5,3H14.5L15.5,4H19Z',
                'stack' => 10,
                'title' => 'Recover',
                'url' => [
                    'path' => $path,
                    'query' => x\panel\_query_set(['token' => $token]),
                    'task' => 'fire/recover'
                ]
            ];
        }
    }
    if ($is_root && !empty($v['url'])) {
        $v['url']['query']['deep'] = true;
    }
}

unset($v);

if ($is_root) {
    // Sort folder(s) descending by its `path` property
    krsort($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot']);
}

return $_;