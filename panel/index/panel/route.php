<?php

foreach (array_reverse(step($_['path'], '/')) as $v) {
    // Function-based route
    if (is_callable($f = "x\\panel\\route\\" . f2p($v))) {
        if ($_ = call_user_func($f, $_)) {
            $GLOBALS['_'] = array_replace_recursive($GLOBALS['_'], (array) $_);
        }
    // File-based route
    } else if (is_file($f = __DIR__ . D . 'route' . D . strtr($v, '/', D) . '.php')) {
        (static function($f) {
            extract($GLOBALS, EXTR_SKIP);
            if ($_ = require $f) {
                $GLOBALS['_'] = array_replace_recursive($GLOBALS['_'], (array) $_);
            }
        })($f);
    }
}

$_ = $GLOBALS['_'];