<?php

if ($url->path === $state['path'] || strpos($url->path . '/', $state['path'] . '/') === 0) {
    require PANEL . DS . 'lot' . DS . 'worker' . DS . 'task' . DS . 'route.php';
}