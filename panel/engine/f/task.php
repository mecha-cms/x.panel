<?php namespace _\lot\x\panel\Task;

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
    return \_\lot\x\panel\Task($in, $key);
}

function Link($in, $key) {
    if (isset($in['lot'])) {
        foreach ($in['lot'] as &$v) {
            if (!isset($v['type'])) {
                $v['type'] = 'Link';
            } else if ($v['type'] !== 'Link' && \strpos($v['type'], 'Link_') !== 0) {
                $v['type'] = 'Link_' . $v['type'];
            }
            $v['tags'][] = 'text';
        }
    }
    return \_\lot\x\panel\Task($in, $key);
}