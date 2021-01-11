<?php namespace _\lot\x\panel\tasks;

function button($in, $key) {
    if (isset($in['lot'])) {
        \_\lot\x\panel\h\p($in['lot'], 'button');
    }
    $in['tags']['lot:task.button'] = 1;
    return \_\lot\x\panel\tasks($in, $key);
}

function link($in, $key) {
    if (isset($in['lot'])) {
        \_\lot\x\panel\h\p($in['lot'], 'link');
    }
    $in['tags']['lot:task.link'] = 1;
    return \_\lot\x\panel\tasks($in, $key);
}
