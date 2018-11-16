<?php

require __DIR__ . DS . 'page.php';

// Disable page children feature
Config::reset('panel.+.page.tool.s');
Config::set('panel.error', !!$chops);

Hook::set('page.url', function($url) {
    $path = $this->path;
    return $path && strpos($path, TAG . DS) === 0 ? false : $url;
});