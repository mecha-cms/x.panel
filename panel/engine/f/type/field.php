<?php namespace x\panel\type\field;

function _($value, $key) {
    $out = \x\panel\type\field\content($value, $key);
    $out['skip'] = true;
    return $out;
}

function blob($value, $key) {
    $out = \x\panel\to\field($value, $key, 'input');
    $out['field'][2]['type'] = 'file';
    \x\panel\_set_class($out['field'][2], $value['tags'] ?? []);
    return \x\panel\type\field($out, $key);
}

function blobs($value, $key) {
    $out = \x\panel\to\field($value, $key, 'input');
    $out['field'][2]['multiple'] = true;
    $out['field'][2]['name'] = ($value['name'] ?? $key) . '[]';
    $out['field'][2]['type'] = 'file';
    \x\panel\_set_class($out['field'][2], $value['tags'] ?? []);
    return \x\panel\type\field($out, $key);
}

function button($value, $key) {
    $out = \x\panel\to\field($value, $key, 'button');
    $out['field'][1] = \i(...((array) ($value['hint'] ?? $value['title'] ?? $value['value'] ?? $key)));
    $out['field'][2]['type'] = 'button';
    \x\panel\_set_class($out['field'][2], $value['tags'] ?? []);
    return \x\panel\type\field($out, $key);
}

function buttons($value, $key) {
    $out = \x\panel\to\field($value, $key);
    $out['field'][0] = 'div';
    $name = $value['name'] ?? $key;
    if (isset($value['lot'])) {
        if (!isset($value['sort']) || $value['sort']) {
            \sort($value['lot']);
        }
        $count = 0;
        foreach ($value['lot'] as $k => $v) {
            if (null === $v || false === $v || !empty($v['skip'])) {
                continue;
            }
            ++$count;
            if (!\is_array($v)) {
                $v = ['value' => $v];
            }
            $is_active = !isset($v['active']) || $v['active'];
            $n = $name . '[' . $k . ']';
            $button = \x\panel\to\field($v, $k, 'button')['field'];
            $button[1] = \i(...((array) ($v['hint'] ?? $v['title'] ?? $v['value'] ?? $k)));
            $button[2]['name'] = $n;
            $button[2]['type'] = 'button';
            \x\panel\_set_class($button[2], \array_replace([
                'is:active' => $is_active,
                'not:active' => !$is_active
            ], $v['tags'] ?? []));
            $out['field'][1] .= new \HTML($button);
        }
        unset($value['lot']);
    }
    \x\panel\_set_class($out['field'][2], \array_replace([
        'count:' . $count => true,
        'options' => true,
        'textarea' => false
    ], $value['tags'] ?? []));
    unset($out['field'][2]['name']);
    return \x\panel\type\field($out, $key);
}

function color($value, $key) {
    $out = \x\panel\to\field($value, $key, 'input');
    $out['field'][2]['type'] = 'color';
    \x\panel\_set_class($out['field'][2], $value['tags'] ?? []);
    if ($the_value = \x\panel\to\color((string) ($value['value'] ?? ""))) {
        $out['field'][2]['title'] = $the_value;
        $out['field'][2]['value'] = $the_value;
    }
    return \x\panel\type\field($out, $key);
}

function colors($value, $key) {
    $out = \x\panel\to\field($value, $key);
    $out['field'][0] = 'div';
    $name = $value['name'] ?? $key;
    if (isset($value['lot'])) {
        if (!isset($value['sort']) || $value['sort']) {
            \sort($value['lot']);
        }
        $count = 0;
        foreach ($value['lot'] as $k => $v) {
            if (null === $v || false === $v || !empty($v['skip'])) {
                continue;
            }
            ++$count;
            if (\is_string($v)) {
                $v = ['value' => $v];
            }
            $n = $name . '[' . $k . ']';
            $input = \x\panel\to\field($v, $k, 'input')['field'];
            \x\panel\_set_class($input[2], $v['tags'] ?? []);
            $input[2]['name'] = $n;
            $input[2]['type'] = 'color';
            if ($the_value = \x\panel\to\color((string) ($v['value'] ?? ""))) {
                $input[2]['title'] = $the_value;
                $input[2]['value'] = $the_value;
            }
            $out['field'][1] .= new \HTML($input);
        }
        unset($value['lot']);
    }
    \x\panel\_set_class($out['field'][2], \array_replace([
        'count:' . $count => true,
        'options' => true,
        'textarea' => false
    ], $value['tags'] ?? []));
    unset($out['field'][2]['name']);
    return \x\panel\type\field($out, $key);
}

