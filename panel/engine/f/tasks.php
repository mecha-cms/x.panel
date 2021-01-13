<?php namespace _\lot\x\panel\type\tasks;

function button($value, $key) {
    if (isset($value['lot'])) {
        \_\lot\x\panel\h\p($value['lot'], 'button');
    }
    $value['tags']['lot:task.button'] = true;
    return \_\lot\x\panel\type\tasks($value, $key);
}

function link($value, $key) {
    if (isset($value['lot'])) {
        \_\lot\x\panel\h\p($value['lot'], 'link');
    }
    $value['tags']['lot:task.link'] = true;
    return \_\lot\x\panel\type\tasks($value, $key);
}
