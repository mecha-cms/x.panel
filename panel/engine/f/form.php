<?php namespace _\lot\x\panel\form;

function get($in, $key) {
    $out = \_\lot\x\panel\form($in, $key);
    $out['method'] = 'get';
    return $out;
}

function post($in, $key) {
    $out = \_\lot\x\panel\form($in, $key);
    if (!isset($out['enctype'])) {
        $out['enctype'] = 'multipart/form-data';
    }
    $out['method'] = 'post';
    return $out;
}
