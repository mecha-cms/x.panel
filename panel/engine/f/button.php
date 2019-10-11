<?php namespace _\lot\x\panel;

function Button__($in, $key) {
    return \_\lot\x\panel\Button($in, $key); // Unknown `Button` type
}

function Button__Button($in, $key) {
    $out = \_\lot\x\panel\Button($in, $key);
    $out['type'] = 'button';
    return $out;
}

function Button__Link($in, $key) {
    $out = \_\lot\x\panel\Link($in, $key);
    \_\lot\x\panel\h\c($out, $in, ['button']);
    return $out;
}

function Button__Reset($in, $key) {
    $out = \_\lot\x\panel\Button($in, $key);
    $out['type'] = 'reset';
    return $out;
}

function Button__Submit($in, $key) {
    $out = \_\lot\x\panel\Button($in, $key);
    $out['type'] = 'submit';
    return $out;
}