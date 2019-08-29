<?php namespace _\lot\x\panel\field;

function blob($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['class'] = \trim('input ' . ($out['content'][2]['class'] ?? ""));
    $out['content'][2]['type'] = 'file';
    return \_\lot\x\panel\field($out, $key);
}

function color($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['class'] = \trim('input input:color ' . ($out['content'][2]['class'] ?? ""));
    $out['content'][2]['type'] = 'color';
    $value = $in['value'] ?? "";
    // TODO
    if (\strpos($value, 'rgb') === 0 && \preg_match('/rgb\(\)/', $value, $m)) {
        
    }
    $out['content'][2]['value'] = $value !== "" ? $value : null;
    return \_\lot\x\panel\field($out, $key);
}

function combo($in, $key) {
    if (isset($in['lot']) && \is_array($in['lot'])) {
        $out = \_\lot\x\panel\h\field($in, $key);
        $value = $in['value'] ?? null;
        $placeholder = $out['placeholder'] ?? null;
        $out['content'][0] = 'select';
        unset($out['value']);
        $seq = \array_keys($in['lot']) === \range(0, \count($in['lot']) - 1);
        $a = [];
        foreach ($in['lot'] as $k => $v) {
            // Group
            if (\is_array($v) && isset($v['lot'])) {
                $aa = [];
                $optgroup = new \HTML(['optgroup', "", [
                    'disabled' => isset($v['active']) && !$v['active'],
                    'label' => $t = \trim(\strip_tags($v['title'] ?? $k))
                ]]);
                $seq0 = \array_keys($v['lot']) === \range(0, \count($v['lot']) - 1);
                foreach ($v['lot'] as $kk => $vv) {
                    $option = new \HTML(['option', "", [
                        'selected' => $value !== null && (string) $value === (string) $kk,
                        'value' => $seq0 ? null : $kk
                    ]]);
                    if (\is_array($vv)) {
                        $tt = $vv['title'] ?? $kk;
                        $option['disabled'] = isset($vv['active']) && !$vv['active'];
                    } else {
                        $tt = $vv;
                    }
                    $option[1] = \trim(\strip_tags($tt));
                    $aa[$tt] = $option;
                }
                \ksort($aa);
                foreach ($aa as $vv) {
                    $optgroup[1] .= $vv;
                }
                // Add `0` to the end of the key so that option(s) group will come first
                $a[$t . '0'] = $optgroup;
            // Flat
            } else {
                $option = new \HTML(['option', $k, [
                    'selected' => $value !== null && (string) $value === (string) $k,
                    'value' => $seq ? null : $k
                ]]);
                if (\is_array($v)) {
                    $t = $v['title'] ?? $k;
                    $option['disabled'] = isset($v['active']) && !$v['active'];
                } else {
                    $t = $v;
                }
                $option[1] = \trim(\strip_tags($t));
                // Add `1` to the end of the key so that bare option(s) will come last
                $a[$t . '1'] = $option;
            }
        }
        \ksort($a);
        foreach ($a as $v) {
            $out['content'][1] .= $v;
        }
        $out['content'][2]['class'] = \trim('select ' . ($out['content'][2]['class'] ?? ""));
        return \_\lot\x\panel\field($out, $key);
    }
    return \_\lot\x\panel\field\text($in, $key);
}

function content($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][2]['class'] = \trim('textarea ' . ($out['content'][2]['class'] ?? ""));
    return \_\lot\x\panel\field($out, $key);
}

function hidden($in, $key) {
    return new \HTML([
        0 => 'input',
        1 => false,
        2 => [
            'id' => $in['id'] ?? 'f:' . \dechex(\crc32($key)),
            'name' => $in['name'] ?? $key,
            'type' => 'hidden',
            'value' => $in['value'] ?? null
        ]
    ]);
}

function item($in, $key) {
    if (isset($in['lot']) && \is_array($in['lot'])) {
        $out = \_\lot\x\panel\h\field($in, $key);
        $value = $in['value'] ?? null;
        $out['content'][0] = 'div';
        unset($out['name'], $out['placeholder'], $out['value']);
        $n = $in['name'] ?? $key;
        $a = [];
        foreach ($in['lot'] as $k => $v) {
            $input = new \HTML(['input', false, [
                'checked' => $value !== null && (string) $value === (string) $k,
                'class' => 'input',
                'name' => $n,
                'type' => 'radio',
                'value' => $k
            ]]);
            if (\is_array($v)) {
                $t = $v['title'] ?? $k;
                $input['disabled'] = isset($v['active']) && !$v['active'];
            } else {
                $t = $v;
            }
            $t = \w($t, 'abbr,b,br,cite,code,del,dfn,em,i,img,ins,kbd,mark,q,span,strong,sub,sup,svg,time,u,var');
            $a[$t] = '<label' . ($input['disabled'] ? ' class="disabled"' : "") . '>' . $input . ' <span>' . $t . '</span></label>';
        }
        \ksort($a);
        if (!isset($in['block'])) {
            $block = \count($in['lot']) > 6 ? '<br>' : ""; // Auto
        } else {
            $block = $in['block'] ? '<br>' : "";
        }
        $out['content'][1] = \implode($block, $a);
        $out['content'][2]['class'] = 'lot lot:item' . ($block ? ' block' : "");
        return \_\lot\x\panel\field($out, $key);
    }
    return \_\lot\x\panel\field\text($in, $key);
}

