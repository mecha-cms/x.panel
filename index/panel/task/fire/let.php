<?php namespace x\panel\task\fire;

function let($_) {
    // Abort by previous hook’s return value if any
    if (isset($_['kick']) || !empty($_['alert']['error']) || $_['status'] >= 400) {
        return $_;
    }
    $_['alert']['info'][] = 'Reserved function name.';
    return $_;
}