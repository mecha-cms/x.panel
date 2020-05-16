<?php namespace _\lot\x\panel\content;

function desk($in, $key) {
    $out = \_\lot\x\panel\content($in, $key);
    $out[0] = 'main';
    return $out;
}

function section($in, $key) {
    $out = \_\lot\x\panel\content($in, $key);
    $out[0] = 'section';
    return $out;
}
