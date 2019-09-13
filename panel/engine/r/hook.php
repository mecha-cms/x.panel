<?php namespace _\lot\x\panel;

if (\is_file($f = __DIR__ . \DS . 'task' . \DS . $_['task'] . '.php')) {
    require $f;
}

function c() {
    extract($GLOBALS);
    // Normalize path value and remove any `\..` to prevent directory traversal attack
    $path = \str_replace(\DS . '..', "", \strtr($_['path'], '/', \DS));
    if (\stream_resolve_include_path($f = \LOT . $path)) {
        $GLOBALS['_']['f'] = $_['f'] = $f;
    }
    if (\defined("\\DEBUG") && \DEBUG && isset($_GET['test'])) {
        $lot = __DIR__ . \DS . 'state' . \DS . 'test.' . \basename(\urlencode($_GET['test'])) . '.php';
    } else {
        \Config::set('[content].view:' . $_['view'], true);
        $lot = __DIR__ . \DS . 'state' . \DS . $_['view'] . ($_['i'] > 0 ? 's' : "") . '.php';
    }
    (function($lot) {
        extract($GLOBALS, \EXTR_SKIP);
        $GLOBALS['_']['lot'] = $_['lot'] = (array) (\is_file($lot) ? require $lot : []);
        $var = $GLOBALS['_' . ($_SERVER['REQUEST_METHOD'] ?? 'GET')] ?? [];
        if (isset($var['token'])) {
            if ($r = \Hook::fire('on.' . $_['view'] . '.' . ([
                'g' => 'get',
                'l' => 'let',
                's' => 'set'
            ][$_['task']] ?? '?'), [$_, $var])) {
                $GLOBALS['_'] = $_ = $r;
            }
            if (!empty($_['alert'])) {
                foreach ((array) $_['alert'] as $k => $v) {
                    foreach ((array) $v as $alert) {
                        $alert = (array) $alert;
                        \call_user_func("\\Alert::" . $k, ...$alert);
                    }
                }
            }
            if (!empty($_['kick'])) {
                \Guard::kick($_['kick']);
            }
        }
    })($lot);
}

\Hook::set('start', __NAMESPACE__ . "\\c", 10);