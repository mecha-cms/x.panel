<?php

$path = $user->path;
$super = 1 === $user->status;

Hook::set('_', function($_) use($path, $super) {
    if (
        !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot']) &&
        !empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type']) &&
        'pages' === $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type']
    ) {
        extract($GLOBALS, EXTR_SKIP);
        foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
            $page = new User($v['path'] ?? $k);
            $v['title'] = S . $page . S;
            $v['description'] = S . $page->user . S;
            $v['link'] = 'draft' !== $page->x ? $page->url : false;
            $v['image'] = $page->avatar(72);
            $v['tags']['status:' . $page->status] = true;
            // Disable page children feature
            $v['tasks']['enter']['skip'] = true;
            $v['tasks']['s']['skip'] = true;
            if (!$super && $path !== $page->path) {
                $v['skip'] = true;
            }
            if (
                // Prevent user with status other than `1` from deleting user file(s)
                !$super ||
                // Prevent user from deleting their own user file
                $path === $page->path
            ) {
                unset($v['tasks']['l']['url']);
            }
        }
    }
    return $_;
}, 10.1);

// `http://127.0.0.1/panel/::g::/user/1`
$_['type'] = 'pages';

$_ = require __DIR__ . DS . '..' . DS . 'index.php';

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['title'] = 'User';
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['description'][1] = 'User';
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url'] = $_['/'] . '/::s::/' . $_['path'] . $url->query('&', [
    'tab' => false,
    'type' => 'page/user'
]) . $url->hash;

// Prevent user with status other than `1` from creating user file(s)
if (!$super) {
    $_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['skip'] = true;
}

return $_;
