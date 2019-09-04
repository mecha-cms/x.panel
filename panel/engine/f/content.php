<?php namespace _\lot\x\panel\content;

function Column($in, $key) {
    $out = \_\lot\x\panel\content($in, $key);
    $out[0] = 'div';
    return $out;
}

function Content($in, $key) {
    $out = \_\lot\x\panel\content($in, $key);
    $out[0] = 'div';
    return $out;
}

function Desk($in, $key) {
    $out = \_\lot\x\panel\content($in, $key);
    $out[0] = 'main';
    return $out;
}

function Section($in, $key) {
    $out = \_\lot\x\panel\content($in, $key);
    $out[0] = 'section';
    return $out;
}