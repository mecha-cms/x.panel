<?php

$__folder_page = PAGE . DS . Path::F(Path::D($__path), $__chops[0]);
if ($__f = File::exist([
    $__folder_page . '.draft',
    $__folder_page . '.page',
    $__folder_page . '.archive'
])) {
    Lot::set('__source', [
        new Page($__f, [], '__page'),
        new Page($__f, [], 'page')
    ]);
}