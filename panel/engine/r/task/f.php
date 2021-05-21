<?php

$GLOBALS['_']['fn'] = $_['fn'] = $id = array_shift($_['chop']);
$GLOBALS['_']['chop'] = $_['chop'];
$GLOBALS['_']['f'] = $_['f'] = stream_resolve_include_path(LOT . DS . implode(DS, $_['chop'])) ?: null;
$GLOBALS['_']['id'] = $_['chop'][0] ?? null;

if (function_exists($fn = "x\\panel\\task\\f\\" . $id)) {
    Hook::set('do.task.' . $id, function() use($fn) {
        return call_user_func($fn, $GLOBALS['_']);
    }, 10);
} else if (is_file($f = __DIR__ . DS . 'f' . DS . $id . '.php')) {
    Hook::set('do.task.' . $id, function() use($f) {
        extract($GLOBALS, EXTR_SKIP);
        return require $f;
    }, 10);
}

if ($r = Hook::fire('do.task.' . $id, [$_])) {
    $_ = $r;
}

$GLOBALS['_'] = $_; // Update data
