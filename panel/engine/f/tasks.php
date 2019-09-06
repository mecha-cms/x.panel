<?php namespace _\lot\x\panel\Tasks;

function Button($in, $key) {
    if (isset($in['lot'])) {
        \_\lot\x\panel\h\p($in['lot'], 'Button');
    }
    $in['tags'][] = 'lot:task.button';
    return \_\lot\x\panel\Tasks($in, $key);
}

function Link($in, $key) {
    if (isset($in['lot'])) {
        \_\lot\x\panel\h\p($in['lot'], 'Link');
    }
    $in['tags'][] = 'lot:task.link';
    return \_\lot\x\panel\Tasks($in, $key);
}