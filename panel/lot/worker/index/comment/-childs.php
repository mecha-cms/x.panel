<?php

if ($__f = glob(LOT . DS . Path::D($__path) . DS . '*' . DS . 'parent.data')) {
    $__slug = end($__chops);
    foreach (Anemon::eat($__f)->is(function($__v) use($__slug) {
        return file_get_contents($__v) === $__slug;
    })->chunk($__chunk, 0) as $__v) {
        $__ff = Path::D($__v);
        if ($__ff = File::exist([
            $__ff . '.draft',
            $__ff . '.page'
        ])) {
            $__childs[0][] = new Comment($__ff, [], '__comment');
            $__childs[1][] = new Comment($__ff);
        }
    }
    $__is_has_step_child = count($__f) > $__chunk;
    Lot::set([
        '__childs' => $__childs,
        '__is_has_step_child' => $__is_has_step_child
    ]);
}