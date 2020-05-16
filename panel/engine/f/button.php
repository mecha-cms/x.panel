<?php namespace _\lot\x\panel\button;

function _($in, $key) {
    return \_\lot\x\panel\button($in, $key); // Unknown `button` type
}

function button($in, $key) {
    $out = \_\lot\x\panel\button($in, $key);
    $out['type'] = 'button';
    return $out;
}

function link($in, $key) {
    $out = \_\lot\x\panel\link($in, $key);
    \_\lot\x\panel\h\c($out, $in, ['button']);
    return $out;
}

function reset($in, $key) {
    $out = \_\lot\x\panel\button($in, $key);
    $out['type'] = 'reset';
    return $out;
}

function submit($in, $key) {
    $out = \_\lot\x\panel\button($in, $key);
    $out['type'] = 'submit';
    return $out;
}
