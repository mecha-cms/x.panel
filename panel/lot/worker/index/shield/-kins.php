<?php

$__s = isset($__chops[1]) ? $__chops[1] : null;
if ($__f = glob(SHIELD . DS . '*', GLOB_ONLYDIR)) {
    foreach ($__f as $__k => $__v) {
        if (Path::N($__v) === $__s) continue;
        if ($__v = File::exist([
            $__v . DS . 'about.' . $config->language . '.page',
            $__v . DS . 'about.page'
        ])) {
            $__kins[0][] = new Page($__v, [], '__page');
            $__kins[1][] = new Page($__v, [], 'page');
        }
    }
    $__is_has_step_kin = count($__f) > $__chunk;
    Lot::set([
        '__kins' => $__kins,
        '__is_has_step_kin' => $__is_has_step_kin
    ]);
}