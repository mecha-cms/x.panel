<?php namespace _\lot\x\panel\task\f;

$GLOBALS['_']['fn'] = $fn = \array_shift($_['chops']);
$GLOBALS['_']['chops'] = $_['chops'];
$GLOBALS['_']['f'] = \stream_resolve_include_path(\LOT . \DS . \implode(\DS, $_['chops'])) ?: null;

if (\is_file($_f = __DIR__ . \DS . 'f' . \DS . $fn . '.php')) {
    $_ = (function($_f) {
        extract($GLOBALS, \EXTR_SKIP);
        $lot = \e($GLOBALS['_' . ($_SERVER['REQUEST_METHOD'] ?? 'GET')] ?? []);
        return require $_f;
    })($_f);
} else if (\function_exists($fn = "\\_\\lot\\x\\panel\\task\\f\\" . $fn)) {
    $_ = \call_user_func($fn, $_, \e($GLOBALS['_' . ($_SERVER['REQUEST_METHOD'] ?? 'GET')] ?? []));
}

$GLOBALS['_'] = $_; // Update data