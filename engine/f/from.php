<?php namespace x\panel\from;

function color($color) {
    // TODO
}

function path($value) {
    return \strtr(\strtr($value, [\PATH . \D => '.' . \D]), '/', "\\");
}