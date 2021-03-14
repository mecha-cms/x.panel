<?php namespace x\panel\type\form;

function get($value, $key) {
    $out = \x\panel\type\form($value, $key);
    $out['method'] = 'get';
    return $out;
}

function post($value, $key) {
    $out = \x\panel\type\form($value, $key);
    if (!isset($out['enctype'])) {
        $out['enctype'] = 'multipart/form-data';
    }
    $out['method'] = 'post';
    return $out;
}
