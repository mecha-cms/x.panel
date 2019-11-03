<?php

if (is_file($f = __DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'layout' . DS . 'index.php')) {
    require $f;
}

Layout::set('panel', __DIR__ . DS . 'layout' . DS . 'panel.php');
Layout::set('panel.404', __DIR__ . DS . 'layout' . DS . '404.php');