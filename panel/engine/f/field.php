<?php namespace _\lot\x\panel\field;

function _($in, $key) {
    $out = \_\lot\x\panel\field\content($in, $key);
    $out['hidden'] = true;
    return $out;
}

function blob($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['name'] = $in['name'] ?? $key;
    $out['content'][2]['type'] = 'file';
    \_\lot\x\panel\h\c($out['content'][2], $in, ['input']);
    return \_\lot\x\panel\field($out, $key);
}

function blobs($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['multiple'] = true; // TODO: Limit file(s) to upload
    $out['content'][2]['name'] = ($in['name'] ?? $key) . '[]';
    $out['content'][2]['type'] = 'file';
    \_\lot\x\panel\h\c($out['content'][2], $in, ['input']);
    return \_\lot\x\panel\field($out, $key);
}

function color($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['type'] = 'color';
    \_\lot\x\panel\h\c($out['content'][2], $in, ['input']);
    if ($value = \_\lot\x\panel\h\color((string) ($in['value'] ?? ""))) {
        $out['content'][2]['title'] = $value;
        $out['content'][2]['value'] = $value;
    }
    return \_\lot\x\panel\field($out, $key);
}

function colors($in, $key) {
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
            $input = \_\lot\x\panel\h\field($v, $k);
            $input[0] = 'input';
            $input[1] = false;
            $input[2]['class'] = 'input';
            $input[2]['name'] = $n;
            $input[2]['type'] = 'color';
            if ($value = \_\lot\x\panel\h\color((string) ($v['value'] ?? ""))) {
                $input[2]['title'] = $value;
                $input[2]['value'] = $value;
            }
            $out['content'][1] .= new \HTML($input);
        }
        unset($in['lot']);
    }
    \_\lot\x\panel\h\c($out['content'][2], $in, ['lot', 'lot:color']);
    unset($out['content'][2]['name']);
    return \_\lot\x\panel\field($out, $key);
}

function combo($in, $key) {
    if (isset($in['lot'])) {
        $out = \_\lot\x\panel\h\field($in, $key);
        $value = $in['value'] ?? null;
        $placeholder = \i(...((array) ($out['alt'] ?? [])));
        $out['content'][0] = 'select';
        $out['content'][1] = ""; // Remove content because this is no longer a `<textarea>`
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
        return \_\lot\x\panel\field($out, $key);
    }
    return \_\lot\x\panel\field\text($in, $key);
}

function content($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    \_\lot\x\panel\h\c($out['content'][2], $in, ['textarea']);
    return \_\lot\x\panel\field($out, $key);
}

function date($in, $key) {
    if (!isset($in['pattern'])) {
        $in['pattern'] = "^[1-9]\\d{3,}-(0\\d|1[0-2])-(0\\d|[1-2]\\d|3[0-1])$";
    }
    return \_\lot\x\panel\field\date_time($in, $key);
}

function date_time($in, $key) {
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
    return \_\lot\x\panel\field\text($in, $key);
}

function email($in, $key) {
    if (!isset($in['alt'])) {
        $in['alt'] = \S . \i('hello') . \S . '@' . \S . $GLOBALS['url']->host . \S;
    }
    if (!isset($in['pattern'])) {
        $in['pattern'] = "^[a-z\\d]+([_.-][a-z\\d]+)*@[a-z\\d]+([_.-][a-z\\d]+)*(\\.[a-z]+)$";
    }
    return \_\lot\x\panel\field\text($in, $key);
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
        return \_\lot\x\panel\field($out, $key);
    }
    return \_\lot\x\panel\field\text($in, $key);
}

