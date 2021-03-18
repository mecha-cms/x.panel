<?php

$GLOBALS['_']['fn'] = $_['fn'] = $fn = array_shift($_['chop']);
$GLOBALS['_']['chop'] = $_['chop'];
$GLOBALS['_']['f'] = $_['f'] = stream_resolve_include_path(LOT . DS . implode(DS, $_['chop'])) ?: null;

if (function_exists($fn = "\\x\\panel\\task\\f\\" . $fn)) {
    Hook::set('do.task.' . $fn, function() use($fn) {
        return call_user_func($fn, $GLOBALS['_']);
    }, 10);
} else if (is_file($_f = __DIR__ . DS . 'f' . DS . $fn . '.php')) {
    Hook::set('do.task.' . $fn, function() use($_f) {
        extract($GLOBALS, EXTR_SKIP);
        return require $_f;
    }, 10);
}

if ($r = Hook::fire('do.task.' . $fn, [$_])) {
    $_ = $r;
}

$GLOBALS['_'] = $_; // Update data
