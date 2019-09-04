<?php namespace _\lot\x\panel\lot;

function Column($in, $key) {
    $out = \_\lot\x\panel\lot($in, $key);
    $out[0] = 'div';
    return $out;
}

function Content($in, $key) {
    $out = \_\lot\x\panel\lot($in, $key);
    $out[0] = 'div';
    return $out;
}

function Desk($in, $key) {
    $out = \_\lot\x\panel\lot($in, $key);
    $out[0] = 'main';
    return $out;
}

function Section($in, $key) {
    $columns = [];
    if (isset($in['lot'])) {
        foreach ($in['lot'] as $k => $v) {
            if (isset($v['type']) && $v['type'] === 'Column') {
                $columns[] = $v;
                unset($in['lot'][$k]);
            }
        }
    }
    $out = \_\lot\x\panel\lot($in, $key);
    $out[0] = 'section';
    if (!empty($columns)) {
        $out[1] .= '<div class="lot lot:row p">' . \_\lot\x\panel\h\lot($columns) . '</div>';
    }
    $class = \explode(' ', $out['class']);
    if (!empty($columns)) {
        $class[] = 'has-column';
        $class[] = 'size-' . \count($columns);
    }
    $out['class'] = \_\lot\x\panel\h\c([], $class);
    return $out;
}