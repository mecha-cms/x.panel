<?php

// `http://127.0.0.1/panel/::g::/user/1`
$_['layout'] = 'page';

$lot = require __DIR__ . DS . '..' . DS . $_['layout'] . 's.php';

$g = 1 !== $user['status'];

if (isset($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'])) {
    $path = $user->path;
    foreach ($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
        // Load data asynchronously for best performance
        $v['invoke'] = function($path) {
            $page = new User($path);
            return [
                'title' => S . $page . S,
                'description' => S . $page->user . S,
                'link' => 'draft' !== $page->x ? $page->url : null,
                'image' => $page->avatar(72),
                'tags' => [9999 => 'status:' . $page['status']]
            ];
        };
        // Disable page children feature
        $v['tasks']['enter']['skip'] = true;
        $v['tasks']['s']['skip'] = true;
        if ($g && $v['path'] !== $path) {
            $v['skip'] = true;
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
    $lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['skip'] = true;
}

return $lot;
