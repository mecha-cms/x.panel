<?php

if (!function_exists('fn_f_reset')) {
    function fn_f_reset($input, $fn) {
        foreach ($input as $k => $v) {
            if (is_array($v)) {
                $input[$k] = fn_f_reset($v, $fn);
            } else {
                if ($fn($v, $k)) {
                    unset($input[$k]);
                }
            }
        }
        return $input;
    }
}

if (Request::is('post')) {
    // Sanitize input …
    $_POST = fn_f_reset($_POST, function($v) {
        return is_string($v) && !trim($v) || is_array($v) && empty($v) || is_object($v) && empty((array) $v);
    });
    // Sanitize by user …
    if (isset($_POST['::f::']) && is_callable($_POST['::f::'])) {
        $_POST = call_user_func($_POST['::f::'], $_POST);
    }
    // Process token …
    if (!isset($_POST['token']) || $_POST['token'] !== Session::get(Guardian::$config['session']['token'])) {
        Message::error('Invalid token.');
        Guardian::kick($url->current);
    }
    // Remove `token` from `$_POST`
    unset($_POST['token']);
}