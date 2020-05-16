<?php namespace _\lot\x\panel\lot;

function desk($in, $key) {
    $out = \_\lot\x\panel\lot($in, $key);
    $out[0] = 'main';
    return $out;
}

function section($in, $key) {
    $out = \_\lot\x\panel\lot($in, $key);
    $out[0] = 'section';
    return $out;
}
