<?php namespace x\panel\type\tasks;

function button($value, $key) {
    if (isset($value['lot'])) {
        $value['lot'] = \x\panel\_type_parent_set($value['lot'], 'button');
    }
    $value['are']['buttons'] = $value['are']['buttons'] ?? true;
    $value[2]['role'] = 'group';
    return \x\panel\type\tasks($value, $key);
}

function link($value, $key) {
    if (isset($value['lot'])) {
        $value['lot'] = \x\panel\_type_parent_set($value['lot'], 'link');
    }
    $value['are']['links'] = $value['are']['links'] ?? true;
    $value[2]['role'] = 'group';
    return \x\panel\type\tasks($value, $key);
}