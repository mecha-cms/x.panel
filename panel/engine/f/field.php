<?php namespace _\lot\x\panel;

function Field__($in, $key) {
    $out = \_\lot\x\panel\Field__Content($in, $key);
    $out['hidden'] = true;
    return $out;
}

function Field__Blob($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $name = 'blob[' . \md5($in['name'] ?? $key) . ']';
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['name'] = $name;
    $out['content'][2]['type'] = 'file';
    \_\lot\x\panel\h\c($out['content'][2], $in, ['input']);
    return \_\lot\x\panel\Field($out, $key);
}

function Field__Color($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['type'] = 'color';
    \_\lot\x\panel\h\c($out['content'][2], $in, ['input']);
    $value = $in['value'] ?? "";
    // TODO: Convert any color string into HEX color code
    if ("" !== $value) {
        $out['content'][2]['title'] = $value;
        $out['content'][2]['value'] = $value;
    }
    return \_\lot\x\panel\Field($out, $key);
}

function Field__Colors($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'div';
    $name = $in['name'] ?? $key;
    if (isset($in['lot'])) {
        \sort($in['lot']);
        foreach ($in['lot'] as $k => $v) {
            if (null === $v || false === $v || !empty($v['hidden'])) {
                continue;
            }
            if (\is_string($v)) {
                $v = ['value' => $v];
            }
            $n = $name . '[' . $k . ']';
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
        }
        unset($in['lot']);
    }
    \_\lot\x\panel\h\c($out['content'][2], $in, ['lot', 'lot:color']);
    return \_\lot\x\panel\Field($out, $key);
}

function Field__Combo($in, $key) {
    if (isset($in['lot'])) {
        $out = \_\lot\x\panel\h\field($in, $key);
        $value = $in['value'] ?? null;
        $placeholder = \i(...((array) ($out['alt'] ?? [])));
        $out['content'][0] = 'select';
        unset($out['value']);
        $seq = \array_keys($in['lot']) === \range(0, \count($in['lot']) - 1);
        $a = [];
        $sort = !isset($in['sort']) || $in['sort'];
        foreach ($in['lot'] as $k => $v) {
            if (null === $v || false === $v || !empty($v['hidden'])) {
                continue;
            }
            // Group
            if (isset($v['lot'])) {
                $aa = [];
                $optgroup = new \HTML(['optgroup', "", [
                    'disabled' => isset($v['active']) && !$v['active'],
                    'label' => $t = \trim(\strip_tags(\i(...((array) ($v['title'] ?? $k)))))
                ]]);
                $seq0 = \array_keys($v['lot']) === \range(0, \count($v['lot']) - 1);
                foreach ($v['lot'] as $kk => $vv) {
                    $option = new \HTML(['option', "", [
                        'selected' => null !== $value && (string) $value === (string) $kk,
                        'value' => $seq0 ? null : $kk
                    ]]);
                    if (\is_array($vv) && \array_key_exists('title', $vv)) {
                        $tt = $vv['title'] ?? $kk;
                        $option['disabled'] = isset($vv['active']) && !$vv['active'];
                    } else {
                        $tt = $vv;
                    }
                    $option[1] = $tt = \trim(\strip_tags(\i(...((array) $tt))));
                    $aa[$tt . $kk] = $option;
                }
                $sort && \ksort($aa);
                foreach ($aa as $vv) {
                    $optgroup[1] .= $vv;
                }
                // Add `0` to the end of the key so that option(s) group will come first
                $a[$t . $k . '0'] = $optgroup;
            // Flat
            } else {
                $option = new \HTML(['option', $k, [
                    'selected' => null !== $value && (string) $value === (string) $k,
                    'value' => $seq ? null : $k
                ]]);
                if (\is_array($v) && \array_key_exists('title', $v)) {
                    $t = $v['title'] ?? $k;
                    $option['disabled'] = isset($v['active']) && !$v['active'];
                } else {
                    $t = $v;
                }
                $option[1] = \trim(\strip_tags(\i(...((array) $t))));
                // Add `1` to the end of the key so that bare option(s) will come last
                $a[$t . $k . '1'] = $option;
            }
        }
        $sort && \ksort($a);
        foreach ($a as $v) {
            $out['content'][1] .= $v;
        }
        \_\lot\x\panel\h\c($out['content'][2], $in, ['select']);
        unset($in['lot']);
        return \_\lot\x\panel\Field($out, $key);
    }
    return \_\lot\x\panel\Field__Text($in, $key);
}

function Field__Content($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    \_\lot\x\panel\h\c($out['content'][2], $in, ['textarea']);
    return \_\lot\x\panel\Field($out, $key);
}

function Field__Date($in, $key) {
    if (!isset($in['pattern'])) {
        $in['pattern'] = "^[1-9]\\d{3,}-(0\\d|1[0-2])-(0\\d|[1-2]\\d|3[0-1])$";
    }
    return \_\lot\x\panel\Field__DateTime($in, $key);
}

