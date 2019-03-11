<?php

Set::post('file.consent', $consent = 0600);

if (Is::void($name)) {
    Message::error('void_field', ['<em>' . $language->key . '</em>']);
}

/*
Hook::set('guard.kick', function($url) {
    if (!Message::$x) {
        if ($x = HTTP::get('x')) {
            return dirname($url) . '.' . $x . To::query(['tab' => ['data']]);
        }
        return $url;
    }
}, 0);
*/