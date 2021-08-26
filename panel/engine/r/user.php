<?php

// $status = $user['status'] ?? -1; // Buggy :(
$status = $user->status ?? -1;
if (is_file($f = __DIR__ . DS . '..' . DS . '..' . DS . 'state' . DS . 'user' . DS . $status . '.php')) {
    State::set('x.panel.guard.status.' . $status, (array) require $f);
}

// Load user rule(s) from a file stored in `.\lot\x\*\state\user\*.php`
foreach ($GLOBALS['X'][1] as $v) {
    is_file($v = dirname($v) . DS . 'state' . DS . 'user' . DS . $status . '.php') && (static function($v, $status) {
        extract($GLOBALS, EXTR_SKIP);
        State::set('x.panel.guard.status.' . $status, (array) require $v);
    })($v, $status);
}

// Load user rule(s) from a file stored in `.\lot\layout\state\user\*.php`
is_file($v = LOT . DS . 'layout' . DS . 'state' . DS . 'user' . DS . $status . '.php') && (static function($v, $status) {
    extract($GLOBALS, EXTR_SKIP);
    State::set('x.panel.guard.status.' . $status, (array) require $v);
})($v, $status);