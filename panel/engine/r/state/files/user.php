<?php

// `http://127.0.0.1/panel/::g::/user/1`
$GLOBALS['_']['layout'] = $_['layout'] = 'page';

$lot = require __DIR__ . DS . '..' . DS . $_['layout'] . 's.php';

$g = 1 !== $user['status'];

if (isset($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'])) {
    $path = $user->path;
    foreach ($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
        $page = new User($k);
        $v['link'] = 'draft' !== $page->x ? $page->url : null;
        $v['title'] = S . $page . S;
        $v['description'] = S . $page->user . S;
        $v['image'] = function() use($page) {
            // Load avatar asynchronously for best performance
            return $page->avatar(72);
        };
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

$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['title'] = 'User';
$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['description'][1] = 'User';
$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url'] = $url . $_['/'] . '/::s::/' . $_['path'] . $url->query('&', ['layout' => 'page.user', 'tab' => false]) . $url->hash;

// Prevent user with status other than `1` from creating user file(s)
if ($g) {
    $lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['hidden'] = true;
}

return $lot;
