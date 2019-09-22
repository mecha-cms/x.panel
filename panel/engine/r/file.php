<?php

File::$config['type'] = array_replace(File::$config['type'] ?? [], state('panel', 'type'));
File::$config['x'] = array_replace(File::$config['x'] ?? [], state('panel', 'x'));

if ($size = state('panel', 'size')) {
    File::$config['size'] = array_replace(File::$config['size'], $size);
    ini_set('upload_max_filesize', $size[1]);
}