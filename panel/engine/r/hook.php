<?php namespace _\lot\x\panel;

function c() {
    extract($GLOBALS);
    if (\stream_resolve_include_path($f = \LOT . \strtr($PANEL['path'], '/', \DS))) {
        $GLOBALS['PANEL']['file']['path'] = $f;
        $GLOBALS['PANEL']['file']['type'] = \mime_content_type($f);
        $GLOBALS['PANEL']['view'] = $PANEL['view'] = \is_dir($f) ? 'folder' : 'file';
    }
    if (\defined("\\DEBUG") && \DEBUG && isset($_GET['test'])) {
        $c = __DIR__ . \DS . 'state' . \DS . 'test.' . \basename(\urlencode($_GET['test'])) . '.php';
    } else {
        $type = $PANEL['view'] . (isset($PANEL['i']) ? 's' : "");
        \Config::set('[content].view:' . $type, true);
        $c = __DIR__ . \DS . 'state' . \DS . $type . '.php';
    }
    (function($c) {
        extract($GLOBALS, \EXTR_SKIP);
        $GLOBALS['PANEL']['lot'] = \is_file($c) ? require $c : [];
        if (\is_file($f = __DIR__ . \DS . \strtolower($_SERVER['REQUEST_METHOD']) . '.php')) {
            require $f;
        }
    })($c);
}

\Hook::set('start', __NAMESPACE__ . "\\c", 10);