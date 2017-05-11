<?php

$__a = o($__sgr === 'g' ? [
    'key' => $__key,
    'content' => File::open($__f)->read()
] : [
    'key' => null,
    'content' => null
]);

Lot::set('__data', [$__a, $__a]);