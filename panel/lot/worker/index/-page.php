<?php

if ($__f = File::exist([
    $__folder . '.draft',
    $__folder . '.page',
    $__folder . '.archive'
])) {
    $__page = [
        new Page($__f, [], '__page'),
        new Page($__f, [], 'page')
    ];
} else {
    $__page = [
        new Page(null, [], '__page'),
        new Page(null, [], 'page')
    ];
}

Lot::set('__page', $__page);