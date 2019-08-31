<?php namespace _\lot\x\panel\lot;

function Content($in, $key) {
    $out = \_\lot\x\panel\lot($in, $key);
    $out[0] = 'div';
    return $out;
}

function Desk($in, $key) {
    $out = \_\lot\x\panel\lot($in, $key);
    $out[0] = 'main';
    return $out;
}

function Section($in, $key) {
    $out = \_\lot\x\panel\lot($in, $key);
    $out[0] = 'section';
    return $out;
}