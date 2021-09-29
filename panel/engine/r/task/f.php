<?php

$GLOBALS['_']['fn'] = $_['fn'] = $name = array_shift($_['chop']);
$GLOBALS['_']['chop'] = $_['chop'];
$GLOBALS['_']['f'] = $_['f'] = stream_resolve_include_path(LOT . DS . implode(DS, $_['chop'])) ?: null;
$GLOBALS['_']['id'] = $_['chop'][0] ?? null;

if (function_exists($fn = "x\\panel\\task\\f\\" . strtolower(f2p(strtr($name, '-', '_'))))) {
    Hook::set('do.task.' . $name, function() use($fn) {
        return call_user_func($fn, $GLOBALS['_']);
    }, 10);
} else if (is_file($f = __DIR__ . DS . 'f' . DS . $name . '.php')) {
    Hook::set('do.task.' . $name, function() use($f) {
        extract($GLOBALS, EXTR_SKIP);
        return require $f;
    }, 10);
}

if ($r = Hook::fire('do.task.' . $name, [$_])) {
    $_ = $r;
}

$GLOBALS['_'] = $_; // Update data