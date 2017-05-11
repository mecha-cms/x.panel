<?php

if ($__f = g($__folder, 'data')) {
    $__has_tag = Extend::exist('tag');
    foreach (/* Anemon::eat($__f)->chunk($__chunk, 0) */ $__f as $__k => $__v) {
        $__n = Path::N($__v);
        if (
            $__n === $__key ||
            $__n === 'chunk' ||
            $__n === 'css' ||
            $__n === 'id' ||
            $__n === 'js' ||
            $__n === 'kind' && $__has_tag ||
            $__n === 'slug' ||
            $__n === 'sort' ||
            $__n === 'state' ||
            $__n === 'time'
        ) continue;
        $__a = [
            'title' => $__n,
            'key' => $__n,
            'url' => $__state->path . '/::g::/' . rtrim(explode('/+/', $__path . '/')[0], '/') . '/+/' . $__n
        ];
        $__datas[0][] = (object) $__a;
        $__datas[1][] = (object) $__a;
    }
    $__is_has_step_data = /* count($__f) > $__chunk */ false;
    Lot::set([
        '__datas' => $__datas,
        '__is_has_step_data' => $__is_has_step_data
    ]);
}