<?php namespace x\panel\from;

function path($value) {
    return \strtr(\strtr($value, [\PATH . \D => '.' . \D]), '/', "\\");
}

function tags($value) {
    // `[0, 1, 2]`
    if (\array_keys($value) === \range(0, \count($value) - 1)) {
        return $value;
    }
    // `{0: true, 1: true, 2: true}`
    return \array_keys(\array_filter($value));
}