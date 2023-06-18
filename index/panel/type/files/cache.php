<?php

if (!isset($_with_hooks) || $_with_hooks) {
    Hook::set('_', function ($_) use ($state) {
        if (
            !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot']) &&
            !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['type']) &&
            'files' === $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['type']
        ) {
            foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'] as $k => &$v) {
                unset($v['tasks']['get']);
                if (!empty($v['tasks']['let']['url']['query']['trash'])) {
                    $v['tasks']['let']['description'] = 'Delete permanently';
                    $v['tasks']['let']['icon'] = 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8.46,11.88L9.87,10.47L12,12.59L14.12,10.47L15.53,11.88L13.41,14L15.53,16.12L14.12,17.53L12,15.41L9.88,17.53L8.47,16.12L10.59,14L8.46,11.88M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z';
                    unset($v['tasks']['let']['url']['query']['trash']);
                }
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
    'icon' => 'M12,18A6,6 0 0,1 6,12C6,11 6.25,10.03 6.7,9.2L5.24,7.74C4.46,8.97 4,10.43 4,12A8,8 0 0,0 12,20V23L16,19L12,15M12,4V1L8,5L12,9V6A6,6 0 0,1 18,12C18,13 17.75,13.97 17.3,14.8L18.76,16.26C19.54,15.03 20,13.57 20,12A8,8 0 0,0 12,4Z',
    'stack' => 10,
    'title' => 'Flush',
    'type' => 'link',
    'url' => [
        'query' => x\panel\_query_set(['token' => $_['token']]),
        'task' => 'fire/flush'
    ]
];

return $_;