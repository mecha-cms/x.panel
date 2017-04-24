<?php

if ($__f = Get::pages($__folder, 'draft,page,archive', $__sort, 'path')) {
    foreach (Anemon::eat($__f)->chunk($__chunk, 0) as $__v) {
        $__childs[0][] = new Page($__v, [], '__page');
        $__childs[1][] = new Page($__v, [], 'page');
    }
    $__is_has_step_child = count($__f) > $__chunk;
    Lot::set([
        '__childs' => $__childs,
        '__is_has_step_child' => $__is_has_step_child
    ]);
}