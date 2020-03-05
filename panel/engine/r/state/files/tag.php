<?php

// `http://127.0.0.1/panel/::g::/tag/1`
$GLOBALS['_']['layout'] = $_['layout'] = 'page';

$lot = require __DIR__ . DS . '..' . DS . $_['layout'] . 's.php';

$g = 1 !== $user['status'];
$author = $user->user;

if (isset($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'])) {
    foreach ($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
        unset($v['link'], $v['url']);
        // Disable page children feature
        $v['tasks']['enter']['hidden'] = true;
        $v['tasks']['s']['hidden'] = true;
        if ($g && null !== $v['author'] && $v['author'] !== $author) {
            $v['hidden'] = true;
        }
    }
}

$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['title'] = 'Tag';
$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['description'][1] = 'Tag';
$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url'] = $url . $_['/'] . '/::s::/' . $_['path'] . $url->query('&', ['layout' => 'page.tag', 'tab' => false]) . $url->hash;

return $lot;
