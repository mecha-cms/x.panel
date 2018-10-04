<?php

if (Is::void($name)) {
    panel\message('error', 'Please fill out the key field!');
}

Hook::set('guardian.kick', function($url) {
    if (!Message::$x) {
        if ($x = HTTP::get('x')) {
            return dirname($url) . '.' . $x . To::query(['tab' => ['data']]);
        }
        return $url;
    }
}, 0);