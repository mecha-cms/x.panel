<?php namespace x\panel\type\button;

function _($value, $key) {
    return \x\panel\type\button($value, $key); // Unknown `button` type
}

function button($value, $key) {
    $out = \x\panel\type\button($value, $key);
    $out['type'] = 'button';
    return $out;
}

function link($value, $key) {
    $value['is']['host'] = $value['is']['host'] ?? true;
    $out = \x\panel\type\link($value, $key);
    $out['role'] = 'button';
    return $out;
}

function reset($value, $key) {
    $out = \x\panel\type\button($value, $key);
    $out['type'] = 'reset';
    return $out;
}

function submit($value, $key) {
    $out = \x\panel\type\button($value, $key);
    $out['type'] = 'submit';
    return $out;
}