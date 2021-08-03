<?php namespace x\panel\route\__test;

function alert($_) {
    $_['title'] = 'Alerts';
    $_['alert']['error'][] = 'This is an error message.';
    $_['alert']['info'][] = 'This is an info message.';
    $_['alert']['success'][] = 'This is a success message.';
    $_['alert']['success'][] = 'This is another success message.';
    $_['alert']['custom'][] = 'This is a custom message.';
    return $_;
}
