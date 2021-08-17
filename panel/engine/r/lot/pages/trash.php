<?php

Hook::set('_', function($_) {
    if (
        !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot']) &&
        !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['type']) &&
        'files' === $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['type']
    ) {
        extract($GLOBALS, EXTR_SKIP);
        $is_root = 1 === count($_['chop']);
        foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'] as $k => &$v) {
            unset($v['tasks']['g']);
            if (isset($v['tasks']['l']['url']) && false !== strpos($v['tasks']['l']['url'], '&trash=')) {
                $v['tasks']['l']['icon'] = 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8.46,11.88L9.87,10.47L12,12.59L14.12,10.47L15.53,11.88L13.41,14L15.53,16.12L14.12,17.53L12,15.41L9.88,17.53L8.47,16.12L10.59,14L8.46,11.88M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z';
                $v['tasks']['l']['description'] = 'Delete permanently';
                $v['tasks']['l']['url'] = explode('&trash=', $v['tasks']['l']['url'], 2)[0];
                if ($is_root) {
                    if (is_dir($k)) {
                        $stats = [0, 0];
                        foreach (g($k, null, true) as $kk => $vv) {
                            ++$stats[$vv];
                        }
                        $v['description'] = implode(', ', [
                            i('%d folder' . (1 === $stats[0] ? "" : 's'), $stats[0]),
                            i('%d file' . (1 === $stats[0] ? "" : 's'), $stats[1])
                        ]);
                    }
                    $v['tasks']['restore'] = [
                        'title' => 'Restore',
                        'description' => 'Restore',
                        'icon' => 'M14,14H16L12,10L8,14H10V18H14V14M6,7H18V19C18,19.5 17.8,20 17.39,20.39C17,20.8 16.5,21 16,21H8C7.5,21 7,20.8 6.61,20.39C6.2,20 6,19.5 6,19V7M19,4V6H5V4H8.5L9.5,3H14.5L15.5,4H19Z',
                        'url' => $_['/'] . '/::f::/20909bc1/trash/' . basename($k) . $url->query('&', [
                            'q' => false,
                            'tab' => false,
                            'token' => $_['token'],
                            'trash' => false,
                        ]) . $url->hash,
                        'stack' => 10
                    ];
                }
            }
        }
        if ($is_root) {
            // Sort folder(s) by its `path` property, descending
            $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['sort'] = [-1, 'path'];
        }
    }
    return $_;
}, 10.1);

$_ = require __DIR__ . DS . '..' . DS . 'index.php';

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['skip'] = true;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['file']['skip'] = true;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['folder']['skip'] = true;
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['l'] = [
    'title' => 'Empty',
    'description' => 'Empty the trash folder',
    'icon' => 'M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z',
    'type' => 'link',
    'url' => $_['/'] . '/::f::/e2c4d4a6' . $url->query('&', [
        'kick' => strtr($_['/'] . '/::g::/trash/1', [
            $url . '/' => ""
        ]),
        'q' => false,
        'tab' => false,
        'token' => $_['token']
    ]) . $url->hash,
    'skip' => 0 === q(g($_['f'])),
    'stack' => 10
];

return $_;
