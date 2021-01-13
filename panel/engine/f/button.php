<?php namespace _\lot\x\panel\type\button;

function _($value, $key) {
    return \_\lot\x\panel\type\button($value, $key); // Unknown `button` type
}

function button($value, $key) {
    $out = \_\lot\x\panel\type\button($value, $key);
    $out['type'] = 'button';
    return $out;
}

function link($value, $key) {
    $out = \_\lot\x\panel\type\link($value, $key);
    \_\lot\x\panel\h\c($out, [
        'button' => true
    ], $value['tags'] ?? []);
    return $out;
}

function reset($value, $key) {
    $out = \_\lot\x\panel\type\button($value, $key);
    $out['type'] = 'reset';
    return $out;
}

function submit($value, $key) {
    $out = \_\lot\x\panel\type\button($value, $key);
    $out['type'] = 'submit';
    return $out;
}
