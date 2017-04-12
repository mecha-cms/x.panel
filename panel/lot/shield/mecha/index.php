<?php

$s = __DIR__ . DS . 'asset' . DS . 'css' . DS;

Asset::set([
    $s . 'mecha.min.css',
    $s . 'mecha.c-m.min.css',
    $s . 'mecha.t-i-b.min.css',
    $s . 'mecha.t-p.min.css'
], [
    10.1,
    11.1,
    12.1,
    13.1
]);