<?php namespace x\panel\task\fire;

function let($_) {
    $_['alert']['info'][] = 'Reserved function name.';
    return $_;
}