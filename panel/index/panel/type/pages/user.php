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
            $v['description'] = S . $page->user . S;
            $v['image'] = $page->avatar(72) ?? null;
            $v['link'] = 'draft' !== $page->x ? $page->url : false;
            $v['tags']['status:' . $page->status] = true;
            $v['title'] = S . $page . S;
            // Disable page children feature
            $v['tasks']['enter']['skip'] = true;
            $v['tasks']['set']['skip'] = true;
            if (!$super && $path !== $page->path) {
                $v['skip'] = true;
            }
            if (
                // Prevent user with status other than `1` from deleting user file(s)
                !$super ||
                // Prevent user from deleting their own user file
                $path === $page->path
            ) {
                unset($v['tasks']['let']['url']);
            }
        }
    }
    return $_;
}, 10.1);

$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['description'][1] = 'User';
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['title'] = 'User';
$_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url'] = [
    'part' => 0,
    'query' => [
        'chunk' => null,
        'deep' => null,
        'query' => null,
        'stack' => null,
        'tab' => null,
        'type' => 'page/user',
        'x' => null
    ],
    'task' => 'set'
];

// Prevent user with status other than `1` from creating user file(s)
if (!$super) {
    $_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['skip'] = true;
}

return $_;