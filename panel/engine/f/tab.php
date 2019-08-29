<?php namespace _\lot\x\panel\tab;

function pane($in, $key, $type) {
    $out = [
        0 => $in[0] ?? 'section',
        1 => $in[1] ?? "",
        2 => \array_replace(['id' => $in['id'] ?? $key], $in[2] ?? [])
    ];
    if (isset($in['content'])) {
        $out[1] .= \is_array($in['content']) ? new \HTML($in['content']) : $in['content'];
    } else if (isset($in['lot']) && \is_array($in['lot'])) {
        foreach ((new \Anemon($in['lot']))->sort([1, 'stack', 10], true) as $k => $v) {
            $out[1] .= \_\lot\x\panel($v, $k, $v['type'] ?? '#');
        }
    }
    $out[2] = \_\lot\x\panel\h\c($in);
    return new \HTML($out);
}