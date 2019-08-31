<?php namespace _\lot\x\panel\Form;

function Get($in, $key) {
    $out = \_\lot\x\panel\Form($in, $key);
    $out['method'] = 'get';
    return $out;
}

function Post($in, $key) {
    $out = \_\lot\x\panel\Form($in, $key);
    if (!isset($out['enctype'])) {
        $out['enctype'] = 'multipart/form-data';
    }
    $out['method'] = 'post';
    return $out;
}