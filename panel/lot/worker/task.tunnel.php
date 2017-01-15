<?php

if (!function_exists('fn_array_walk_reset')) {
    function fn_array_walk_reset($input, $fn) {
        foreach ($input as $k => $v) {
            if (is_array($v)) {
                $input[$k] = fn_array_walk_reset($v, $fn);
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
    if (!isset($_POST[':fn'])) {
        $_POST = fn_array_walk_reset($_POST, function($v) {
            return is_string($v) && !trim($v) || is_array($v) && empty($v) || is_object($v) && empty((array) $v);
        });
    } else {
        $_POST = call_user_func($_POST[':fn'], $_POST);
    }
}