function items($in, $key) {
    if (isset($in['lot']) && \is_array($in['lot'])) {
        $out = \_\lot\x\panel\h\field($in, $key);
        $value = \P . \implode(\P, (array) ($in['value[]'] ?? null)) . \P;
        $out['content'][0] = 'div';
        unset($out['name'], $out['placeholder'], $out['value']);
        $n = $in['name'] ?? $key;
        $a = [];
        foreach ($in['lot'] as $k => $v) {
            $input = new \HTML(['input', false, [
                'checked' => \strpos($value, \P . $k . \P) !== false,
                'class' => 'input',
                'name' => $n . '[' . $k . ']',
                'type' => 'checkbox',
                'value' => $k
            ]]);
            if (\is_array($v)) {
                $t = \w($v['title'] ?? $k, 'abbr,b,br,cite,code,del,dfn,em,i,img,ins,kbd,mark,q,span,strong,sub,sup,svg,time,u,var');
                $input['disabled'] = isset($v['active']) && !$v['active'];
            } else {
                $t = $v;
            }
            $a[$t] = '<label' . ($input['disabled'] ? ' class="disabled"' : "") . '>' . $input . ' <span>' . $t . '</span></label>';
        }
        \ksort($a);
        if (!isset($in['block'])) {
            $block = \count($in['lot']) > 6 ? '<br>' : ""; // Auto
        } else {
            $block = $in['block'] ? '<br>' : "";
        }
        $out['content'][1] = \implode($block, $a);
        $out['content'][2]['class'] = 'lot lot:items' . ($block ? ' block' : "");
        return \_\lot\x\panel\field($out, $key);
    }
    return \_\lot\x\panel\field\text($in, $key);
}

function number($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['class'] = \trim('input ' . ($out['content'][2]['class'] ?? ""));
    $out['content'][2]['type'] = 'number';
    $out['content'][2]['min'] = $in['min'] ?? null;
    $out['content'][2]['max'] = $in['max'] ?? null;
    $out['content'][2]['step'] = $in['step'] ?? null;
    $out['content'][2]['value'] = $in['value'] ?? null;
    return \_\lot\x\panel\field($out, $key);
}

function pass($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['class'] = \trim('input ' . ($out['content'][2]['class'] ?? ""));
    $out['content'][2]['type'] = 'password';
    unset($out['content'][2]['value']); // Do not show `value` on this field
    return \_\lot\x\panel\field($out, $key);
}

function range($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['class'] = \trim('input ' . ($out['content'][2]['class'] ?? ""));
    $out['content'][2]['type'] = 'range';
    $out['content'][2]['min'] = $in['min'] ?? null;
    $out['content'][2]['max'] = $in['max'] ?? null;
    $out['content'][2]['step'] = $in['step'] ?? null;
    $out['content'][2]['value'] = $in['value'] ?? null;
    return \_\lot\x\panel\field($out, $key);
}

function source($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][2]['class'] = \trim('textarea code ' . ($out['content'][2]['class'] ?? ""));
    $out['content'][2]['data-type'] = $in['syntax'] ?? null;
    return \_\lot\x\panel\field($out, $key);
}

function text($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['class'] = \trim('input ' . ($out['content'][2]['class'] ?? ""));
    $out['content'][2]['type'] = 'text';
    $out['content'][2]['value'] = $in['value'] ?? null;
    return \_\lot\x\panel\field($out, $key);
}

function toggle($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $value = $in['value'] ?? null;
    $toggle = new \HTML(['input', false, [
        'checked' => !empty($value),
        'class' => 'input',
        'name' => $in['name'] ?? $key,
        'type' => 'checkbox',
        'value' => $value === true ? 'true' : ($value === false ? 'false' : '1') // Force value to be exists
    ]]);
    $t = \w($in['description'] ?? $GLOBALS['language']->doYes ?? $key, 'abbr,b,br,cite,code,del,dfn,em,i,ins,kbd,mark,q,span,strong,sub,sup,svg,time,u,var');
    $out['content'][0] = 'div';
    $out['content'][1] = '<label>' . $toggle . '&nbsp;<span>' . $t . '</span></label>';
    $out['content'][2]['class'] = 'lot lot:toggle';
    unset($out['description']);
    return \_\lot\x\panel\field($out, $key);
}