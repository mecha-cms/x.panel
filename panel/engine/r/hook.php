<?php namespace _\lot\x\panel;

function c() {
    extract($GLOBALS, \EXTR_SKIP);
    if (\defined("\\DEBUG") && \DEBUG && isset($_GET['test'])) {
        $c = __DIR__ . \DS . 'state' . \DS . 'test.' . \basename(\urlencode($_GET['test'])) . '.php';
    } else {
        $type = $PANEL['view'] . (isset($PANEL['i']) ? 's' : "");
        \Config::set('[content].view:' . $type, true);
        $c = __DIR__ . \DS . 'state' . \DS . $type . '.php';
    }
    $GLOBALS['PANEL']['lot'] = \is_file($c) ? require $c : [];
}

\Hook::set('start', __NAMESPACE__ . "\\c", 10);