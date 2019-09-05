<?php namespace _\lot\x\panel;

function route($form, $k) {
    extract($GLOBALS, \EXTR_SKIP);
    $GLOBALS['t'][] = 'Panel';
    if (is_file($panel['config'] ?? null)) {
        \Config::set('panel', require $panel['config']);
    }
    $this->content(__DIR__ . DS . 'content' . DS . 'panel.php');
}

if (\defined("\\DEBUG") && \DEBUG && isset($_GET['test'])) {
    $c = __DIR__ . \DS . 'state' . \DS . 'test.' . \basename(\urlencode($_GET['test'])) . '.php';
} else {
    $c = __DIR__ . \DS . 'state' . \DS . 'file' . (isset($panel['i']) ? 's' : "") . '.php';
}

$GLOBALS['panel']['config'] = $panel['config'] = $c;

\Route::set(\state('panel')['//'] . '/*', __NAMESPACE__ . "\\route", 1);