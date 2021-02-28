<?php namespace _\lot\x\panel\type\tasks;

function button($value, $key) {
    if (isset($value['lot'])) {
        \_\lot\x\panel\_set_type_prefix($value['lot'], 'button');
    }
    $value['tags']['lot:tasks.button'] = true;
    return \_\lot\x\panel\type\tasks($value, $key);
}

function link($value, $key) {
    if (isset($value['lot'])) {
        \_\lot\x\panel\_set_type_prefix($value['lot'], 'link');
    }
    $value['tags']['lot:tasks.link'] = true;
    return \_\lot\x\panel\type\tasks($value, $key);
}