function Field__DateTime($in, $key) {
    if (!isset($in['alt'])) {
        $in['alt'] = \date('Y-m-d H:i:s');
    }
    if (!isset($in['pattern'])) {
        $in['pattern'] = "^[1-9]\\d{3,}-(0\\d|1[0-2])-(0\\d|[1-2]\\d|3[0-1])([ ]([0-1]\\d|2[0-4])(:([0-5]\\d|60)){2})?$";
    }
    if (isset($in['value'])) {
        if (\is_string($in['value']) || \is_numeric($in['value'])) {
            $in['value'] = new \Time($in['value']);
        } else {
            $in['value'] = \date('Y-m-d H:i:s');
        }
        $in['value'] = (string) $in['value'];
    }
    return \_\lot\x\panel\Field__Text($in, $key);
}

function Field__Email($in, $key) {
    if (!isset($in['alt'])) {
        $in['alt'] = \S . \i('hello') . \S . '@' . \S . $GLOBALS['url']->host . \S;
    }
    if (!isset($in['pattern'])) {
        $in['pattern'] = "^[a-z\\d]+([_.-][a-z\\d]+)*@[a-z\\d]+([_.-][a-z\\d]+)*(\\.[a-z]+)$";
    }
    return \_\lot\x\panel\Field__Text($in, $key);
}

