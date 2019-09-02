<?php namespace _\lot\x\panel\Task;

function Button($in, $key) {
    if (isset($in['lot'])) {
        foreach ($in['lot'] as &$v) {
            if (!isset($v['type'])) {
                $v['type'] = 'Button';
            }
        }
    }
    return \_\lot\x\panel\Task($in, $key);
}

function Link($in, $key) {
    if (isset($in['lot'])) {
        foreach ($in['lot'] as &$v) {
            if (!isset($v['type'])) {
                $v['type'] = 'Link';
            }
            $v['tags'][] = 'text';
        }
    }
    return \_\lot\x\panel\Task($in, $key);
}