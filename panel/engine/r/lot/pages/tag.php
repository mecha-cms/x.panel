<?php

Hook::set('_', function($_) {
    if (
        !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot']) &&
        !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type']) &&
        'pages' === $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type']
    ) {
        foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
            $v['link'] = $v['url'] = false;
            // Disable page children feature
            $v['tasks']['enter']['skip'] = true;
            $v['tasks']['s']['skip'] = true;
        }
    }
    return $_;
}, 10.1);

// `http://127.0.0.1/panel/::g::/tag/1`
$_['type'] = 'pages';

$_ = require __DIR__ . DS . '..' . DS . 'index.php';

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['title'] = 'Tag';
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['description'][1] = 'Tag';
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url'] = $_['/'] . '/::s::/' . $_['path'] . $url->query('&', [
    'q' => false,
    'tab' => false,
    'type' => 'page/tag'
]) . $url->hash;

return $_;
