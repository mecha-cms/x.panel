<?php namespace _\lot\x\panel\type\lot;

function desk($value, $key) {
    $out = \_\lot\x\panel\type\lot($value, $key);
    $out[0] = 'main';
    return $out;
}

function section($value, $key) {
    $out = \_\lot\x\panel\type\lot($value, $key);
    $out[0] = 'section';
    return $out;
}
