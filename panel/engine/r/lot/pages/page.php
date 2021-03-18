<?php

Hook::set('_', function($_) {
    if (
        !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot']) &&
        !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type']) &&
        'pages' === $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type']
    ) {
        extract($GLOBALS, EXTR_SKIP);
        $can_comment = isset($state->x->comment);
        $before = $_['/'] . '/::';
        foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
            $after = '::' . strtr($k, [
                LOT => "",
                DS => '/'
            ]);
            if (!empty($v['tasks']['s']['url'])) {
                $v['tasks']['s']['url'] = $before . 's' . Path::F($after, '/') . $url->query('&', [
                    'q' => false,
                    'tab' => false,
                    'type' => 'page/page'
                ]) . $url->hash;
            }
            if ($can_comment && $count = q(g($c = strtr(Path::F($k), [
                LOT . DS . 'page' . DS => LOT . DS . 'comment' . DS
            ]), 'archive,draft,page'))) {
                $v['tasks']['comment'] = [
                    'title' => 'Comments',
                    'description' => [0 === $count ? '0 Comments' : (1 === $count ? '1 Comment' : '%d Comments'), $count],
                    'icon' => 'M4,4H9.5C9.25,4.64 9.09,5.31 9.04,6H4V16H10V19.08L13.08,16H18V13.23L20,15.23V16A2,2 0 0,1 18,18H13.9L10.2,21.71C10,21.9 9.75,22 9.5,22H9A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V6C2,4.89 2.9,4 4,4M15.5,2C18,2 20,4 20,6.5C20,7.38 19.75,8.2 19.31,8.89L22.41,12L21,13.39L17.89,10.31C17.2,10.75 16.38,11 15.5,11C13,11 11,9 11,6.5C11,4 13,2 15.5,2M15.5,4A2.5,2.5 0 0,0 13,6.5A2.5,2.5 0 0,0 15.5,9A2.5,2.5 0 0,0 18,6.5A2.5,2.5 0 0,0 15.5,4Z',
                    'url' => $before . 'g::/' . strtr($c, [
                        LOT . DS => "",
                        DS => '/'
                    ]) . '/1',
                    'stack' => 21
                ];
            }
        }
    }
    return $_;
}, 10.1);

// `http://127.0.0.1/panel/::g::/page/1`
$_['type'] = 'pages';

$_ = require __DIR__ . DS . '..' . DS . 'index.php';

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url'] = $_['/'] . '/::s::/' . $_['path'] . $url->query('&', [
    'q' => false,
    'tab' => false,
    'type' => 'page/page'
]) . $url->hash;

return $_;
