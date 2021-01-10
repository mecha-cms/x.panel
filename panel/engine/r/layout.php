<?php

State::set('can.fetch', $state->x->panel->fetch ?? false);

if (is_file($f = __DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'layout' . DS . 'index.php')) {
    require $f;
}

Layout::set('200/panel', __DIR__ . DS . 'layout' . DS . '200.php');
Layout::set('404/panel', __DIR__ . DS . 'layout' . DS . '404.php');
