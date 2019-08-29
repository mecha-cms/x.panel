<?php namespace _\lot\x\panel\form;

function get($in, $key, $type) {
    $out = \_\lot\x\panel\form($in, $key, $type);
    $out['method'] = 'get';
    return $out;
}

function post($in, $key, $type) {
    $out = \_\lot\x\panel\form($in, $key, $type);
    $out['method'] = 'post';
    return $out;
}