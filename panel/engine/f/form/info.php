<?php namespace _\lot\x\panel\form\info;

function description(string $value) {
    return \_\lot\x\panel\to\description($value);
}

function file(string $value) {
    return \To::file($value);
}

function folder(string $value) {
    return \To::folder($value);
}

function name(string $value) {
    return \strtr(\w($value), ' ', '-');
}

function title(string $value) {
    return \_\lot\x\panel\to\title($value);
}

function w(string $value) {
    return \_\lot\x\panel\to\w($value);
}
