<?php

// Disable page children
Config::reset('panel.$.page.tools.s');

Hook::set('page.url', function($url) {
    $path = $this->path;
    return $path && strpos($path, USER . DS) === 0 ? $GLOBALS['URL']['$'] . '/' . Extend::state('user', 'path') . '/' . $this->slug : $url;
});

Hook::set('page.title', function($title) {
    $path = $this->path;
    return $path && strpos($path, USER . DS) === 0 ? $this->{'$'} : $title;
});

Hook::set('page.description', function($description) {
    $path = $this->path;
    return $path && strpos($path, USER . DS) === 0 ? '@' . $this->slug : $description;
});