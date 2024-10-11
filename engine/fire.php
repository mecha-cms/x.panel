<?php

if (class_exists('Layout')) {
    !Layout::path('alert/panel') && Layout::set('alert/panel', __DIR__ . D . 'y' . D . 'alert' . D . 'panel.php');
    !Layout::path('panel') && Layout::set('panel', __DIR__ . D . 'y' . D . 'panel.php');
}

require __DIR__ . D . 'r' . D . 'alert.php';
require __DIR__ . D . 'r' . D . 'file.php';