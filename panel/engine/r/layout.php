<?php

Layout::set('200/panel', __DIR__ . DS . 'layout' . DS . '200.php');
Layout::set('404/panel', __DIR__ . DS . 'layout' . DS . '404.php');

$d = __DIR__ . DS . '..' . DS . '..' . DS . 'lot' . DS . 'layout';

is_file($f = $d . DS . 'index' . DS . 'panel.php') && (static function() use($f) {
    extract($GLOBALS, EXTR_SKIP);
    require $f;
    if (isset($_) && is_array($_)) {
        $GLOBALS['_'] = array_replace_recursive($GLOBALS['_'] ?? [], $_);
    }
})();

is_file($f = $d . DS . 'index.php') && (static function() use($f) {
    extract($GLOBALS, EXTR_SKIP);
    require $f;
    if (isset($_) && is_array($_)) {
        $GLOBALS['_'] = array_replace_recursive($GLOBALS['_'] ?? [], $_);
    }
})();
