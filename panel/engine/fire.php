<?php

if (!is_file(LOT . D . 'layout' . D . 'alert' . D . 'panel.php')) {
    Layout::set('alert/panel', __DIR__ . D . '..' . D . 'lot' . D . 'layout' . D . 'alert' . D . 'panel.php');
}

if (!is_file(LOT . D . 'layout' . D . 'panel.php')) {
    Layout::set('panel', __DIR__ . D . '..' . D . 'lot' . D . 'layout' . D . 'panel.php');
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
require __DIR__ . D . 'r' . D . 'file.php';
// TODO require ...