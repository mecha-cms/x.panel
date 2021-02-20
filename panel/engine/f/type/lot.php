<?php namespace _\lot\x\panel\type\lot;

function desk($value, $key) {
    $styles = $tags = [];
    if (isset($value['width']) && false !== $value['width']) {
        $tags['width'] = true;
        if (true !== $value['width']) {
            $styles['width'] = $value['width'];
        }
    }
    \_\lot\x\panel\_set_class($value[2], $tags);
    \_\lot\x\panel\_set_style($value[2], $styles);
    $out = \_\lot\x\panel\type\lot($value, $key);
    $out[0] = 'main';
    return $out;
}

function section($value, $key) {
    $out = \_\lot\x\panel\type\lot($value, $key);
    $out[0] = 'section';
    return $out;
}
