<?php

// `http://127.0.0.1/panel/::g::/user/foo-bar.page`
$GLOBALS['_']['layout'] = $_['layout'] = [
    'archive' => 'page.user',
    'data' => 'data',
    'draft' => 'page.user',
    'page' => 'page.user'
][pathinfo($_['f'], PATHINFO_EXTENSION)] ?? $_['layout'];

$lot = require __DIR__ . DS . '..' . DS . $_['layout'] . '.php';

$status = $user['status'];
$i = $page['status'];
$any = $lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['status']['lot'] ?? null;

if (1 !== $status) {
    $any = isset($any[$i]) ? [$i => $any[$i]] : [];
} else if ($page->name === $user->name) {
    $any = [1 => $any[1]];
}

// No use. This field was added only to hide the `pass` data from file list
$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['pass'] = [
    'hidden' => true,
    'name' => 'data[pass]'
];

// No use. This field was added only to hide the `pass` data from file list
$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['token'] = [
    'hidden' => true,
    'name' => 'data[token]'
];

// Modify user status list
$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['status']['lot'] = $any;

return $lot;