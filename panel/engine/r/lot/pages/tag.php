<?php

// `http://127.0.0.1/panel/::g::/tag/1`
$_['type'] = 'page';

$lot = require __DIR__ . DS . '..' . DS . 'pages.php';

$g = 1 !== $user['status'];
$author = $user->user;

if (isset($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'])) {
    foreach ($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
        unset($v['link'], $v['url']);
        // Disable page children feature
        $v['tasks']['enter']['skip'] = true;
        $v['tasks']['s']['skip'] = true;
        if ($g && isset($v['author']) && $v['author'] !== $author) {
            $v['skip'] = true;
        }
    }
}

$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['title'] = 'Tag';
$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['description'][1] = 'Tag';
$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url'] = $url . $_['/'] . '/::s::/' . $_['path'] . $url->query('&', ['type' => 'page/tag', 'tab' => false]) . $url->hash;

return $lot;
