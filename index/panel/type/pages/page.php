<?php

unset($_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url']['query']['type']);

$_ = x\panel\type\pages\page($_);

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

if (!empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'])) {
    $default = $_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['page']['url']['query']['type'] ?? null;
    foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'] as $k => $v) {
        if (!empty($v['tasks']['set']['url'])) {
            $query = $v['tasks']['set']['url']['query'] ?? [];
            $v['tasks']['set']['url']['query']['type'] = $default ?? $query['type'] ?? 'page';
            $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['pages']['lot']['pages']['lot'][$k] = $v;
        }
    }
}

return $_;