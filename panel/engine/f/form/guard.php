<?php namespace _\lot\x\panel\form\guard;

function any(string $value, array $values = []) {
    return \in_array($value, $values);
}

function color(string $value) {
    if (0 === \strpos($value, '#')) {
        $i = \strlen($value) - 1;
        return (
            // `000`
            3 === $i ||
            // `000f`
            4 === $i ||
            // `000000`
            6 === $i ||
            // `000000ff`
            8 === $i
        ) && \ctype_xdigit(\substr($value, 1));
    }
    $v = '([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])';
    if (0 === \strpos($value, 'rgb(')) {
        return pattern($value, '/^rgb\(\s*' . $v . '\s*,\s*' . $v . '\s*,\s*' . $v . '\s*\)$/');
    }
    if (0 === \strpos($value, 'rgba(')) {
        return pattern($value, '/^rgba\(\s*' . $v . '\s*,\s*' . $v . '\s*,\s*' . $v . '\s*,\s*([01]|0?\.\d+)\s*\)$/');
    }
    return false; // Invalid color value
}

function date(string $value) {
    return pattern($value, '/^[1-9]\d{3,}-(0\d|1[0-2])-(0\d|[1-2]\d|3[0-1])$/');
}

function date_time(string $value) {
    return pattern($value, '/^[1-9]\d{3,}-(0\d|1[0-2])-(0\d|[1-2]\d|3[0-1])[ ]([0-1]\d|2[0-4])(:([0-5]\d|60)){2}$/');
}

function email(string $value) {
    return \Is::email($value);
}

function i_p(string $value) {
    return \Is::IP($value);
}

function link(string $value) {
    return \Is::URL($value);
}

function name(string $value) {
    return pattern($value, '/^[a-z\d]+(-[a-z\d]+)*$/');
}

function number(string $value) {
    return \is_numeric($value);
}

function pattern(string $value, string $pattern) {
    return \preg_match($pattern, $value);
}

function range(string $value, array $range = [0, 1]) {
    return $value >= $range[0] && $value <= $range[1];
}

function time(string $value) {
    return pattern($value, '/^([0-1]\d|2[0-4])(:([0-5]\d|60)){2}$/');
}

function u_r_l(string $value) {
    return \Is::URL($value);
}

function void(string $value, $is = true) {
    return $is === ("" === \trim($value));
}
