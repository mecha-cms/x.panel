<?php

foreach (array_reverse(step($_['path'], '/')) as $v) {
    // Function-based route
    if (is_callable($f = "x\\panel\\route\\" . f2p($v))) {
        if ($_ = call_user_func($f, $_)) {
            lot('_', array_replace_recursive(lot('_'), (array) $_));
        }
    // File-based route
    } else if (is_file($f = __DIR__ . D . 'route' . D . strtr($v, '/', D) . '.php')) {
        (static function ($f) {
            extract(lot(), EXTR_SKIP);
            if ($_ = require $f) {
                lot('_', array_replace_recursive(lot('_'), (array) $_));
            }
        })($f);
    }
}

$_ = lot('_');