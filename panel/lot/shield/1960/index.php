<?php

$s = __DIR__ . DS . 'asset' . DS . 'css' . DS;

Asset::set([
    $s . '1960.css',
    $s . '1960.c-m.min.css',
    $s . '1960.t-i-b.min.css',
    $s . '1960.t-p.min.css'
], [
    10.1,
    11.1,
    12.1,
    13.1
]);