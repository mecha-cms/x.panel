<?php namespace x\panel\type\tasks;

function button($value, $key) {
    if (isset($value['lot'])) {
        \x\panel\_type_parent_set($value['lot'], 'button');
    }
    $value['tags']['are:buttons'] = true;
    return \x\panel\type\tasks($value, $key);
}

function link($value, $key) {
    if (isset($value['lot'])) {
        \x\panel\_type_parent_set($value['lot'], 'link');
    }
    $value['tags']['are:links'] = true;
    return \x\panel\type\tasks($value, $key);
}