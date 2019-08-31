<?php namespace _\lot\x\panel\Button;

function Button($in, $key) {
    $out = \_\lot\x\panel\Button($in, $key);
    $out['type'] = 'button';
    return $out;
}

function Link($in, $key) {
    $in['tags'][] = 'button';
    $out = \_\lot\x\panel\Link($in, $key);
    return $out;
}

function Reset($in, $key) {
    $out = \_\lot\x\panel\Button($in, $key);
    $out['type'] = 'reset';
    return $out;
}

function Submit($in, $key) {
    $out = \_\lot\x\panel\Button($in, $key);
    $out['type'] = 'submit';
    return $out;
}