<?php

if ($__f = File::exist([
    $__folder . '.draft',
    $__folder . '.page',
    $__folder . '.archive'
])) {
    Lot::set('__source', [
        new Page($__f, [], '__page'),
        new Page($__f, [], 'page')
    ]);
} else {
    Shield::abort(PANEL_404);
}