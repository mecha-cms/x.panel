<?php

if ($url->path === $state['path'] || strpos($url->path . '/', $state['path'] . '/') === 0) {
    Asset::reset();
    require PANEL . DS . 'lot' . DS . 'worker' . DS . 'task.main.php';
}