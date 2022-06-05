<?php

if (class_exists('Layout')) {
    !Layout::path('alert/panel') && Layout::set('alert/panel', __DIR__ . D . 'y' . D . 'alert' . D . 'panel.php');
    !Layout::path('panel') && Layout::set('panel', __DIR__ . D . 'y' . D . 'panel.php');
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