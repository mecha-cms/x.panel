<?php namespace x\panel\type\input;

// TODO

function button($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'button';
    return $out;
}

function checkbox($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'checkbox';
    return $out;
}

function color($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'color';
    return $out;
}

function date($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'date';
    return $out;
}

function datetime_local($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'datetime-local';
    return $out;
}

function email($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'email';
    return $out;
}

function file($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'file';
    return $out;
}

function hidden($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'hidden';
    return $out;
}

function image($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'image';
    return $out;
}

function month($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'month';
    return $out;
}

function number($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'number';
    return $out;
}

function password($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'password';
    return $out;
}

function radio($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'radio';
    return $out;
}

function range($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'range';
    return $out;
}

function reset($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'reset';
    return $out;
}

function search($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'search';
    return $out;
}

function submit($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'submit';
    return $out;
}

function tel($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'tel';
    return $out;
}

function text($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'text';
    return $out;
}

function time($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'time';
    return $out;
}

function url($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'url';
    return $out;
}

function week($value, $key) {
    $out = \x\panel\type\input($value, $key);
    $out['type'] = 'week';
    return $out;
}