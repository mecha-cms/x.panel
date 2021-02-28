<?php

// `http://127.0.0.1/panel/::g::/tag/1`
$_['type'] = 'pages';

$_ = require __DIR__ . DS . '..' . DS . 'index.php';

$g = 1 !== $user['status'];
$author = $user->user;

if (isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'])) {
    foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
        unset($v['link'], $v['url']);
        // Disable page children feature
        $v['tasks']['enter']['skip'] = true;
        $v['tasks']['s']['skip'] = true;
        if ($g && isset($v['author']) && $v['author'] !== $author) {
            $v['skip'] = true;
        }
    }
}

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['title'] = 'Tag';
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['description'][1] = 'Tag';
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url'] = $url . $_['/'] . '/::s::/' . $_['path'] . $url->query('&', [
    'tab' => false,
    'type' => 'page/tag'
]) . $url->hash;

return $_;
