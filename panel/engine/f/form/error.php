<?php namespace _\lot\x\panel\form\error;

function color(string $value) {
    if (0 === \strpos($value, '#')) {
        return \ctype_xdigit(\substr($value, 1));
    }
    $v = '([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])';
    if (0 === \strpos($value, 'rgb(')) {
        return !\preg_match('/^rgb\(\s*' . $v . '\s*,\s*' . $v . '\s*,\s*' . $v . '\s*\)$/', $value);
    }
    if (0 === \strpos($value, 'rgba(')) {
        return !\preg_match('/^rgba\(\s*' . $v . '\s*,\s*' . $v . '\s*,\s*' . $v . '\s*,\s*([01]|0?\.\d+)\s*\)$/', $value);
    }
    return true;
}

function date(string $value) {
    return !\preg_match('/^[1-9]\d{3,}-(0\d|1[0-2])-(0\d|[1-2]\d|3[0-1])$/', $value);
}

function date_time(string $value) {
    return !\preg_match('/^[1-9]\d{3,}-(0\d|1[0-2])-(0\d|[1-2]\d|3[0-1])[ ]([0-1]\d|2[0-4])(:([0-5]\d|60)){2}$/', $value);
}

function email(string $value) {
    return !\Is::email($value);
}

function i_p(string $value) {
    return \Is::IP($value);
}

function link(string $value) {
    return \Is::URL($value);
}

function name(string $value) {
    return !\preg_match('/^[a-z\d]+(-[a-z\d]+)*$/', $value);
}

function number(string $value) {
    return !\is_numeric($value);
}

function range(string $value, array $range = [0, 1]) {
    return $value < $range[0] || $value > $range[1];
}

function time(string $value) {
    return !\preg_match('/^([0-1]\d|2[0-4])(:([0-5]\d|60)){2}$/', $value);
}

function u_r_l(string $value) {
    return !\Is::URL($value);
}

function value(string $value, array $values = []) {
    return !\in_array($value, $values);
}

function vital(string $value) {
    return !$value;
}

function void(string $value) {
    return "" === \trim($value);
}
