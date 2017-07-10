<?php

$__a = o($__action === 'g' ? [
    'key' => $__key,
    'content' => File::open($__f)->read()
] : [
    'key' => null,
    'content' => null
]);

Lot::set('__data', [$__a, $__a]);