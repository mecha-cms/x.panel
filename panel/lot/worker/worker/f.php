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
    // Process special field name…
    if (Request::is('post', 'time')) {
        $s = Request::post('time');
        $format = '#^(\d{4,}/\d{2}/\d{2}|\d{4,}\-\d{2}\-\d{2}) \d{2}:\d{2}:\d{2}$#';
        if (!is_string($s) || !preg_match($format, $s)) {
            Request::save('post');
            Message::error('pattern_field', $language->time);
        }
    }
    if (Request::is('post', 'slug')) {
        $s = Request::post('slug', "");
        Request::set('post', 'slug', $s = trim(To::slug($s), '-'));
        if (!$s) {
            Request::save('post');
            Message::error('void_field', $language->slug, true);
        }
    }
    if (Request::is('post', 'key')) {
        $s = Request::post('key', "");
        Request::set('post', 'key', $s = trim(To::key($s), '_'));
        if (!$s) {
            Request::save('post');
            Message::error('void_field', $language->key, true);
        }
    }
    if (Request::is('post', 'x')) {
        $s = Request::post('x', "");
        Request::set('post', 'x', $s = l($s));
        if (!Is::these(File::$config['extensions'])->has($s)) {
            Request::save('post');
            Message::error('file_x', $s);
        }
    }
    // Remove empty request value(s) …
    $s = fn_f_reset(Request::post(null, []), function($v) {
        return is_string($v) && !trim($v) || is_array($v) && empty($v) || is_object($v) && empty((array) $v);
    });
    Request::reset('post')->extend('post', $s);
    // Sanitize by user …
    $s = Request::post('::f::');
    if ($s && is_callable($s)) {
        $s = call_user_func($s, Request::post(null, []));
        Request::reset('post')->extend('post', $s);
    }
    // Process token …
    $s = Request::post('token');
    if (!$s || $s !== Session::get(Guardian::$config['session']['token'])) {
        Message::error('token');
        Guardian::kick();
    }
} else if (Request::is('get')) {
    // Process token …
    $s = Request::get('token');
    if ($s && $s !== Session::get(Guardian::$config['session']['token'])) {
        Message::error('token');
    }
}

$__f = (array) Panel::get('f', []); // hold!

asort($__f);