function items($in, $key) {
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
            $class = [];
            if (isset($v['active']) && !$v['active']) {
                $input['disabled'] = true;
                $class[] = 'not:active';
            // `else if` because mixing both `disabled` and `readonly` attribute does not make sense
            } else if (!empty($v['frozen'])) {
                $input['readonly'] = true;
                $class[] = 'is:frozen';
            }
            \sort($class);
            $a[$t . $k] = '<label' . ($class ? ' class="' . \implode(' ', $class) . '"' : "") . '>' . $input . ' <span' . ("" !== $d ? ' title="' . $d . '"' : "") . '>' . $t . '</span></label>';
        }
        $sort && \ksort($a);
        if (!isset($in['block'])) {
            $block = $count > 6 ? '<br>' : ""; // Auto
        } else {
            $block = $in['block'] ? '<br>' : "";
        }
        $out['content'][1] = \implode($block, $a);
        \_\lot\x\panel\h\c($out['content'][2], $in, ['count:' . $count, $block ? 'is:block' : null, 'lot', 'lot:items']);
        unset($out['content'][2]['name']);
        return \_\lot\x\panel\field($out, $key);
    }
    return \_\lot\x\panel\field\text($in, $key);
}

function link($in, $key) {
    if (!isset($in['alt'])) {
        $url = $GLOBALS['url'];
        $in['alt'] = \S . $url->protocol . \S . $url->host . \S;
    }
    if (!isset($in['pattern'])) {
        $in['pattern'] = "^(data:[^\\s;]+;\\S+|(https?:)\\/\\/\\S+)$";
    }
    return \_\lot\x\panel\field\text($in, $key);
}

function number($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['type'] = 'number';
    $out['content'][2]['min'] = $in['min'] ?? null;
    $out['content'][2]['max'] = $in['max'] ?? null;
    $out['content'][2]['step'] = $in['step'] ?? null;
    $out['content'][2]['value'] = $in['value'] ?? null;
    \_\lot\x\panel\h\c($out['content'][2], $in, ['input']);
    return \_\lot\x\panel\field($out, $key);
}

function pass($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['type'] = 'password';
    unset($out['content'][2]['value']); // Never show `value` on this field
    \_\lot\x\panel\h\c($out['content'][2], $in, ['input']);
    return \_\lot\x\panel\field($out, $key);
}

function query($in, $key) {
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
    return \_\lot\x\panel\field($out, $key);
}

function range($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['type'] = 'range';
    $out['content'][2]['min'] = $in['min'] ?? null;
    $out['content'][2]['max'] = $in['max'] ?? null;
    $out['content'][2]['step'] = $in['step'] ?? null;
    $out['content'][2]['value'] = $in['value'] ?? null;
    \_\lot\x\panel\h\c($out['content'][2], $in, ['input']);
    return \_\lot\x\panel\field($out, $key);
}

function set($in, $key) {
    $title = \strip_tags($in['title'] ?? "", '<a>');
    $description = \_\lot\x\panel\h\description($in);
    $in = \array_replace([
        0 => 'fieldset',
        1 => ("" !== $title ? '<legend>' . $title . '</legend>' : "") . $description,
        2 => []
    ], $in);
    unset($in['description'], $in['title'], $in['type']);
    return \_\lot\x\panel($in, $key);
}

function source($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][2]['data-state'] = \json_encode(\array_replace($in['state'] ?? [], [
        'tab' => '  '
    ]));
    \_\lot\x\panel\h\c($out['content'][2], $in, ['textarea', 'code']);
    return \_\lot\x\panel\field($out, $key);
}

function text($in, $key) {
    $out = \_\lot\x\panel\h\field($in, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['type'] = 'text';
    $out['content'][2]['value'] = $in['value'] ?? null;
    \_\lot\x\panel\h\c($out['content'][2], $in, ['input']);
    return \_\lot\x\panel\field($out, $key);
}

function time($in, $key) {
    if (!isset($in['pattern'])) {
        $in['pattern'] = "^([0-1]\\d|2[0-4])(:([0-5]\\d|60)){1,2}$";
    }
    $out = \_\lot\x\panel\field\date_time($in, $key);
    return $out;
}

function toggle($in, $key) {
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
    unset($out['alt'], $out['content'][2]['name'], $out['content'][2]['placeholder']);
    return \_\lot\x\panel\field($out, $key);
}

function u_r_l($in, $key) {
    if (!isset($in['alt'])) {
        $url = $GLOBALS['url'];
        $in['alt'] = \S . $url->protocol . \S . $url->host . \S;
    }
    if (!isset($in['pattern'])) {
        $in['pattern'] = "^(data:[^\\s;]+;\\S+|(https?:)?\\/\\/\\S+)$";
    }
    return \_\lot\x\panel\field\text($in, $key);
}
