<?php

State::set('can.fetch', $state->x->panel->fetch ?? false);

if (is_file($f = __DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'layout' . DS . 'index.php')) {
    require $f;
}

Layout::set('panel', __DIR__ . DS . 'layout' . DS . 'panel.php');
Layout::set('404/panel', __DIR__ . DS . 'layout' . DS . '404.php');
