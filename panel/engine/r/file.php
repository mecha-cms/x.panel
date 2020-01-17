<?php

$state = State::get('x.panel.guard', true);

File::$state['type'] = array_replace(File::$state['type'] ?? [], $state['type'] ?? []);
File::$state['x'] = array_replace(File::$state['x'] ?? [], $state['x'] ?? []);

if (!empty($state['size'])) {
    File::$state['size'] = array_replace(File::$state['size'] ?? [], $state['size']);
    ini_set('upload_max_filesize', $state['size'][1]);
}
