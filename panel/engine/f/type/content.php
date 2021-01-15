<?php namespace _\lot\x\panel\type\content;

function desk($value, $key) {
    $out = \_\lot\x\panel\type\content($value, $key);
    $out[0] = 'main';
    return $out;
}

function section($value, $key) {
    $out = \_\lot\x\panel\type\content($value, $key);
    $out[0] = 'section';
    return $out;
}
