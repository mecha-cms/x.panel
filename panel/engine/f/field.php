<?php namespace _\lot\x\panel;

function Field_($in, $key) {
    $out = \_\lot\x\panel\Field_Content($in, $key);
    $out['hidden'] = true;
    return $out;
}

function Field_Blob($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $name = 'blob[' . \md5($in['name'] ?? $key) . ']';
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['class'] = \_\lot\x\panel\h\c($in, ['input']);
    $out['content'][2]['name'] = $name;
    $out['content'][2]['type'] = 'file';
    return \_\lot\x\panel\Field($out, $key);
}

function Field_Color($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['class'] = \_\lot\x\panel\h\c($in, ['input']);
    $out['content'][2]['type'] = 'color';
    $value = $in['value'] ?? "";
    // TODO: Convert any color string into HEX color code
    if ($value !== "") {
        $out['content'][2]['title'] = $value;
        $out['content'][2]['value'] = $value;
    }
    return \_\lot\x\panel\Field($out, $key);
}

function Field_Colors($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'div';
    $name = $in['name'] ?? $key;
    if (isset($in['lot'])) {
        \sort($in['lot']);
        foreach ($in['lot'] as $k => $v) {
            if (\is_string($v)) {
                $v = ['value' => $v];
            }
            $n = $v['name'] ?? $name . '[' . $k . ']';
            $value = $v['value'] ?? null;
            $input = \_\lot\x\panel\h\field($v, $k);
            $input[0] = 'input';
            $input[1] = false;
            $input[2]['class'] = 'input';
            $input[2]['name'] = $n;
            $input[2]['title'] = $value;
            $input[2]['type'] = 'color';
            $input[2]['value'] = $value;
            $out['content'][1] .= new \HTML($input);
            \_\lot\x\panel\h\session($n, $v);
        }
        unset($in['lot']);
    }
    $out['content'][2]['class'] = \_\lot\x\panel\h\c($in, ['lot', 'lot:color']);
    return \_\lot\x\panel\Field($out, $key);
}

function Field_Combo($in, $key) {
    if (isset($in['lot'])) {
        $out = \_\lot\x\panel\h\field($in, $key);
        $value = $in['value'] ?? null;
        $placeholder = $out['placeholder'] ?? null;
        $out['content'][0] = 'select';
        unset($out['value']);
        $seq = \array_keys($in['lot']) === \range(0, \count($in['lot']) - 1);
        $a = [];
        foreach ($in['lot'] as $k => $v) {
            // Group
            if (isset($v['lot'])) {
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
        $out['content'][2]['class'] = \_\lot\x\panel\h\c($in, ['select']);
        unset($in['lot']);
        return \_\lot\x\panel\Field($out, $key);
    }
    return \_\lot\x\panel\Field_Text($in, $key);
}

function Field_Content($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][2]['class'] = \_\lot\x\panel\h\c($in, ['textarea']);
    return \_\lot\x\panel\Field($out, $key);
}

function Field_Hidden($in, $key) {
    \_\lot\x\panel\h\session($name = $in['name'] ?? $key, $in);
    return new \HTML([
        0 => 'input',
        1 => false,
        2 => [
            'id' => $in['id'] ?? 'f:' . \dechex(\crc32($key)),
            'name' => $name,
            'type' => 'hidden',
            'value' => $in['value'] ?? null
        ]
    ]);
}

function Field_Item($in, $key) {
    if (isset($in['lot'])) {
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
            \_\lot\x\panel\h\session($n, $v);
            if (\is_array($v)) {
                $t = $v['title'] ?? $k;
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
        $out['content'][2]['class'] = \_\lot\x\panel\h\c($in, ['lot', 'lot:item', $block ? 'block' : null]);
        unset($in['lot']);
        return \_\lot\x\panel\Field($out, $key);
    }
    return \_\lot\x\panel\Field_Text($in, $key);
}

function Field_Items($in, $key) {
    if (isset($in['lot'])) {
        $value = $in['value'] ?? [];
        unset($in['value']);
        $out = \_\lot\x\panel\h\field($in, $key);
        $value = \P . \implode(\P, (array) $value) . \P;
        $out['content'][0] = 'div';
        unset($out['name'], $out['placeholder']);
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
            \_\lot\x\panel\h\session($n . '[' . $k . ']', $in);
            if (\is_array($v)) {
                $t = \_\lot\x\panel\h\title($v, -2) . "";
                $input['disabled'] = isset($v['active']) && !$v['active'];
            } else {
                $t = \_\lot\x\panel\h\title(['title' => $v], -2) . "";
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
        $out['content'][2]['class'] = \_\lot\x\panel\h\c($in, ['lot', 'lot:items', $block ? 'block' : null]);
        unset($in['lot']);
        return \_\lot\x\panel\Field($out, $key);
    }
    return \_\lot\x\panel\Field_Text($in, $key);
}

function Field_Number($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['class'] = \_\lot\x\panel\h\c($in, ['input']);
    $out['content'][2]['type'] = 'number';
    $out['content'][2]['min'] = $in['min'] ?? null;
    $out['content'][2]['max'] = $in['max'] ?? null;
    $out['content'][2]['step'] = $in['step'] ?? null;
    $out['content'][2]['value'] = $in['value'] ?? null;
    return \_\lot\x\panel\Field($out, $key);
}

function Field_Pass($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['class'] = \_\lot\x\panel\h\c($in, ['input']);
    $out['content'][2]['type'] = 'password';
    unset($out['content'][2]['value']); // Never show `value` on this field
    return \_\lot\x\panel\Field($out, $key);
}

function Field_Range($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['class'] = \_\lot\x\panel\h\c($in, ['input']);
    $out['content'][2]['type'] = 'range';
    $out['content'][2]['min'] = $in['min'] ?? null;
    $out['content'][2]['max'] = $in['max'] ?? null;
    $out['content'][2]['step'] = $in['step'] ?? null;
    $out['content'][2]['value'] = $in['value'] ?? null;
    return \_\lot\x\panel\Field($out, $key);
}

function Field_Source($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][2]['class'] = \_\lot\x\panel\h\c($in, ['textarea', 'code']);
    $out['content'][2]['data-type'] = $in['syntax'] ?? null;
    return \_\lot\x\panel\Field($out, $key);
}

function Field_Text($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['class'] = \_\lot\x\panel\h\c($in, ['input']);
    $out['content'][2]['type'] = 'text';
    $out['content'][2]['value'] = $in['value'] ?? null;
    return \_\lot\x\panel\Field($out, $key);
}

function Field_Toggle($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $value = $in['value'] ?? null;
    $toggle = new \HTML(['input', false, [
        'checked' => !empty($value),
        'class' => 'input',
        'name' => $in['name'] ?? $key,
        'type' => 'checkbox',
        'value' => $value === true ? 'true' : ($value === false ? 'false' : '1') // Force value to be exists
    ]]);
    $t = $in['description'] ?? $GLOBALS['language']->doYes ?? $key;
    $out['content'][0] = 'div';
    $out['content'][1] = '<label>' . $toggle . ' <span>' . $t . '</span></label>';
    $out['content'][2]['class'] = \_\lot\x\panel\h\c($in, ['lot', 'lot:toggle']);
    unset($out['description']);
    return \_\lot\x\panel\Field($out, $key);
}