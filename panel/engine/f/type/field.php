<?php namespace _\lot\x\panel\type\field;

function _($value, $key) {
    $out = \_\lot\x\panel\type\field\content($value, $key);
    $out['skip'] = true;
    return $out;
}

function blob($value, $key) {
    $out = \_\lot\x\panel\to\field($value, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['name'] = $value['name'] ?? $key;
    $out['content'][2]['type'] = 'file';
    \_\lot\x\panel\_set_class($out['content'][2], \array_replace([
        'input' => true
    ], $value['tags'] ?? []));
    return \_\lot\x\panel\type\field($out, $key);
}

function blobs($value, $key) {
    $out = \_\lot\x\panel\to\field($value, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['multiple'] = true; // TODO: Limit file(s) to upload
    $out['content'][2]['name'] = ($value['name'] ?? $key) . '[]';
    $out['content'][2]['type'] = 'file';
    \_\lot\x\panel\_set_class($out['content'][2], \array_replace([
        'input' => true
    ], $value['tags'] ?? []));
    return \_\lot\x\panel\type\field($out, $key);
}

function color($value, $key) {
    $out = \_\lot\x\panel\to\field($value, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['type'] = 'color';
    \_\lot\x\panel\_set_class($out['content'][2], \array_replace([
        'input' => true
    ], $value['tags'] ?? []));
    if ($the_value = \_\lot\x\panel\to\color((string) ($value['value'] ?? ""))) {
        $out['content'][2]['title'] = $the_value;
        $out['content'][2]['value'] = $the_value;
    }
    return \_\lot\x\panel\type\field($out, $key);
}

function colors($value, $key) {
    $out = \_\lot\x\panel\to\field($value, $key);
    $out['content'][0] = 'div';
    $name = $value['name'] ?? $key;
    if (isset($value['lot'])) {
        if (!isset($value['sort']) || $value['sort']) {
            \sort($value['lot']);
        }
        foreach ($value['lot'] as $k => $v) {
            if (null === $v || false === $v || !empty($v['skip'])) {
                continue;
            }
            if (\is_string($v)) {
                $v = ['value' => $v];
            }
            $n = $name . '[' . $k . ']';
            $content = \_\lot\x\panel\to\field($v, $k);
            $content[0] = 'input';
            $content[1] = false;
            $content[2]['class'] = 'input';
            $content[2]['name'] = $n;
            $content[2]['type'] = 'color';
            if ($the_value = \_\lot\x\panel\to\color((string) ($v['value'] ?? ""))) {
                $content[2]['title'] = $the_value;
                $content[2]['value'] = $the_value;
            }
            $out['content'][1] .= new \HTML($content);
        }
        unset($value['lot']);
    }
    \_\lot\x\panel\_set_class($out['content'][2], \array_replace([
        'lot' => true,
        'lot:color' => true
    ], $value['tags'] ?? []));
    unset($out['content'][2]['name']);
    return \_\lot\x\panel\type\field($out, $key);
}

function combo($value, $key) {
    if (isset($value['lot'])) {
        $out = \_\lot\x\panel\to\field($value, $key);
        $the_value = $value['value'] ?? null;
        $placeholder = \i(...((array) ($out['hint'] ?? [])));
        $out['content'][0] = 'select';
        $out['content'][1] = ""; // Remove content because this is no longer a `<textarea>`
        unset($out['value']);
        $seq = \array_keys($value['lot']) === \range(0, \count($value['lot']) - 1);
        $a = [];
        $sort = !isset($value['sort']) || $value['sort'];
        foreach ($value['lot'] as $k => $v) {
            if (null === $v || false === $v || !empty($v['skip'])) {
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
                        'selected' => null !== $the_value && (string) $the_value === (string) $kk,
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
                    'selected' => null !== $the_value && (string) $the_value === (string) $k,
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
        \_\lot\x\panel\_set_class($out['content'][2], \array_replace([
            'select' => true
        ], $value['tags'] ?? []));
        return \_\lot\x\panel\type\field($out, $key);
    }
    return \_\lot\x\panel\type\field\text($value, $key);
}

function content($value, $key) {
    $out = \_\lot\x\panel\to\field($value, $key);
    \_\lot\x\panel\_set_class($out['content'][2], \array_replace([
        'textarea' => true
    ], $value['tags'] ?? []));
    return \_\lot\x\panel\type\field($out, $key);
}

function date($value, $key) {
    if (!isset($value['hint'])) {
        $value['hint'] = \date('Y-m-d');
    }
    if (!isset($value['pattern'])) {
        $value['pattern'] = "^[1-9]\\d{3,}-(0\\d|1[0-2])-(0\\d|[1-2]\\d|3[0-1])$";
    }
    return \_\lot\x\panel\type\field\date_time($value, $key);
}

function date_time($value, $key) {
    if (!isset($value['hint'])) {
        $value['hint'] = \date('Y-m-d H:i:s');
    }
    if (!isset($value['pattern'])) {
        $value['pattern'] = "^[1-9]\\d{3,}-(0\\d|1[0-2])-(0\\d|[1-2]\\d|3[0-1])[ ]([0-1]\\d|2[0-4])(:([0-5]\\d|60)){2}$";
    }
    if (isset($value['value'])) {
        if (\is_string($value['value']) || \is_numeric($value['value'])) {
            $value['value'] = new \Time($value['value']);
        } else {
            $value['value'] = \date('Y-m-d H:i:s');
        }
        $value['value'] = (string) $value['value'];
    }
    return \_\lot\x\panel\type\field\text($value, $key);
}

function email($value, $key) {
    if (!isset($value['hint'])) {
        $value['hint'] = \S . \i('hello') . \S . '@' . \S . $GLOBALS['url']->host . \S;
    }
    if (!isset($value['pattern'])) {
        $value['pattern'] = "^[a-z\\d]+([_.-][a-z\\d]+)*@[a-z\\d]+([_.-][a-z\\d]+)*(\\.[a-z]+)$";
    }
    return \_\lot\x\panel\type\field\text($value, $key);
}

function hidden($value, $key) {
    return new \HTML([
        0 => 'input',
        1 => false,
        2 => [
            'id' => $value['id'] ?? 'f:' . \dechex(\crc32($key)),
            'name' => $value['name'] ?? $key,
            'type' => 'hidden',
            'value' => $value['value'] ?? null
        ]
    ]);
}

function item($value, $key) {
    if (isset($value['lot'])) {
        $the_value = $value['value'] ?? null;
        $n = $value['name'] ?? $key;
        unset($value['name'], $value['hint'], $value['value']);
        $a = [];
        $out = \_\lot\x\panel\to\field($value, $key);
        $out['content'][0] = 'div';
        $count = 0;
        $sort = !isset($value['sort']) || $value['sort'];
        foreach ($value['lot'] as $k => $v) {
            if (null === $v || false === $v || !empty($v['skip'])) {
                continue;
            }
            ++$count;
            $is_active = !isset($v['active']) || $v['active'];
            $content = new \HTML(['input', false, [
                'checked' => null !== $the_value && ((string) $the_value === (string) $k),
                'class' => 'input',
                'name' => $n,
                'type' => 'radio',
                'value' => $k
            ]]);
            if (\is_array($v)) {
                $t = \_\lot\x\panel\to\title($v, -2) . "";
                $d = $v['description'] ?? "";
                $content['disabled'] = !$is_active;
                if (isset($v['name'])) {
                    $content['name'] = $v['name'];
                }
                if (isset($v['value'])) {
                    $content['value'] = $v['value'];
                }
                \_\lot\x\panel\_set_class($content, [
                    'is:active' => $is_active,
                    'not:active' => !$is_active
                ]);
            } else {
                $t = \_\lot\x\panel\to\title(['title' => $v], -2) . "";
                $d = "";
            }
            $d = \strip_tags(\i(...((array) $d)));
            $a[$t . $k] = '<label class="' . ($is_active ? 'is' : 'not') . ':active">' . $content . ' <span' . ("" !== $d ? ' title="' . $d . '"' : "") . '>' . $t . '</span></label>';
        }
        $sort && \ksort($a);
        if (!isset($value['block'])) {
            $block = $count > 6 ? '<br>' : ""; // Auto
        } else {
            $block = $value['block'] ? '<br>' : "";
        }
        $out['content'][1] = \implode($block, $a);
        \_\lot\x\panel\_set_class($out['content'][2], \array_replace([
            'count:' . $count => true,
            'is:block' => !!$block,
            'lot' => true,
            'lot:item' => true
        ], $value['tags'] ?? []));
        unset($out['content'][2]['name']);
        return \_\lot\x\panel\type\field($out, $key);
    }
    return \_\lot\x\panel\type\field\text($value, $key);
}

function items($value, $key) {
    if (isset($value['lot'])) {
        $the_value = (array) ($value['value'] ?? []);
        if ($key_as_value = !empty($value['flat'])) {
            $the_value = \P . \implode(\P, $the_value) . \P;
        }
        $n = $value['name'] ?? $key;
        unset($value['name'], $value['hint'], $value['value']);
        $out = \_\lot\x\panel\to\field($value, $key);
        $out['content'][0] = 'div';
        $a = [];
        $count = 0;
        $sort = !isset($value['sort']) || $value['sort'];
        foreach ($value['lot'] as $k => $v) {
            if (null === $v || false === $v || !empty($v['skip'])) {
                continue;
            }
            ++$count;
            $input = new \HTML(['input', false, [
                'checked' => $key_as_value ? false !== \strpos($the_value, \P . $k . \P) : isset($the_value[$k]),
                'class' => 'input',
                'name' => $n . '[' . ($key_as_value ? "" : $k) . ']',
                'type' => 'checkbox',
                'value' => $key_as_value ? $k : \s($the_value[$k] ?? true)
            ]]);
            if (\is_array($v) && \array_key_exists('title', $v)) {
                $t = \_\lot\x\panel\to\title($v, -2) . "";
                $d = $v['description'] ?? "";
                if (isset($v['name'])) {
                    $input['name'] = $v['name'];
                }
                if (isset($v['value'])) {
                    $input['value'] = $v['value'];
                }
            } else {
                $t = \_\lot\x\panel\to\title(['title' => $v], -2) . "";
                $d = "";
            }
            $d = \strip_tags(\i(...((array) $d)));
            $class = [];
            $class[] = (!isset($v['active']) || $v['active'] ? 'is' : 'not') . ':active';
            $class[] = (!empty($v['lock']) ? 'is' : 'not') . ':lock';
            if (isset($v['active']) && !$v['active']) {
                $input['disabled'] = true;
            // `else if` because mixing both `disabled` and `readonly` attribute does not make sense
            } else if (!empty($v['lock'])) {
                $input['readonly'] = true;
            }
            \sort($class);
            $a[$t . $k] = '<label' . ($class ? ' class="' . \implode(' ', $class) . '"' : "") . '>' . $input . ' <span' . ("" !== $d ? ' title="' . $d . '"' : "") . '>' . $t . '</span></label>';
        }
        $sort && \ksort($a);
        if (!isset($value['block'])) {
            $block = $count > 6 ? '<br>' : ""; // Auto
        } else {
            $block = $value['block'] ? '<br>' : "";
        }
        $out['content'][1] = \implode($block, $a);
        \_\lot\x\panel\_set_class($out['content'][2], \array_replace([
            'count:' . $count => true,
            'is:block' => !!$block,
            'lot' => true,
            'lot:items' => true
        ], $value['tags'] ?? []));
        unset($out['content'][2]['name']);
        return \_\lot\x\panel\type\field($out, $key);
    }
    return \_\lot\x\panel\type\field\text($value, $key);
}

function link($value, $key) {
    if (!isset($value['hint'])) {
        $url = $GLOBALS['url'];
        $value['hint'] = \S . $url->protocol . \S . $url->host . \S;
    }
    if (!isset($value['pattern'])) {
        $value['pattern'] = "^(data:[^\\s;]+;\\S+|(https?:)\\/\\/\\S+)$";
    }
    return \_\lot\x\panel\type\field\text($value, $key);
}

function number($value, $key) {
    $out = \_\lot\x\panel\to\field($value, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['type'] = 'number';
    $out['content'][2]['min'] = $value['min'] ?? null;
    $out['content'][2]['max'] = $value['max'] ?? null;
    $out['content'][2]['step'] = $value['step'] ?? null;
    $out['content'][2]['value'] = $value['value'] ?? null;
    \_\lot\x\panel\_set_class($out['content'][2], \array_replace([
        'input' => true
    ], $value['tags'] ?? []));
    return \_\lot\x\panel\type\field($out, $key);
}

function pass($value, $key) {
    $out = \_\lot\x\panel\to\field($value, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['type'] = 'password';
    unset($out['content'][2]['value']); // Never show `value` on this field
    \_\lot\x\panel\_set_class($out['content'][2], \array_replace([
        'input' => true
    ], $value['tags'] ?? []));
    return \_\lot\x\panel\type\field($out, $key);
}

function query($value, $key) {
    if (!isset($value['hint'])) {
        $value['hint'] = 'foo, bar, baz';
    }
    if (!isset($value['pattern'])) {
        $value['pattern'] = "^([A-Za-z\\d]+([- ][A-Za-z\\d]+)*)(\\s*,\\s*[A-Za-z\\d]+([- ][A-Za-z\\d]+)*)*$";
    }
    if (isset($value['value']) && \is_array($value['value'])) {
        $value['value'] = \implode(', ', $value['value']);
    }
    $out = \_\lot\x\panel\to\field($value, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['type'] = 'text';
    $out['content'][2]['value'] = $value['value'] ?? null;
    if (isset($value['state'])) {
        $out['content'][2]['data-state'] = \json_encode($value['state']);
    }
    \_\lot\x\panel\_set_class($out['content'][2], \array_replace([
        'input' => true
    ], $value['tags'] ?? []));
    return \_\lot\x\panel\type\field($out, $key);
}

function range($value, $key) {
    $out = \_\lot\x\panel\to\field($value, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    if (isset($value['range'])) {
        // `[$min, $max]`
        if (2 === \count($value['range'])) {
            $value['min'] = $value['range'][0] ?? 0;
            $value['max'] = $value['range'][1] ?? 1;
        // `[$min, $value, $max]`
        } else {
            $value['min'] = $value['range'][0] ?? 0;
            $value['value'] = $value['range'][1] ?? 0;
            $value['max'] = $value['range'][2] ?? 1;
        }
    }
    $out['content'][2]['type'] = 'range';
    $out['content'][2]['min'] = $value['min'] ?? null;
    $out['content'][2]['max'] = $value['max'] ?? null;
    $out['content'][2]['value'] = $value['value'] ?? null;
    $out['content'][2]['step'] = $value['step'] ?? null;
    \_\lot\x\panel\_set_class($out['content'][2], \array_replace([
        'input' => true
    ], $value['tags'] ?? []));
    return \_\lot\x\panel\type\field($out, $key);
}

function set($value, $key) {
    $title = \strip_tags($value['title'] ?? "", '<a>');
    $description = \_\lot\x\panel\to\description($value);
    $value = \array_replace([
        0 => 'fieldset',
        1 => ("" !== $title ? '<legend>' . $title . '</legend>' : "") . $description,
        2 => []
    ], $value);
    unset($value['description'], $value['title'], $value['type']);
    return \_\lot\x\panel\type($value, $key);
}

function source($value, $key) {
    $out = \_\lot\x\panel\to\field($value, $key);
    $out['content'][2]['data-state'] = \json_encode(\array_replace($value['state'] ?? [], [
        'tab' => '  '
    ]));
    \_\lot\x\panel\_set_class($out['content'][2], \array_replace([
        'code' => true,
        'textarea' => true
    ], $value['tags'] ?? []));
    return \_\lot\x\panel\type\field($out, $key);
}

function text($value, $key) {
    $out = \_\lot\x\panel\to\field($value, $key);
    $out['content'][0] = 'input';
    $out['content'][1] = false;
    $out['content'][2]['type'] = 'text';
    $out['content'][2]['value'] = $value['value'] ?? null;
    \_\lot\x\panel\_set_class($out['content'][2], \array_replace([
        'input' => true
    ], $value['tags'] ?? []));
    return \_\lot\x\panel\type\field($out, $key);
}

function time($value, $key) {
    if (!isset($value['hint'])) {
        $value['hint'] = \date('H:i:s');
    }
    if (!isset($value['pattern'])) {
        $value['pattern'] = "^([0-1]\\d|2[0-4])(:([0-5]\\d|60)){1,2}$";
    }
    $out = \_\lot\x\panel\type\field\date_time($value, $key);
    return $out;
}

function toggle($value, $key) {
    $out = \_\lot\x\panel\to\field($value, $key);
    $the_value = $value['value'] ?? null;
    $toggle = new \HTML(['input', false, [
        'checked' => !empty($the_value),
        'class' => 'input',
        'name' => $value['name'] ?? $key,
        'type' => 'checkbox',
        'value' => 'true' // Force value to be `true`
    ]]);
    $t = \i(...((array) ($value['hint'] ?? \S)));
    $out['content'][0] = 'div';
    $out['content'][1] = '<label>' . $toggle . ' <span>' . $t . '</span></label>';
    \_\lot\x\panel\_set_class($out['content'][2], \array_replace([
        'lot' => true,
        'lot:toggle' => true
    ], $value['tags'] ?? []));
    unset($out['hint'], $out['content'][2]['name'], $out['content'][2]['placeholder']);
    return \_\lot\x\panel\type\field($out, $key);
}

function u_r_l($value, $key) {
    if (!isset($value['hint'])) {
        $url = $GLOBALS['url'];
        $value['hint'] = \S . $url->protocol . \S . $url->host . \S;
    }
    if (!isset($value['pattern'])) {
        $value['pattern'] = "^(data:[^\\s;]+;\\S+|(https?:)?\\/\\/\\S+)$";
    }
    return \_\lot\x\panel\type\field\text($value, $key);
}
