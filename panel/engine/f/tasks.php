<?php namespace _\lot\x\panel\Tasks;

function Button($in, $key) {
    if (isset($in['lot'])) {
        foreach ($in['lot'] as &$v) {
            if (!isset($v['type'])) {
                $v['type'] = 'Button';
            } else if ($v['type'] !== 'Button' && \strpos($v['type'], 'Button_') !== 0) {
                $v['type'] = 'Button_' . $v['type'];
            }
        }
    }
    return \_\lot\x\panel\Tasks($in, $key);
}

function Link($in, $key) {
    if (isset($in['lot'])) {
        \_\lot\x\panel\h\p($in['lot'], 'Link');
        foreach ($in['lot'] as &$v) {
            $v['tags'][] = 'is:link';
        }
    }
    return \_\lot\x\panel\Tasks($in, $key);
}