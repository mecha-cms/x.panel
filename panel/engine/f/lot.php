<?php namespace _\lot\x\panel\lot;

function Container($in, $key) {
    $out = \_\lot\x\panel\lot($in, $key);
    $out[0] = 'div';
    return $out;
}

function Desk($in, $key) {
    $out = \_\lot\x\panel\lot($in, $key);
    $out[0] = 'main';
    return $out;
}

function Pane($in, $key) {
    if (isset($in['lot'])) {
        $in['lot'] = [
            0 => [
                'type' => 'Container',
                'lot' => $in['lot']
            ]
        ];
    }
    $out = \_\lot\x\panel\lot($in, $key);
    $out[0] = 'section';
    return $out;
}

function Section($in, $key) {
    $out = \_\lot\x\panel\lot($in, $key);
    $out[0] = 'section';
    return $out;
}