function content($value, $key) {
    if (!\array_key_exists('hint', $value)) {
        $value['hint'] = 'Content goes here...';
    }
    $out = \x\panel\to\field($value, $key);
    \x\panel\_set_class($out['field'][2], $value['tags'] ?? []);
    return \x\panel\type\field($out, $key);
}

function date($value, $key) {
    $v = (string) ($value['value'] ?? "");
    if (!\array_key_exists('hint', $value)) {
        $value['hint'] = "" !== $v ? $v : \date('Y-m-d');
    }
    if (!isset($value['pattern'])) {
        $value['pattern'] = "^[1-9]\\d{3,}-(0\\d|1[0-2])-(0\\d|[1-2]\\d|3[0-1])$";
    }
    return \x\panel\type\field\date_time($value, $key);
}

function date_time($value, $key) {
    $v = (string) ($value['value'] ?? "");
    if (!\array_key_exists('hint', $value)) {
        $value['hint'] = "" !== $v ? $v : \date('Y-m-d H:i:s');
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
    return \x\panel\type\field\text($value, $key);
}

function description($value, $key) {
    if (!\array_key_exists('hint', $value)) {
        $value['hint'] = 'Description goes here...';
    }
    if (!isset($value['max'])) {
        $value['max'] = 400;
    }
    return \x\panel\type\field\content($value, $key);
}

function email($value, $key) {
    if (!\array_key_exists('hint', $value)) {
        $value['hint'] = \S . \i('hello') . \S . '@' . \S . $GLOBALS['url']->host . \S;
    }
    if (!isset($value['pattern'])) {
        $value['pattern'] = "^[a-z\\d]+([_.-][a-z\\d]+)*@[a-z\\d]+([_.-][a-z\\d]+)*(\\.[a-z]+)$";
    }
    return \x\panel\type\field\text($value, $key);
}

function flex($value, $key) {
    $value['tags']['is:flex'] = true;
    return \x\panel\type\fields($value, $key);
}

function hidden($value, $key) {
    if (!\array_key_exists('id', $value)) {
        $value['id'] = 'f:' . \dechex(\crc32($key));
    }
    return \x\panel\type\input\hidden($value, $key);
}

function item($value, $key) {
    if (isset($value['lot'])) {
        $the_value = $value['value'] ?? null;
        $n = $value['name'] ?? $key;
        unset($value['name'], $value['hint'], $value['value']);
        $a = [];
        $out = \x\panel\to\field($value, $key);
        $out['field'][0] = 'div';
        $count = 0;
        $sort = !isset($value['sort']) || $value['sort'];
        foreach ($value['lot'] as $k => $v) {
            if (null === $v || false === $v || !empty($v['skip'])) {
                continue;
            }
            ++$count;
            if (!\is_array($v)) {
                $v = ['title' => $v];
            }
            $is_active = !isset($v['active']) || $v['active'];
            $input = \x\panel\to\field($v, $k, 'input')['field'];
            $input[2]['checked'] = null !== $the_value && ((string) $the_value === (string) $k);
            $input[2]['type'] = 'radio';
            $t = \x\panel\to\title($v['title'] ?? "", -2);
            $d = $v['description'] ?? "";
            $input[2]['disabled'] = !$is_active;
            $input[2]['name'] = $v['name'] ?? $n;
            $input[2]['value'] = $v['value'] ?? $k;
            \x\panel\_set_class($input[2], [
                'is:active' => $is_active,
                'not:active' => !$is_active
            ]);
            $d = \strip_tags(\i(...((array) $d)));
            $a[$t . $k] = '<label class="' . ($is_active ? 'is' : 'not') . ':active">' . (new \HTML($input)) . ' <span' . ("" !== $d ? ' title="' . $d . '"' : "") . '>' . $t . '</span></label>';
        }
        $sort && \ksort($a);
        if (!isset($value['block'])) {
            $block = $count > 6 ? '<br>' : ""; // Auto
        } else {
            $block = $value['block'] ? '<br>' : "";
        }
        $out['field'][1] = \implode($block, $a);
        \x\panel\_set_class($out['field'][2], [
            'count:' . $count => true,
            'is:block' => !!$block,
            'options' => true,
            'textarea' => false
        ]);
        unset($value['lot'], $out['field'][2]['name']);
        return \x\panel\type\field($out, $key);
    }
    return \x\panel\type\field\text($value, $key);
}

function items($value, $key) {
    if (isset($value['lot'])) {
        $the_value = (array) ($value['value'] ?? []);
        if ($key_as_value = !empty($value['flat'])) {
            $the_value = \P . \implode(\P, $the_value) . \P;
        }
        $n = $value['name'] ?? $key;
        unset($value['name'], $value['hint'], $value['value']);
        $out = \x\panel\to\field($value, $key);
        $out['field'][0] = 'div';
        $a = [];
        $count = 0;
        $sort = !isset($value['sort']) || $value['sort'];
        foreach ($value['lot'] as $k => $v) {
            if (null === $v || false === $v || !empty($v['skip'])) {
                continue;
            }
            ++$count;
            if (!\is_array($v)) {
                $v = ['title' => $v];
            }
            $input = \x\panel\to\field($v, $k, 'input')['field'];
            $input[2]['checked'] = $key_as_value ? false !== \strpos($the_value, \P . $k . \P) : isset($the_value[$k]);
            $input[2]['type'] = 'checkbox';
            $t = \x\panel\to\title($v['title'] ?? "", -2);
            $d = $v['description'] ?? "";
            $input[2]['name'] = $v['name'] ?? $n . '[' . ($key_as_value ? "" : $k) . ']';
            $input[2]['value'] = $v['value'] ?? ($key_as_value ? $k : \s($the_value[$k] ?? true));
            $d = \strip_tags(\i(...((array) $d)));
            $class = [];
            $class[] = (!isset($v['active']) || $v['active'] ? 'is' : 'not') . ':active';
            $class[] = (!empty($v['locked']) ? 'is' : 'not') . ':locked';
            if (isset($v['active']) && !$v['active']) {
                $input[2]['disabled'] = true;
            // `else if` because mixing both `disabled` and `readonly` attribute does not make sense
            } else if (!empty($v['locked'])) {
                $input[2]['readonly'] = true;
            }
            \sort($class);
            $a[$t . $k] = '<label' . ($class ? ' class="' . \implode(' ', $class) . '"' : "") . '>' . (new \HTML($input)) . ' <span' . ("" !== $d ? ' title="' . $d . '"' : "") . '>' . $t . '</span></label>';
        }
        $sort && \ksort($a);
        if (!isset($value['block'])) {
            $block = $count > 6 ? '<br>' : ""; // Auto
        } else {
            $block = $value['block'] ? '<br>' : "";
        }
        $out['field'][1] = \implode($block, $a);
        \x\panel\_set_class($out['field'][2], [
            'count:' . $count => true,
            'is:block' => !!$block,
            'options' => true,
            'textarea' => false
        ]);
        unset($value['lot'], $out['field'][2]['name']);
        return \x\panel\type\field($out, $key);
    }
    return \x\panel\type\field\text($value, $key);
}

function link($value, $key) {
    if (!\array_key_exists('hint', $value)) {
        $url = $GLOBALS['url'];
        $value['hint'] = \S . $url->protocol . \S . $url->host . \S;
    }
    if (!isset($value['pattern'])) {
        $value['pattern'] = "^(data:[^\\s;]+;\\S+|(https?:)\\/\\/\\S+)$";
    }
    return \x\panel\type\field\text($value, $key);
}

function name($value, $key) {
    $v = (string) ($value['value'] ?? "");
    $x = $value['x'] ?? \implode('|', \array_keys(\array_filter(\File::$state['x'])));
    if (\is_array($x)) {
        $x = \implode('|', \array_keys(\array_filter($x)));
    }
    $x = $x ? "\\.(" . $x . ")" : "";
    if (!isset($value['pattern'])) {
        $value['pattern'] = "^([_.]?[a-z\\d]+([_.-][a-z\\d]+)*)?" . $x . "$";
    }
    if (!\array_key_exists('hint', $value)) {
        $value['hint'] = "" !== $v ? $v : 'foo-bar' . ($x ? '.baz' : "");
    }
    if (!isset($value['max'])) {
        // <https://serverfault.com/a/9548>
        $value['max'] = 255;
    }
    if (!isset($value['min'])) {
        $value['min'] = $x ? 2 : 1;
    }
    return \x\panel\type\field\text($value, $key);
}

function number($value, $key) {
    if (!\array_key_exists('hint', $value)) {
        $value['hint'] = $value['min'] ?? 0;
    }
    $out = \x\panel\to\field($value, $key, 'input');
    $out['field'][2]['type'] = 'number';
    $out['field'][2]['min'] = $value['min'] ?? null;
    $out['field'][2]['max'] = $value['max'] ?? null;
    $out['field'][2]['step'] = $value['step'] ?? null;
    \x\panel\_set_class($out['field'][2], $value['tags'] ?? []);
    return \x\panel\type\field($out, $key);
}

function option($value, $key) {
    if (isset($value['lot'])) {
        $out = \x\panel\to\field($value, $key, 'select');
        unset($value['lot']);
        return \x\panel\type\field($out, $key);
    }
    return \x\panel\type\field\text($value, $key);
}

function options($value, $key) {
    // TODO
}

function pass($value, $key) {
    $out = \x\panel\to\field($value, $key, 'input');
    $out['field'][2]['type'] = 'password';
    unset($out['field'][2]['value']); // Never show `value` on this field
    \x\panel\_set_class($out['field'][2], $value['tags'] ?? []);
    return \x\panel\type\field($out, $key);
}

function path($value, $key) {
    $v = (string) ($value['value'] ?? "");
    if (!isset($value['pattern'])) {
        $value['pattern'] = "^[_.]?[a-z\\d]+([_.-][a-z\\d]+)*([\\\\/][_.]?[a-z\\d]+([_.-][a-z\\d]+)*)*$";
    }
    if (!\array_key_exists('hint', $value)) {
        $value['hint'] = "" !== $v ? $v : "foo\\bar\\baz";
    }
    if (!isset($value['max'])) {
        // <https://docs.microsoft.com/en-us/windows/win32/fileio/maximum-file-path-limitation>
        $value['max'] = 260 - (\strlen(\ROOT) + 1);
    }
    if (!isset($value['min'])) {
        $value['min'] = 0;
    }
    return \x\panel\type\field\text($value, $key);
}

function query($value, $key) {
    if (!\array_key_exists('hint', $value)) {
        $value['hint'] = 'foo, bar, baz';
    }
    if (!isset($value['pattern'])) {
        $value['pattern'] = "^([A-Za-z\\d]+([- ][A-Za-z\\d]+)*)(\\s*,\\s*[A-Za-z\\d]+([- ][A-Za-z\\d]+)*)*$";
    }
    if (isset($value['value']) && \is_array($value['value'])) {
        $value['value'] = \implode(', ', $value['value']);
    }
    $out = \x\panel\to\field($value, $key, 'input');
    $out['field'][2]['type'] = 'text';
    \x\panel\_set_class($out['field'][2], $value['tags'] ?? []);
    return \x\panel\type\field($out, $key);
}

function range($value, $key) {
    $out = \x\panel\to\field($value, $key, 'input');
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
    $out['field'][2]['type'] = 'range';
    $out['field'][2]['min'] = $value['min'] ?? null;
    $out['field'][2]['max'] = $value['max'] ?? null;
    $out['field'][2]['value'] = $value['value'] ?? null;
    $out['field'][2]['step'] = $value['step'] ?? null;
    unset($out['field'][2]['maxlength'], $out['field'][2]['minlength']);
    \x\panel\_set_class($out['field'][2], $value['tags'] ?? []);
    return \x\panel\type\field($out, $key);
}

function set($value, $key) {
    $title = \x\panel\to\title($value['title'] ?? "", -2);
    $description = \x\panel\to\description($value['description'] ?? "");
    $value = \array_replace([
        0 => 'fieldset',
        1 => ("" !== $title ? '<legend>' . $title . '</legend>' : "") . $description,
        2 => []
    ], $value);
    unset($value['description'], $value['title'], $value['type']);
    return \x\panel\type($value, $key);
}

function source($value, $key) {
    $value['state'] = \array_replace(['tab' => '  '], $value['state'] ?? []);
    if (!\array_key_exists('hint', $value)) {
        $value['hint'] = 'Content goes here...';
    }
    $out = \x\panel\to\field($value, $key);
    \x\panel\_set_class($out['field'][2], \array_replace([
        'code' => true
    ], $value['tags'] ?? []));
    return \x\panel\type\field($out, $key);
}

function text($value, $key) {
    $out = \x\panel\to\field($value, $key, 'input');
    $out['field'][2]['type'] = 'text';
    \x\panel\_set_class($out['field'][2], $value['tags'] ?? []);
    return \x\panel\type\field($out, $key);
}

function time($value, $key) {
    $v = (string) ($value['value'] ?? "");
    if (!\array_key_exists('hint', $value)) {
        $value['hint'] = "" !== $v ? $v : \date('H:i:s');
    }
    if (!isset($value['pattern'])) {
        $value['pattern'] = "^([0-1]\\d|2[0-4])(:([0-5]\\d|60)){1,2}$";
    }
    $out = \x\panel\type\field\date_time($value, $key);
    return $out;
}

function title($value, $key) {
    $v = (string) ($value['value'] ?? "");
    if (!\array_key_exists('hint', $value)) {
        $value['hint'] = "" !== $v ? $v : 'Title Goes Here';
    }
    if (!isset($value['max'])) {
        $value['max'] = 200;
    }
    return \x\panel\type\field\text($value, $key);
}

function toggle($value, $key) {
    $out = \x\panel\to\field($value, $key, 'input');
    $the_value = $value['value'] ?? null;
    $input = \x\panel\to\field($value, $key, 'input')['field'];
    $input[2]['checked'] = !empty($the_value);
    $input[2]['type'] = 'checkbox';
    $input[2]['value'] = 'true'; // Force value to be `true`
    $t = \i(...((array) ($value['hint'] ?? $value['title'] ?? \S)));
    $out['field'][0] = 'div';
    $out['field'][1] = '<label>' . (new \HTML($input)) . ' <span>' . $t . '</span></label>';
    \x\panel\_set_class($out['field'][2], [
        'input' => false,
        'options' => true
    ]);
    unset($out['hint'], $out['field'][2]['name'], $out['field'][2]['placeholder']);
    return \x\panel\type\field($out, $key);
}

function u_r_l($value, $key) { // This is not a typo!
    if (!\array_key_exists('hint', $value)) {
        $url = $GLOBALS['url'];
        $value['hint'] = \S . $url->protocol . \S . $url->host . \S;
    }
    if (!isset($value['pattern'])) {
        $value['pattern'] = "^(data:[^\\s;]+;\\S+|(https?:)?\\/\\/\\S+)$";
    }
    return \x\panel\type\field\text($value, $key);
}
