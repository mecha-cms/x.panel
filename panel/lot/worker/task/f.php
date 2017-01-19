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
    // Process special field name …
    if (isset($_POST['slug'])) {
        $_POST['slug'] = $s = To::slug($_POST['slug']);
        if ($s === '--') {
            Message::error('Please fill out the slug field.');
        }
    }
    if (isset($_POST['key'])) {
        $_POST['key'] = $s = To::key($_POST['key']);
        if ($s === '__') {
            Message::error('Please fill out the key field.');
        }
    }
    if (isset($_POST['x'])) {
        $_POST['x'] = $s = l($_POST['x']);
        if (!Is::these(File::$config['extensions'])->has($s)) {
            Message::error('Extension <code>' . $s . '</code> is not allowed.');
        }
    }
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