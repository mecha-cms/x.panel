<?php

if (is_file($f = __DIR__ . D . '..' . D . '..' . D . 'state' . D . 'file' . D . 'size.php')) {
    State::set('x.panel.guard.file.size', $size = require $f);
    if (!empty($size)) {
        // These setting(s) may not affect the actual maximum file upload size setting in `php.ini` file.
        // I recommend you to change the value by editing the `php.ini` file directly.
        // You can also specify it via `.htaccess` file by adding this line:
        //
        //     php_value upload_max_filesize 125829120
        //
        // <https://www.php.net/manual/en/configuration.changes.php>
        ini_set('upload_max_filesize', $size[1]);
    }
}

if (is_file($f = __DIR__ . D . '..' . D . '..' . D . 'state' . D . 'file' . D . 'type.php')) {
    State::set('x.panel.guard.file.type', require $f);
}

if (is_file($f = __DIR__ . D . '..' . D . '..' . D . 'state' . D . 'file' . D . 'x.php')) {
    State::set('x.panel.guard.file.x', require $f);
}