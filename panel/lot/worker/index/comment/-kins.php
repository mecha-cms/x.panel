<?php

if ($__f = Get::pages(Path::D($__folder), 'draft,page', $__sort, 'path')) {
    $__n = Path::B($__path);
    $__f = array_filter($__f, function($__v) use($__n) {
        return Path::N($__v) !== $__n;
    });
    foreach (Anemon::eat($__f)->chunk($__chunk, 0) as $__v) {
        $__kins[0][] = new Comment($__v, [], '__comment');
        $__kins[1][] = new Comment($__v);
    }
    $__is_has_step_kin = count($__f) > $__chunk;
    Lot::set([
        '__kins' => $__kins,
        '__is_has_step_kin' => $__is_has_step_kin
    ]);
}