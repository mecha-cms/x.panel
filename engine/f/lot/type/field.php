<?php namespace x\panel\lot\type\field;

function blank($value, $key) {
    return \x\panel\lot\type\field($value, $key);
}

function blob($value, $key) {
    $out = \x\panel\to\field($value, $key, 'input');
    $out['field'][2]['type'] = 'file';
    $out['field'][2] = \x\panel\lot\_tag_set($out['field'][2], $value);
    return \x\panel\lot\type\field($out, $key);
}

function blobs($value, $key) {
    $out = \x\panel\to\field($value, $key, 'input');
    $name = (string) ($value['name'] ?? $key);
    $out['field'][2]['multiple'] = true;
    $out['field'][2]['name'] = $name . ('[]' === \substr($name, -2) ? "" : '[]');
    $out['field'][2]['type'] = 'file';
    $out['field'][2] = \x\panel\lot\_tag_set($out['field'][2], $value);
    return \x\panel\lot\type\field($out, $key);
}

function button($value, $key) {
    $out = \x\panel\to\field($value, $key, 'button');
    $out['field'][1] = \i(...((array) ($value['hint'] ?? $value['title'] ?? $value['value'] ?? $key)));
    $out['field'][2]['type'] = 'button';
    $out['field'][2] = \x\panel\lot\_tag_set($out['field'][2], $value);
    return \x\panel\lot\type\field($out, $key);
}

function buttons($value, $key) {
    $count = 0;
    $has_gap = !isset($value['gap']) || $value['gap'];
    $is_flex = !isset($value['flex']) || $value['flex'];
    $name = (string) ($value['name'] ?? $key);
    if (isset($value['values'])) {
        if (isset($value['lot']) && \is_array($value['lot'])) {
            foreach ($value['values'] as $k => $v) {
                if (!isset($value['lot'][$k]) || (\is_array($value['lot'][$k]) && !\array_key_exists('value', $value['lot'][$k]))) {
                    $value['lot'][$k]['value'] = $v;
                }
            }
        } else {
            $value['lot'] = $value['values'];
        }
        unset($value['values']);
    }
    $out = \x\panel\to\field($value, $key);
    $out['field'][0] = 'div';
    if (isset($value['lot'])) {
        // TODO: Sort by `stack`
        foreach ($value['lot'] as $k => $v) {
            if (false === $v || null === $v || !empty($v['skip'])) {
                continue;
            }
            ++$count;
            if (!\is_array($v)) {
                $v = ['value' => \s($v)];
            }
            $n = (string) ($v['name'] ?? "");
            $n = $n ? (0 === \strpos($n, '[') ? $name . $n : $n) : $name . '[' . $k . ']';
            $is_active = !isset($v['active']) || $v['active'];
            $v['is']['active'] = $v['is']['active'] ?? $is_active;
            $v['not']['active'] = $v['not']['active'] ?? !$is_active;
            $button = \x\panel\to\field($v, $k, 'button')['field'];
            $button[1] = \i(...((array) ($v['hint'] ?? $v['title'] ?? $v['value'] ?? $k)));
            $button[2]['name'] = $n;
            $button[2]['type'] = $v['type'] ?? 'button';
            $button[2] = \x\panel\lot\_tag_set($button[2], $v);
            $out['field'][1] .= new \HTML($button);
        }
        unset($value['lot']);
    }
    $out['field'][2]['aria-orientation'] = $out['field'][2]['aria-orientation'] ?? ($is_flex ? 'horizontal' : 'vertical');
    $out['field'][2]['role'] = $out['field'][2]['role'] ?? 'group';
    $value['has']['gap'] = $value['has']['gap'] ?? $has_gap;
    $value['is']['active'] = $value['is']['fix'] = $value['is']['vital'] = false; // Remove class
    $value['is']['flex'] = $value['is']['flex'] ?? $is_flex;
    $value['not']['active'] = $value['not']['fix'] = $value['not']['vital'] = false; // Remove class
    $value['tags']['count:' . $count] = $value['tags']['count:' . $count] ?? true;
    $value['tags']['textarea'] = false;
    $value['with']['options'] = $value['with']['options'] ?? true;
    $out['field'][2] = \x\panel\lot\_tag_set($out['field'][2], $value);
    unset($out['field'][2]['disabled'], $out['field'][2]['id'], $out['field'][2]['name'], $out['field'][2]['readonly']);
    return \x\panel\lot\type\field($out, $key);
}

