<?php

if (is_file($f = __DIR__ . DS . '..' . DS . '..' . DS . 'state' . DS . 'type.php')) {
    File::$state['type'] = array_replace(File::$state['type'] ?? [], $data = require $f);
    State::set('x.panel.guard.type', $data);
}

if (is_file($f = __DIR__ . DS . '..' . DS . '..' . DS . 'state' . DS . 'x.php')) {
    File::$state['x'] = array_replace(File::$state['x'] ?? [], $data = require $f);
    State::set('x.panel.guard.x', $data);
}

$state = State::get('x.panel.guard', true);

if (!empty($state['size'])) {
    File::$state['size'] = array_replace(File::$state['size'] ?? [], $state['size']);
    ini_set('upload_max_filesize', $state['size'][1]);
}
