<?php

// `http://127.0.0.1/panel/::g::/page/1`
$GLOBALS['_']['layout'] = $_['layout'] = 'page';

$lot = require __DIR__ . DS . '..' . DS . $_['layout'] . 's.php';

$g = 1 !== $user['status'];
$author = $user->user;

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
        if ($g && null !== $v['author'] && $v['author'] !== $author) {
            $v['hidden'] = true;
        }
    }
}

$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url'] = $url . $_['/'] . '/::s::/' . $_['path'] . $url->query('&', ['layout' => 'page.page', 'tab' => false]) . $url->hash;

return $lot;
