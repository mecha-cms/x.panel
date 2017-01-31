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
    // Electronic mail
    if (Request::is('post', 'email')) {
        if ($s = Request::post('email', "", false)) {
            if (!Is::email($s)) {
                Request::save('post');
                Message::error('value', $language->email, true);
            }
        }
    }
    // Internet protocol
    if (Request::is('post', 'ip')) {
        if ($s = Request::post('ip', "", false)) {
            if (!Is::ip($s)) {
                Request::save('post');
                Message::error('value', $language->ip, true);
            }
        }
    }
    // Object key
    if (Request::is('post', 'key')) {
        $s = Request::post('key', "", false);
        Request::set('post', 'key', $s = trim(To::key($s), '_'));
        if ($s === "") {
            Request::save('post');
            Message::error('void_field', $language->key, true);
        }
    }
    // Uniform resource locator
    if (Request::is('post', 'link')) {
        if ($s = Request::post('link', "", false)) {
            if (strpos($s, '//') === 0) {
                $s = $url->scheme . ':' . $s;
            }
            if (!Is::url($s)) {
                Request::save('post');
                Message::error('value', $language->link, true);
            }
        }
    }
    // Comma–separated quer(y|ies)
    if (Request::is('post', 'query')) {
        if ($s = Request::post('query', [], false)) {
            if (is_string($s)) {
                $s = array_unique(preg_split('#\s*,\s*#', $s, null, PREG_SPLIT_NO_EMPTY));
            }
            Request::set('post', 'query', (array) $s);
        }
    }
    // File name
    if (Request::is('post', 'slug')) {
        $s = Request::post('slug', "", false);
        Request::set('post', 'slug', $s = trim(To::slug($s), '-'));
        if ($s === "") {
            Request::save('post');
            Message::error('void_field', $language->slug, true);
        }
    }
    // Time pattern: `YYYY/MM/DD hh:mm:ss`
    if (Request::is('post', 'time')) {
        $s = Request::post('time', "", false);
        $format = '#^(\d{4,}/\d{2}/\d{2}|\d{4,}\-\d{2}\-\d{2}) \d{2}:\d{2}:\d{2}$#';
        if (!is_string($s) || !preg_match($format, $s)) {
            Request::save('post');
            Message::error('pattern_field', $language->time, true);
        }
    }
    // Uniform resource locator
    if (Request::is('post', 'url')) {
        if ($s = Request::post('url', "", false)) {
            if (strpos($s, '//') === 0) {
                $s = $url->scheme . ':' . $s;
            }
            if (!Is::url($s)) {
                Request::save('post');
                Message::error('value', $language->url, true);
            }
        }
    }
    // File extension
    if (Request::is('post', 'x')) {
        $s = Request::post('x', "", false);
        if ($s === "") {
            Request::save('post');
            Message::error('void_field', $language->extension, true);
        }
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
        Request::save('post');
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