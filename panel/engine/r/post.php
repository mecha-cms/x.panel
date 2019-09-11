<?php

$prev = LOT . DS . $PANEL['path'];

// Rename
$name = $_POST['file']['name'] ?? null;
if ($PANEL['task'] === 'g' && basename($PANEL['path']) !== $name) {
    $next = dirname($prev) . DS . $name;
    if (stream_resolve_include_path($next)) {
        Alert::error('File already exists.');
    } else if (rename($prev, $next) !== false) {
        Alert::success('Updated.');
        Guard::kick($url . $PANEL['//'] . '/::' . $PANEL['task'] . '::' . dirname($PANEL['path']) . '/' . $name . $url->query);
    } else {
        Alert::error('Cannot rename file.');
    }
} else {
    Alert::success('Updated.');
}