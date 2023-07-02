<?php

unset($_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url']['query']['type']);

$_ = x\panel\type\pages\user($_);

if (!empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['skip'])) {
    return $_;
}

if (!isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type'])) {
    return $_;
}

if (0 !== strpos($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['type'] . '/', 'pages/')) {
    return $_;
}

if (!is_dir($folder = $_['folder'] ?? P)) {
    return $_;
}

$path = $user->path;
$super = 1 === $user->status;

// Prevent user with status other than `1` from creating user file(s)
if (!$super) {
    $_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['skip'] = true;
}

if (!empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'])) {
    $default = $_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url']['query']['type'] ?? 'page';
    foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => &$v) {
        if (!empty($v['tasks']['set']['url'])) {
            $v['tasks']['set']['url']['query']['type'] = $default;
        }
        $page = new User($v['path'] ?? $k);
        $v['description'] = S . $page->user . S;
        $v['image'] = $page->avatar(72, 72, 100) ?? null;
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
            $v['tasks']['let']['active'] = false;
        }
    }
    unset($v);
}

return $_;