<?php

$__folder_parent = LOT . DS . $__path;
if ($__action !== 's') {
    $__folder_parent = Path::D($__folder_parent);
}
if ($__file_parent = File::exist([
    $__folder_parent . '.draft',
    $__folder_parent . '.page',
    $__folder_parent . '.archive'
])) {
    $__parent = [
        new Page($__file_parent, [], '__page'),
        new Page($__file_parent, [], 'page')
    ];
    Lot::set('__parent', $__parent);
}