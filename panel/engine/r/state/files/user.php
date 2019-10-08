<?php

// `http://127.0.0.1/panel/::g::/user/1`
$GLOBALS['_']['content'] = $_['content'] = 'page';

$lot = require __DIR__ . DS . '..' . DS . $_['content'] . 's.php';

$g = $user->status !== 1;

if (isset($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'])) {
    $path = $user->path;
    foreach ($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
        $page = new User($v['path']);
        $v['link'] = $page->url;
        $v['title'] = $page . "";
        $v['description'] = $page->user;
        $v['image'] = $page->avatar(72);
        // Disable page children feature
        $v['tasks']['enter']['hidden'] = true;
        $v['tasks']['s']['hidden'] = true;
        $v['tags'][] = 'status:' . $page['status'];
        if ($g && $v['path'] !== $path) {
            $v['hidden'] = true;
        }
        if (
            // Prevent user with status other than `1` from deleting user file(s)
            $g ||
            // Prevent user from deleting their own user file
            $v['path'] === $path
        ) {
            unset($v['tasks']['l']['url']);
        }
    }
}

$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['title'] = $language->user;
$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url'] = $url . $_['/'] . '::s::' . $_['path'] . $url->query('&', ['content' => 'page.user', 'tab' => false]) . $url->hash;

// Prevent user with status other than `1` from creating user file(s)
if ($g) {
    $lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['hidden'] = true;
}

return $lot;