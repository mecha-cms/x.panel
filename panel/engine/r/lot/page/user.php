<?php

// `http://127.0.0.1/panel/::g::/user/foo-bar.page`
$_['type'] = [
    'archive' => 'page/user',
    'data' => 'data',
    'draft' => 'page/user',
    'page' => 'page/user'
][pathinfo($_['f'], PATHINFO_EXTENSION)] ?? $_['type'];

$_ = require __DIR__ . DS . '..' . DS . 'index.php';

$status = $user->status;
$i = $page->status;
$any = $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['status']['lot'] ?? null;

if (1 !== $status) {
    $any = isset($any[$i]) ? [$i => $any[$i]] : [];
} else if ($page->name === $user->name) {
    $any = [1 => $any[1]];
}

// No use. This field was added only to hide the `pass` data from file list
$_['lot']['desk']['lot']['form']['data']['data[pass]'] = null;

// No use. This field was added only to hide the `pass` data from file list
$_['lot']['desk']['lot']['form']['data']['data[token]'] = null;

// Modify user status list
$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['status']['lot'] = $any;

return $_;