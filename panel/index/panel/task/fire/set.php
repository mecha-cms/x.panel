<?php namespace x\panel\task\fire;

function get($_) {
    $_['alert']['info'][] = 'Reserved function name.';
    return $_;
}