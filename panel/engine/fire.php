<?php

if (!is_file(LOT . D . 'layout' . D . 'panel.php')) {
    Layout::set('panel', __DIR__ . D . '..' . D . 'lot' . D . 'layout' . D . 'panel.php');
}

// Modify default log-in redirection to the panel page if it is not set
if ('GET' === $req && !array_key_exists('kick', $_GET)) {
    if ($path === $route) {
        $_GET['kick'] = '/' . $route . '/get/' . trim($state->x->panel->route ?? 'asset', '/');
    }
}

foreach ([
    '%s goes here...' => "%s goes here\u{2026}",
    'Content goes here...' => "Content goes here\u{2026}",
    'Description goes here...' => "Description goes here\u{2026}",
    'You don\'t have permission to change the %s value.' => "You don\u{2019}t have permission to change the %s value."
] as $k => $v) {
    if (isset($GLOBALS['I'][$k])) {
        continue;
    }
    $GLOBALS['I'][$k] = $v;
}

require __DIR__ . D . 'r' . D . 'alert.php';
// require __DIR__ . D . 'r' . D . 'asset.php';
// require __DIR__ . D . 'r' . D . 'file.php';