function color($value, $key) {
    $out = \x\panel\to\field($value, $key, 'input');
    $out['field'][2]['type'] = 'color';
    if ($the_value = \x\panel\to\color((string) ($value['value'] ?? ""))) {
        $out['field'][2]['title'] = $the_value;
        $out['field'][2]['value'] = $the_value;
    }
    $out['field'][2] = \x\panel\lot\_tag_set($out['field'][2], $value);
    return \x\panel\lot\type\field($out, $key);
}

function colors($value, $key) {
    $count = 0;
    $has_gap = !isset($value['gap']) || $value['gap'];
    $is_flex = $value['is']['flex'] ?? $has_gap;
    $name = (string) ($value['name'] ?? $key);
    if (isset($value['values'])) {
        if (isset($value['lot']) && \is_array($value['lot'])) {
            foreach ($value['values'] as $k => $v) {
                if (!isset($value['lot'][$k]) || (\is_array($value['lot'][$k]) && !\array_key_exists('value', $value['lot'][$k]))) {
                    $value['lot'][$k]['value'] = $v;
                }
            }
        } else {
            $value['lot'] = $value['values'];
        }
        unset($value['values']);
    }
    $out = \x\panel\to\field($value, $key);
    $out['field'][0] = 'div';
    if (isset($value['lot'])) {
        // TODO: Sort by `stack`
        foreach ($value['lot'] as $k => $v) {
            if (false === $v || null === $v || !empty($v['skip'])) {
                continue;
            }
            ++$count;
            if (!\is_array($v)) {
                $v = ['value' => \s($v)];
            }
            $n = (string) ($v['name'] ?? "");
            $n = $n ? (0 === \strpos($n, '[') ? $name . $n : $n) : $name . '[' . $k . ']';
            $input = \x\panel\to\field($v, $k, 'input')['field'];
            $input[2]['name'] = $n;
            $input[2]['type'] = 'color';
            if ($the_value = \x\panel\to\color((string) ($v['value'] ?? ""))) {
                $input[2]['title'] = $v['title'] ?? $the_value;
                $input[2]['value'] = $the_value;
            }
            $input[2] = \x\panel\lot\_tag_set($input[2], $v);
            $out['field'][1] .= new \HTML($input);
        }
        unset($value['lot']);
    }
    $out['field'][2]['aria-orientation'] = $out['field'][2]['aria-orientation'] ?? ($is_flex ? 'horizontal' : 'vertical');
    $out['field'][2]['role'] = $out['field'][2]['role'] ?? 'group';
    $value['has']['gap'] = $value['has']['gap'] ?? $is_flex;
    $value['is']['active'] = $value['is']['fix'] = $value['is']['vital'] = false; // Remove class
    $value['is']['flex'] = $is_flex;
    $value['not']['active'] = $value['not']['fix'] = $value['not']['vital'] = false; // Remove class
    $value['tags']['count:' . $count] = $value['tags']['count:' . $count] ?? true;
    $value['tags']['textarea'] = false;
    $value['with']['options'] = $value['with']['options'] ?? true;
    $out['field'][2] = \x\panel\lot\_tag_set($out['field'][2], $value);
    unset($out['field'][2]['disabled'], $out['field'][2]['id'], $out['field'][2]['name'], $out['field'][2]['readonly']);
    return \x\panel\lot\type\field($out, $key);
}

function content($value, $key) {
    $value['hint'] = $value['hint'] ?? 'Content goes here...';
    $out = \x\panel\to\field($value, $key);
    $out['field'][2] = \x\panel\lot\_tag_set($out['field'][2], $value);
    return \x\panel\lot\type\field($out, $key);
}

function date($value, $key) {
    $v = (string) ($value['value'] ?? "");
    $value['hint'] = $value['hint'] ?? ("" !== $v ? $v : \date('Y-m-d'));
    $value['pattern'] = $value['pattern'] ?? "[1-9]\\d{3,}-(0\\d|1[0-2])-(0\\d|[1-2]\\d|3[0-1])";
    return \x\panel\lot\type\field\date_time($value, $key);
}

