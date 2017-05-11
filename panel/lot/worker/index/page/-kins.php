<?php

if ($__f = Get::pages(Path::D($__folder), 'draft,page,archive', $__sort, 'path')) {
    $__n = Path::B($__path);
    $__f = array_filter($__f, function($__v) use($__n) {
        return Path::N($__v) !== $__n;
    });
    foreach (Anemon::eat($__f)->chunk($__chunk, 0) as $__v) {
        $__kins[0][] = new Page($__v, [], '__page');
        $__kins[1][] = new Page($__v, [], 'page');
    }
    $__is_has_step_kin = count($__f) > $__chunk;
    Lot::set([
        '__kins' => $__kins,
        '__is_has_step_kin' => $__is_has_step_kin
    ]);
}