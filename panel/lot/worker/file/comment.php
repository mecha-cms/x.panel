<?php

// Force `view` value to `page`
require __DIR__ . DS . '..' . DS . ($panel->v = $panel->view = 'page') . DS . 'comment.php';

// Delete comment notification if any
if ($c === 'g' && is_file($file)) {
    if ($notify = File::exist(LOT . DS . '.message' . DS . md5($file) . '.page')) {
        File::open($notify)->delete();
    }
}