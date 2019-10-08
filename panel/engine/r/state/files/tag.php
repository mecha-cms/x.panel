<?php

// `http://127.0.0.1/panel/::g::/tag/1`
$GLOBALS['_']['content'] = $_['content'] = 'page';

$lot = require __DIR__ . DS . '..' . DS . $_['content'] . 's.php';

$g = $user->status !== 1;
$author = $user->user;

if (isset($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'])) {
    foreach ($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
        unset($v['link'], $v['url']);
        // Disable page children feature
        $v['tasks']['enter']['hidden'] = true;
        $v['tasks']['s']['hidden'] = true;
        if ($g && $v['author'] !== null && $v['author'] !== $author) {
            $v['hidden'] = true;
        }
    }
}

$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['title'] = $language->tag;
$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url'] = $url . $_['/'] . '::s::' . $_['path'] . $url->query('&', ['content' => 'page.tag', 'tab' => false]) . $url->hash;

return $lot;