<?php

if ($__f = glob(LOT . DS . $__path . DS . '*')) {
    foreach ($__f as $__k => $__v) {
        $__b = str_replace(SHIELD . DS . $__chops[1] . DS, "", To::path($__v));
        if (substr($__b, -6) === '.trash') continue;
        $__a = [
            'title' => Path::B($__b),
            'path' => $__v,
            'extension' => Path::X($__v),
            'url' => $url . '/' . $__state->path . '/::g::/' . $__chops[0] . '/' . $__chops[1] . '/' . $__b
        ];
        $__datas[0][] = (object) $__a;
        $__datas[1][] = (object) $__a;
    }
    Lot::set('__datas', $__datas);
}