function Field__Hidden($in, $key) {
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

function Field__Item($in, $key) {
    if (isset($in['lot'])) {
        $value = $in['value'] ?? null;
        $n = $in['name'] ?? $key;
        unset($in['name'], $in['alt'], $in['value']);
        $a = [];
        $out = \_\lot\x\panel\h\field($in, $key);
        $out['content'][0] = 'div';
        $count = 0;
        $sort = !isset($in['sort']) || $in['sort'];
        foreach ($in['lot'] as $k => $v) {
            if (null === $v || false === $v || !empty($v['hidden'])) {
                continue;
            }
            ++$count;
            $input = new \HTML(['input', false, [
                'checked' => null !== $value && ((string) $value === (string) $k),
                'class' => 'input',
                'name' => $n,
                'type' => 'radio',
                'value' => $k
            ]]);
            if (\is_array($v)) {
                $t = \_\lot\x\panel\h\title($v, -2) . "";
                $d = $v['description'] ?? "";
                $input['disabled'] = isset($v['active']) && !$v['active'];
                if (isset($v['name'])) {
                    $input['name'] = $v['name'];
                }
                if (isset($v['value'])) {
                    $input['value'] = $v['value'];
                }
            } else {
                $t = \_\lot\x\panel\h\title(['title' => $v], -2) . "";
                $d = "";
            }
            $d = \strip_tags(\i(...((array) $d)));
            $a[$t . $k] = '<label' . ($input['disabled'] ? ' class="disabled"' : "") . '>' . $input . ' <span' . ("" !== $d ? ' title="' . $d . '"' : "") . '>' . $t . '</span></label>';
        }
        $sort && \ksort($a);
        if (!isset($in['block'])) {
            $block = $count > 6 ? '<br>' : ""; // Auto
        } else {
            $block = $in['block'] ? '<br>' : "";
        }
        $out['content'][1] = \implode($block, $a);
        \_\lot\x\panel\h\c($out['content'][2], $in, ['count:' . $count, $block ? 'is:block' : null, 'lot', 'lot:item']);
        unset($in['lot']);
        return \_\lot\x\panel\Field($out, $key);
    }
    return \_\lot\x\panel\Field__Text($in, $key);
}

function Field__Items($in, $key) {
    if (isset($in['lot'])) {
        $value = (array) ($in['value'] ?? []);
        if ($key_as_value = !empty($in['flat'])) {
            $value = \P . \implode(\P, $value) . \P;
        }
        $n = $in['name'] ?? $key;
        unset($in['name'], $in['alt'], $in['value']);
        $out = \_\lot\x\panel\h\field($in, $key);
        $out['content'][0] = 'div';
        $a = [];
        $count = 0;
        $sort = !isset($in['sort']) || $in['sort'];
        foreach ($in['lot'] as $k => $v) {
            if (null === $v || false === $v || !empty($v['hidden'])) {
                continue;
            }
            ++$count;
            $input = new \HTML(['input', false, [
                'checked' => $key_as_value ? false !== \strpos($value, \P . $k . \P) : isset($value[$k]),
                'class' => 'input',
                'name' => $n . '[' . ($key_as_value ? "" : $k) . ']',
                'type' => 'checkbox',
                'value' => $key_as_value ? $k : \s($value[$k] ?? true)
            ]]);
            if (\is_array($v) && \array_key_exists('title', $v)) {
                $t = \_\lot\x\panel\h\title($v, -2) . "";
                $d = $v['description'] ?? "";
                $input['disabled'] = isset($v['active']) && !$v['active'];
                if (isset($v['name'])) {
                    $input['name'] = $v['name'];
                }
                if (isset($v['value'])) {
                    $input['value'] = $v['value'];
                }
            } else {
                $t = \_\lot\x\panel\h\title(['title' => $v], -2) . "";
                $d = "";
            }
            $d = \strip_tags(\i(...((array) $d)));
            $a[$t . $k] = '<label' . ($input['disabled'] ? ' class="not:active"' : "") . '>' . $input . ' <span' . ("" !== $d ? ' title="' . $d . '"' : "") . '>' . $t . '</span></label>';
        }
        $sort && \ksort($a);
        if (!isset($in['block'])) {
            $block = $count > 6 ? '<br>' : ""; // Auto
        } else {
            $block = $in['block'] ? '<br>' : "";
        }
        $out['content'][1] = \implode($block, $a);
        \_\lot\x\panel\h\c($out['content'][2], $in, ['count:' . $count, $block ? 'is:block' : null, 'lot', 'lot:items']);
        unset($in['lot']);
        return \_\lot\x\panel\Field($out, $key);
    }
    return \_\lot\x\panel\Field__Text($in, $key);
}

function field__Link($in, $key) {
    if (!isset($in['alt'])) {
        $url = $GLOBALS['url'];
        $in['alt'] = \S . $url->protocol . \S . $url->host . \S;
    }
    if (!isset($in['pattern'])) {
        $in['pattern'] = "^(data:[^\\s;]+;\\S+|(https?:)\\/\\/\\S+)$";
    }
    return \_\lot\x\panel\Field__Text($in, $key);
}

function Field__Number($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['type'] = 'number';
    $out['content'][2]['min'] = $in['min'] ?? null;
    $out['content'][2]['max'] = $in['max'] ?? null;
    $out['content'][2]['step'] = $in['step'] ?? null;
    $out['content'][2]['value'] = $in['value'] ?? null;
    \_\lot\x\panel\h\c($out['content'][2], $in, ['input']);
    return \_\lot\x\panel\Field($out, $key);
}

function Field__Pass($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['type'] = 'password';
    unset($out['content'][2]['value']); // Never show `value` on this field
    \_\lot\x\panel\h\c($out['content'][2], $in, ['input']);
    return \_\lot\x\panel\Field($out, $key);
}

function Field__Query($in, $key) {
    if (!isset($in['alt'])) {
        $in['alt'] = 'foo, bar, baz';
    }
    if (!isset($in['pattern'])) {
        $in['pattern'] = "^([A-Za-z\\d]+([- ][A-Za-z\\d]+)*)(\\s*,\\s*[A-Za-z\\d]+([- ][A-Za-z\\d]+)*)*$";
    }
    if (isset($in['value']) && \is_array($in['value'])) {
        $in['value'] = \implode(', ', $in['value']);
    }
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['type'] = 'text';
    $out['content'][2]['value'] = $in['value'] ?? null;
    if (isset($in['state'])) {
        $out['content'][2]['data-state'] = \json_encode($in['state']);
    }
    \_\lot\x\panel\h\c($out['content'][2], $in, ['input']);
    return \_\lot\x\panel\Field($out, $key);
}

function Field__Range($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['type'] = 'range';
    $out['content'][2]['min'] = $in['min'] ?? null;
    $out['content'][2]['max'] = $in['max'] ?? null;
    $out['content'][2]['step'] = $in['step'] ?? null;
    $out['content'][2]['value'] = $in['value'] ?? null;
    \_\lot\x\panel\h\c($out['content'][2], $in, ['input']);
    return \_\lot\x\panel\Field($out, $key);
}

function Field__Source($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    \_\lot\x\panel\h\c($out['content'][2], $in, ['textarea', 'code']);
    return \_\lot\x\panel\Field($out, $key);
}

function Field__Text($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['type'] = 'text';
    $out['content'][2]['value'] = $in['value'] ?? null;
    \_\lot\x\panel\h\c($out['content'][2], $in, ['input']);
    return \_\lot\x\panel\Field($out, $key);
}

function Field__Time($in, $key) {
    if (!isset($in['pattern'])) {
        $in['pattern'] = "^([0-1]\\d|2[0-4])(:([0-5]\\d|60)){1,2}$";
    }
    $out = \_\lot\x\panel\Field__DateTime($in, $key);
    return $out;
}

function Field__Toggle($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $value = $in['value'] ?? null;
    $toggle = new \HTML(['input', false, [
        'checked' => !empty($value),
        'class' => 'input',
        'name' => $in['name'] ?? $key,
        'type' => 'checkbox',
        'value' => 'true' // Force value to be `true`
    ]]);
    $t = \i(...((array) ($in['alt'] ?? \S)));
    $out['content'][0] = 'div';
    $out['content'][1] = '<label>' . $toggle . ' <span>' . $t . '</span></label>';
    \_\lot\x\panel\h\c($out['content'][2], $in, ['lot', 'lot:toggle']);
    unset($out['alt']);
    return \_\lot\x\panel\Field($out, $key);
}

function field__URL($in, $key) {
    if (!isset($in['alt'])) {
        $url = $GLOBALS['url'];
        $in['alt'] = \S . $url->protocol . \S . $url->host . \S;
    }
    if (!isset($in['pattern'])) {
        $in['pattern'] = "^(data:[^\\s;]+;\\S+|(https?:)?\\/\\/\\S+)$";
    }
    return \_\lot\x\panel\Field__Text($in, $key);
}
