<?php

Hook::set('_', function ($_) {
    if (!empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['skip'])) {
        return $_;
    }
    if (!isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['type'])) {
        return $_;
    }
    if ('files' !== $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['type']) {
        return $_;
    }
    if (!is_dir($folder = $_['folder'] ?? P)) {
        return $_;
    }
    foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'] as $k => &$v) {
        unset($v['tasks']['get']);
        if (!empty($v['tasks']['let']['url']['query']['trash'])) {
            $v['tasks']['let']['description'] = 'Delete permanently';
            $v['tasks']['let']['icon'] = 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8.46,11.88L9.87,10.47L12,12.59L14.12,10.47L15.53,11.88L13.41,14L15.53,16.12L14.12,17.53L12,15.41L9.88,17.53L8.47,16.12L10.59,14L8.46,11.88M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z';
            unset($v['tasks']['let']['url']['query']['trash']);
        }
    }
    unset($v);
    return $_;
}, 10.1);

return x\panel\type\files\cache(array_replace_recursive($_, [
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