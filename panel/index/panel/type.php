<?php

// Set pre-defined panel type
if (null === $_['type']) {
    if ($_['file']) {
        // `/path/to/file.txt`
        $_['type'] = 'file';
    } else if ($_['folder']) {
        if (!empty($_['part'])) {
            // `/path/to/folder/1`
            $_['type'] = 'files';
        } else {
            // `/path/to/folder`
            $_['type'] = 'folder';
        }
    }
}

// Set
$GLOBALS['_'] = array_replace_recursive($GLOBALS['_'], $_);

is_file($f = __DIR__ . D . 'type' . D . strtr($_['type'] ?? 'blank', '/', D) . '.php') && (static function($f) {
    extract($GLOBALS, EXTR_SKIP);
    if ($_ = require $f) {
        $GLOBALS['_'] = array_replace_recursive($GLOBALS['_'], (array) $_);
    }
})($f);

// Get
$_ = $GLOBALS['_'];