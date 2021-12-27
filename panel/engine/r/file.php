<?php

if (is_file($f = __DIR__ . D . '..' . D . '..' . D . 'state' . D . 'file' . D . 'size.php')) {
    State::set('x.panel.guard.size', $size = require $f);
    if (!empty($size)) {
        ini_set('upload_max_filesize', $size[1]);
    }
}

if (is_file($f = __DIR__ . D . '..' . D . '..' . D . 'state' . D . 'file' . D . 'type.php')) {
    State::set('x.panel.guard.type', require $f);
}

if (is_file($f = __DIR__ . D . '..' . D . '..' . D . 'state' . D . 'file' . D . 'x.php')) {
    State::set('x.panel.guard.x', require $f);
}