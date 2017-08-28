<?php

$s = __DIR__ . DS . 'asset' . DS . 'css' . DS;

Asset::set([
    $s . 'construction.min.css',
    $s . 'construction.c-m.min.css',
    $s . 'construction.t-i-b.min.css',
    $s . 'construction.t-p.min.css'
], [
    20,
    20.1,
    20.11,
    20.12
]);