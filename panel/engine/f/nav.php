<?php namespace _\lot\x\panel\nav;

function ul($in, $key, $type, int $i = 0) {
    $out = [
        0 => $in[0] ?? 'ul',
        1 => $in[1] ?? "",
        2 => $in[2] ?? []
    ];
    if (isset($in['content'])) {
        $out[1] .= \is_array($in['content']) ? new \HTML($in['content']) : $in['content'];
    } else if (isset($in['lot'])&& \is_array($in['lot'])) {
        foreach ((new \Anemon($in['lot']))->sort([1, 'stack', 10], true) as $k => $v) {
            $li = [
                0 => 'li',
                1 => $v[1] ?? "",
                2 => $v[2] ?? []
            ];
            if (\is_array($v)) {
                $v['icon'] = \_\lot\x\panel\h\icon($v['icon'] ?? [null, null]);
                if (!empty($v['lot']) && (!empty($v['caret']) || !\array_key_exists('caret', $v))) {
                    $v['icon'][1] = '<svg class="caret" viewBox="0 0 24 24"><path d="' . ($v['caret'] ?? ($i === 0 ? 'M7,10L12,15L17,10H7Z' : 'M10,17L15,12L10,7V17Z')) . '"></path></svg>';
                }
                $ul = "";
                if (!isset($v[1])) {
                    if (!empty($v['lot']) && (!\array_key_exists(0, $v) || \is_string($v[0]))) {
                        $ul = ul($v, $k, $type, $i + 1); // Recurse
                        $ul['class'] = 'lot lot:menu';
                        $li[1] = $ul;
                        if ($i === 0) {
                            $v['tags'][] = 'drop';
                        }
                    }
                    $li[2] = \_\lot\x\panel\h\c($v);
                    unset($v['tags']);
                    $li[1] = \_\lot\x\panel\a($v) . $ul;
                }
            } else {
                $li[1] = \_\lot\x\panel\a(['title' => $v]);
            }
            $out[1] .= new \HTML($li);
        }
    }
    $out[2] = \_\lot\x\panel\h\c($in);
    return new \HTML($out);
}