function date_time($value, $key) {
    $v = (string) ($value['value'] ?? "");
    $value['field'][2]['autocapitalize'] = 'off';
    $value['hint'] = $value['hint'] ?? ("" !== $v ? $v : \date('Y-m-d H:i:s'));
    $value['pattern'] = $value['pattern'] ?? "[1-9]\\d{3,}-(0\\d|1[0-2])-(0\\d|[1-2]\\d|3[0-1])[ ]([0-1]\\d|2[0-4])(:([0-5]\\d|60)){2}";
    if (isset($value['value'])) {
        if (\is_string($value['value']) || \is_numeric($value['value'])) {
            $value['value'] = new \Time($value['value']);
        } else {
            $value['value'] = \date('Y-m-d H:i:s');
        }
        $value['value'] = (string) $value['value'];
    }
    return \x\panel\lot\type\field\text($value, $key);
}

function description($value, $key) {
    $value['hint'] = $value['hint'] ?? 'Description goes here...';
    $value['max'] = $value['max'] ?? 1275; // 255 * 5
    return \x\panel\lot\type\field\content($value, $key);
}

function email($value, $key) {
    $value['field'][2]['autocapitalize'] = 'off';
    $value['hint'] = $value['hint'] ?? (\S . \i('hello') . \S . '@' . \S . ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME']) . \S);
    $value['pattern'] = $value['pattern'] ?? "[a-z\\d]+([_.\\-][a-z\\d]+)*@[a-z\\d]+([_.\\-][a-z\\d]+)*(\\.[a-z]+)";
    return \x\panel\lot\type\field\text($value, $key);
}

function flex($value, $key) {
    $value['can']['flex'] = $value['can']['flex'] = true;
    $value['has']['gap'] = $value['has']['gap'] = true;
    return \x\panel\lot\type\fields($value, $key);
}

function hidden($value, $key) {
    unset($value['decors'], $value['hint'], $value['tags']);
    return \x\panel\lot\type\input\hidden($value, $key);
}

function item($value, $key) {
    if (isset($value['lot'])) {
        $the_value = isset($value['value']) ? \s($value['value']) : null;
        $name = (string) ($value['name'] ?? $key);
        unset($value['name'], $value['hint'], $value['value']);
        $a = [];
        $count = 0;
        $sort = !isset($value['sort']) || $value['sort'];
        $is_active_all = !isset($value['active']) || $value['active'];
        foreach ($value['lot'] as $k => $v) {
            if (false === $v || null === $v || !empty($v['skip'])) {
                continue;
            }
            ++$count;
            if (!\is_array($v)) {
                $v = ['title' => \s($v)];
            } else if (\array_is_list($v) && \is_string($v[0])) {
                $v = ['title' => $v];
            }
            $n = (string) ($v['name'] ?? "");
            $n = $n ? (0 === \strpos($n, '[') ? $name . $n : $n) : $name;
            $is_active = \is_array($v) && \array_key_exists('active', $v) ? (null === $v['active'] || $v['active']) : $is_active_all;
            $is_fix = !empty($v['fix']);
            $v['is']['active'] = $v['is']['active'] ?? $is_active;
            $v['is']['fix'] = $v['is']['fix'] ?? $is_fix;
            $v['not']['active'] = $v['not']['active'] ?? !$is_active;
            $v['not']['fix'] = $v['not']['fix'] ?? !$is_fix;
            $input = \x\panel\to\field($v, $k, 'input')['field'];
            $the_v = isset($v['value']) ? \s($v['value']) : (string) $k;
            $input[2]['checked'] = null !== $the_value && ($the_value === $the_v);
            $input[2]['disabled'] = !$is_active;
            $input[2]['name'] = $n;
            $input[2]['type'] = 'radio';
            $input[2]['value'] = $the_v;
            $input[2] = \x\panel\lot\_tag_set($input[2], $v);
            unset($input[2]['placeholder']);
            $description = \strip_tags(\i(...((array) ($v['description'] ?? ""))) ?? "");
            $title = \x\panel\lot\type\title(\x\panel\lot\_value_set([
                'content' => \i(...((array) ($v['title'] ?? ""))),
                'icon' => $v['icon'] ?? [],
                'level' => -1,
                '2' => ['title' => "" !== $description ? $description : null]
            ], 0), 0);
            $label = [
                0 => 'label',
                1 => (new \HTML($input)) . ' ' . $title,
                2 => \x\panel\lot\_tag_set([], $v)
            ];
            $a[\strip_tags($title ?? "")] = new \HTML($label);
        }
        $sort && \ksort($a);
        if (!isset($value['flex'])) {
            $flex = $count < 7 ? "" : '<br>'; // Auto
        } else {
            $flex = $value['flex'] ? "" : '<br>';
        }
        $out = \x\panel\to\field($value, $key);
        $out['field'][0] = 'div';
        $out['field'][1] = \implode($flex, $a);
        $out['field'][2]['aria-orientation'] = $out['field'][2]['aria-orientation'] ?? ("" === $flex ? 'horizontal' : 'vertical');
        $out['field'][2]['role'] = $out['field'][2]['role'] ?? 'group';
        $value['has']['gap'] = $value['has']['gap'] ?? "" === $flex;
        $value['is']['active'] = $value['is']['fix'] = $value['is']['vital'] = false; // Remove class
        $value['is']['flex'] = $value['is']['flex'] ?? "" === $flex;
        $value['not']['active'] = $value['not']['fix'] = $value['not']['vital'] = false; // Remove class
        $value['tags']['count:' . $count] = $value['tags']['count:' . $count] ?? true;
        $value['tags']['textarea'] = false;
        $value['with']['options'] = $value['with']['options'] ?? true;
        $out['field'][2] = \x\panel\lot\_tag_set($out['field'][2], $value);
        unset($value['lot'], $out['field'][2]['disabled'], $out['field'][2]['id'], $out['field'][2]['name'], $out['field'][2]['readonly']);
        return \x\panel\lot\type\field($out, $key);
    }
    return \x\panel\lot\type\field\text($value, $key);
}

function items($value, $key) {
    if (isset($value['lot'])) {
        $the_values = (array) (!empty($value['values']) ? $value['values'] : ($value['value'] ?? []));
        if ($key_as_value = !empty($value['as']['list'])) {
            $the_values = \P . \implode(\P, \s($the_values)) . \P;
        }
        $name = (string) ($value['name'] ?? $key);
        unset($value['name'], $value['hint'], $value['value'], $value['values']);
        $a = [];
        $count = 0;
        $sort = !isset($value['sort']) || $value['sort'];
        if ($can_sort = $value['can']['sort'] ?? false) {
            $value['tags']['can:sort'] = $value['tags']['can:sort'] ?? $can_sort;
            unset($value['can']['sort']);
        }
        $is_active_all = !isset($value['active']) || $value['active'];
        foreach ($value['lot'] as $k => $v) {
            if (false === $v || null === $v || !empty($v['skip'])) {
                continue;
            }
            ++$count;
            if (!\is_array($v)) {
                $v = ['title' => \s($v)];
            } else if (\array_is_list($v) && \is_string($v[0])) {
                $v = ['title' => $v];
            }
            $n = (string) ($v['name'] ?? "");
            $n = $n ? (0 === \strpos($n, '[') ? $name . $n : $n) : $name . '[' . ($key_as_value ? "" : $k) . ']';
            $is_active = \is_array($v) && \array_key_exists('active', $v) ? (null === $v['active'] || $v['active']) : $is_active_all;
            $is_fix = !empty($v['fix']);
            $v['is']['active'] = $v['is']['active'] ?? $is_active;
            $v['is']['fix'] = $v['is']['fix'] ?? $is_fix;
            $v['not']['active'] = $v['not']['active'] ?? !$is_active;
            $v['not']['fix'] = $v['not']['fix'] ?? !$is_fix;
            $input = \x\panel\to\field($v, $k, 'input')['field'];
            if ($key_as_value) {
                $input[2]['checked'] = false !== \strpos($the_values, \P . $k . \P);
            } else {
                $input[2]['checked'] = isset($v['value']) ? (isset($the_values[$k]) && $v['value'] === $the_values[$k]) : isset($the_values[$k]);
            }
            $input[2]['type'] = 'checkbox';
            $input[2]['name'] = $n;
            $input[2]['value'] = isset($v['value']) ? \s($v['value']) : ($key_as_value ? (string) $k : \s($the_values[$k] ?? true));
            if (!$is_active) {
                $input[2]['disabled'] = true;
            // `else if` because mixing both `disabled` and `readonly` attribute does not make sense
            } else if ($is_fix) {
                $input[2]['readonly'] = true;
            }
            unset($input[2]['placeholder']);
            $input[2] = \x\panel\lot\_tag_set($input[2], $v);
            $description = \strip_tags(\i(...((array) ($v['description'] ?? ""))) ?? "");
            $title = \x\panel\lot\type\title(\x\panel\lot\_value_set([
                'content' => \i(...((array) ($v['title'] ?? ""))),
                'icon' => $v['icon'] ?? [],
                'level' => -1,
                '2' => ['title' => "" !== $description ? $description : null]
            ], 0), 0);
            $label = [
                0 => 'label',
                1 => (new \HTML($input)) . ' ' . $title,
                2 => \x\panel\lot\_tag_set([], $v)
            ];
            $a[\strip_tags($title ?? "")] = new \HTML($label);
        }
        $sort && \ksort($a);
        if (!isset($value['flex'])) {
            $flex = $count < 7 ? "" : '<br>'; // Auto
        } else {
            $flex = $value['flex'] ? "" : '<br>';
        }
        $out = \x\panel\to\field($value, $key);
        $out['field'][0] = 'div';
        $out['field'][1] = \implode($flex, $a);
        $out['field'][2]['aria-orientation'] = $out['field'][2]['aria-orientation'] ?? ("" === $flex ? 'horizontal' : 'vertical');
        $out['field'][2]['role'] = $out['field'][2]['role'] ?? 'group';
        $value['has']['gap'] = $value['has']['gap'] ?? "" === $flex;
        $value['is']['active'] = $value['is']['fix'] = $value['is']['vital'] = false; // Remove class
        $value['is']['flex'] = $value['is']['flex'] ?? "" === $flex;
        $value['not']['active'] = $value['not']['fix'] = $value['not']['vital'] = false; // Remove class
        $value['tags']['count:' . $count] = $value['tags']['count:' . $count] ?? true;
        $value['tags']['textarea'] = false;
        $value['with']['options'] = $value['with']['options'] ?? true;
        $out['field'][2] = \x\panel\lot\_tag_set($out['field'][2], $value);
        unset($value['lot'], $out['field'][2]['disabled'], $out['field'][2]['id'], $out['field'][2]['name'], $out['field'][2]['readonly']);
        return \x\panel\lot\type\field($out, $key);
    }
    return \x\panel\lot\type\field\text($value, $key);
}

function link($value, $key) {
    $value['field'][2]['autocapitalize'] = 'off';
    $value['hint'] = $value['hint'] ?? (\S . 'http://' . \S . ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME']) . \S);
    $value['pattern'] = $value['pattern'] ?? "(data:[^\\s;]+;|(https?:)?\\/\\/)\\S+";
    return \x\panel\lot\type\field\text($value, $key);
}

function name($value, $key) {
    $keep = (string) ($value['keep'] ?? "");
    $v = (string) ($value['value'] ?? "");
    $default = \array_keys(\array_filter((array) \State::get('x.panel.guard.file.x', true)));
    \sort($default);
    if (\is_array($x = $value['x'] ?? \implode('|', $default))) {
        $x = \array_keys(\array_filter($x));
        \sort($x);
        foreach ($x as &$xx) {
            $xx = \x($xx);
        }
        unset($xx);
        $x = \implode('|', $x);
    }
    $x = $x ? "\\.(" . $x . ")" : "";
    $value['field'][2]['autocapitalize'] = 'off';
    $value['hint'] = $value['hint'] ?? ("" !== $v ? $v : 'foo-bar' . ($x ? '.' . (false === \strpos($x, '|') ? \substr($x, 3, -1) : 'baz') : ""));
    $value['max'] = $value['max'] ?? 255; // <https://serverfault.com/a/9548>
    $value['min'] = $value['min'] ?? $x ? 2 : 1;
    $value['pattern'] = $value['pattern'] ?? "([_.]?[a-z\\d" . \x($keep) . "]+([_.\\-][a-z\\d" . \x($keep) . "]+)*)?" . $x;
    return \x\panel\lot\type\field\text($value, $key);
}

function number($value, $key) {
    $value['hint'] = $value['hint'] ?? $value['min'] ?? 0;
    $out = \x\panel\to\field($value, $key, 'input');
    $out['field'][2]['max'] = $value['max'] ?? null;
    $out['field'][2]['min'] = $value['min'] ?? null;
    $out['field'][2]['step'] = $value['step'] ?? null;
    $out['field'][2]['type'] = 'number';
    $out['field'][2] = \x\panel\lot\_tag_set($out['field'][2], $value);
    return \x\panel\lot\type\field($out, $key);
}

function option($value, $key) {
    if (isset($value['lot'])) {
        $out = \x\panel\to\field($value, $key, 'select');
        unset($value['lot']);
        return \x\panel\lot\type\field($out, $key);
    }
    return \x\panel\lot\type\field\text($value, $key);
}

function options($value, $key) {
    // TODO
}

function pass($value, $key) {
    $out = \x\panel\to\field($value, $key, 'input');
    $out['field'][2]['type'] = 'password';
    unset($out['field'][2]['value']); // Never show `value` on this field
    $out['field'][2] = \x\panel\lot\_tag_set($out['field'][2], $value);
    return \x\panel\lot\type\field($out, $key);
}

function path($value, $key) {
    $keep = (string) ($value['keep'] ?? "");
    $v = (string) ($value['value'] ?? "");
    $value['field'][2]['autocapitalize'] = 'off';
    $value['hint'] = $value['hint'] ?? ("" !== $v ? $v : "\\foo\\bar\\baz");
    $value['max'] = $value['max'] ?? (260 - (\strlen(\PATH) + 1)); // <https://docs.microsoft.com/en-us/windows/win32/fileio/maximum-file-path-limitation>
    $value['min'] = 0;
    $value['pattern'] = $value['pattern'] ?? "([\\\\\\/][._]?[a-z\\d" . \x($keep) . "]+([._\\-][a-z\\d" . \x($keep) . "]+)*)+";
    return \x\panel\lot\type\field\text($value, $key);
}

function query($value, $key) {
    $value['hint'] = $value['hint'] ?? 'foo, bar, baz';
    $value['pattern'] = $value['pattern'] ?? "([A-Za-z\\d]+([\\- ][A-Za-z\\d]+)*)(\\s*,\\s*[A-Za-z\\d]+([\\- ][A-Za-z\\d]+)*)*";
    $values = (array) (!empty($value['values']) ? $value['values'] : ($value['value'] ?? []));
    // Key-value pair(s)
    if (\array_keys($values) !== \range(0, \count($values) - 1)) {
        $values = \array_keys($values);
    }
    $value['value'] = \implode(', ', \array_filter($values));
    unset($value['values']);
    $out = \x\panel\to\field($value, $key, 'input');
    $out['field'][2]['type'] = 'text';
    $out['field'][2] = \x\panel\lot\_tag_set($out['field'][2], $value);
    return \x\panel\lot\type\field($out, $key);
}

function range($value, $key) {
    $out = \x\panel\to\field($value, $key, 'input');
    if (isset($value['range'])) {
        // `[$min, $max]`
        if (2 === \count($value['range'])) {
            $value['max'] = $value['range'][1] ?? 1;
            $value['min'] = $value['range'][0] ?? 0;
        // `[$min, $value, $max]`
        } else {
            $value['max'] = $value['range'][2] ?? 1;
            $value['min'] = $value['range'][0] ?? 0;
            $value['value'] = $value['range'][1] ?? 0;
        }
    }
    $out['field'][2]['type'] = 'range';
    $out['field'][2]['min'] = $value['min'] ?? null;
    $out['field'][2]['max'] = $value['max'] ?? null;
    $out['field'][2]['value'] = $value['value'] ?? null;
    $out['field'][2]['step'] = $value['step'] ?? null;
    unset($out['field'][2]['maxlength'], $out['field'][2]['minlength']);
    $out['field'][2] = \x\panel\lot\_tag_set($out['field'][2], $value);
    return \x\panel\lot\type\field($out, $key);
}

function route($value, $key) {
    $keep = (string) ($value['keep'] ?? "");
    $v = (string) ($value['value'] ?? "");
    $value['field'][2]['autocapitalize'] = 'off';
    $value['hint'] = $value['hint'] ?? ("" !== $v ? $v : '/foo/bar/baz');
    $value['pattern'] = $value['pattern'] ?? "(\\/[._]?[a-z\\d" . \x($keep) . "]+([._\\-][a-z\\d" . \x($keep) . "]+)*)+";
    return \x\panel\lot\type\field\text($value, $key);
}

function set($value, $key) {
    $content = (string) ($value['content'] ?? "");
    $description = (string) \x\panel\to\description($value['description'] ?? "");
    $title = (string) \x\panel\to\title($value['title'] ?? "", -2);
    $value[0] = $value[0] ?? 'fieldset';
    $value[1] = $value[1] ?? ("" !== $title ? '<legend>' . $title . '</legend>' : "") . $description . $content;
    $value[2] = $value[2] ?? [];
    unset($value['description'], $value['title'], $value['type']);
    return \x\panel\lot\type($value, $key);
}

function source($value, $key) {
    $value['hint'] = $value['hint'] ?? 'Content goes here...';
    $value['state'] = \array_replace(['tab' => 4], $value['state'] ?? []);
    $out = \x\panel\to\field($value, $key);
    $value['tags']['code'] = $value['tags']['code'] ?? true;
    $out['field'][2] = \x\panel\lot\_tag_set($out['field'][2], $value);
    return \x\panel\lot\type\field($out, $key);
}

function text($value, $key) {
    $out = \x\panel\to\field($value, $key, 'input');
    $out['field'][2]['type'] = 'text';
    $out['field'][2] = \x\panel\lot\_tag_set($out['field'][2], $value);
    return \x\panel\lot\type\field($out, $key);
}

function time($value, $key) {
    $v = (string) ($value['value'] ?? "");
    $value['hint'] = $value['hint'] ?? ("" !== $v ? $v : \date('H:i:s'));
    $value['pattern'] = $value['pattern'] ?? "([0-1]\\d|2[0-4])(:([0-5]\\d|60)){1,2}";
    return \x\panel\lot\type\field\date_time($value, $key);
}

function title($value, $key) {
    $v = (string) ($value['value'] ?? "");
    $value['field'][2]['autocapitalize'] = 'words';
    $value['hint'] = $value['hint'] ?? ("" !== $v ? $v : 'Title Goes Here');
    $value['max'] = $value['max'] ?? 255;
    return \x\panel\lot\type\field\text($value, $key);
}

function u_r_l($value, $key) { // This is not a typo!
    $value['field'][2]['autocapitalize'] = 'off';
    $value['hint'] = $value['hint'] ?? (\S . 'http://' . \S . ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME']) . \S);
    $value['pattern'] = $value['pattern'] ?? "(data:[^\\s;]+;|(https?:)?\\/\\/|[.]{0,2}\\/)[^\\/]\\S*";
    return \x\panel\lot\type\field\text($value, $key);
}

function url($value, $key) {
    return \x\panel\lot\type\u_r_l($value, $key);
}

function version($value, $key) {
    $v = (string) ($value['value'] ?? "");
    $value['field'][2]['autocapitalize'] = 'off';
    $value['hint'] = $value['hint'] ?? ("" !== $v ? $v : '1.0.0');
    $value['max'] = $value['max'] ?? 255;
    $value['min'] = 1;
    $value['pattern'] = $value['pattern'] ?? "(0|[1-9]\\d*)\\.(0|[1-9]\\d*)\\.(0|[1-9]\\d*)(?:-((?:0|[1-9]\\d*|\\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\\.(?:0|[1-9]\\d*|\\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\\+([0-9a-zA-Z-]+(?:\\.[0-9a-zA-Z-]+)*))?"; // <https://semver.org#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string>
    return \x\panel\lot\type\field\text($value, $key);
}