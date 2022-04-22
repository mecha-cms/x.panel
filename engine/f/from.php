<?php namespace x\panel\from;

function path($value) {
    return \strtr(\strtr($value, [\PATH . \D => '.' . \D]), '/', "\\");
}