<?php

// `http://127.0.0.1/panel/::g::/page/1`
$_['layout'] = 'page';

$lot = require __DIR__ . DS . '..' . DS . $_['layout'] . 's.php';

$g = 1 !== $user['status'];
$author = $user->user;
$comments = null !== State::get('x.comment');

if (isset($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'])) {
    $before = $url . $_['/'] . '/::';
    foreach ($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
        $after = '::' . strtr($k, [
            LOT => "",
            DS => '/'
        ]);
        if (!empty($v['tasks']['s']['url'])) {
            $v['tasks']['s']['url'] = $before . 's' . Path::F($after, '/') . $url->query('&', ['layout' => 'page.page', 'tab' => false]) . $url->hash;
        }
        if ($g && isset($v['author']) && $v['author'] !== $author) {
            $v['skip'] = true;
        }
        if ($comments && $i = q(g($c = strtr(Path::F($k), [LOT . DS . 'page' . DS => LOT . DS . 'comment' . DS]), 'archive,draft,page'))) {
            $v['tasks']['comment'] = [
                'title' => 'Comments',
                'description' => [0 === $i ? '0 Comments' : (1 === $i ? '1 Comment' : '%d Comments'), $i],
                'icon' => 'M4,4H9.5C9.25,4.64 9.09,5.31 9.04,6H4V16H10V19.08L13.08,16H18V13.23L20,15.23V16A2,2 0 0,1 18,18H13.9L10.2,21.71C10,21.9 9.75,22 9.5,22H9A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V6C2,4.89 2.9,4 4,4M15.5,2C18,2 20,4 20,6.5C20,7.38 19.75,8.2 19.31,8.89L22.41,12L21,13.39L17.89,10.31C17.2,10.75 16.38,11 15.5,11C13,11 11,9 11,6.5C11,4 13,2 15.5,2M15.5,4A2.5,2.5 0 0,0 13,6.5A2.5,2.5 0 0,0 15.5,9A2.5,2.5 0 0,0 18,6.5A2.5,2.5 0 0,0 15.5,4Z',
                'url' => $before . 'g::/' . strtr($c, [LOT . DS => "", DS => '/']) . '/1',
                'stack' => 21
            ];
        }
    }
}

$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url'] = $url . $_['/'] . '/::s::/' . $_['path'] . $url->query('&', ['layout' => 'page.page', 'tab' => false]) . $url->hash;

return $lot;
