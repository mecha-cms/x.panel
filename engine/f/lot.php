<?php namespace x\panel\lot;

function _decor_set(array $attr, array $value = []) {
    $decors = (array) ($value['decors'] ?? []);
    if (!empty($attr['style']) && \is_string($attr['style'])) {
        $key = $value = "";
        foreach (\preg_split('/(\/\*[\s\S]*?\*\/|"(?:[^"\\\]|\\\.)*"|\'(?:[^\'\\\]|\\\.)*\'|;)/', $attr['style'], -1, \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_NO_EMPTY) as $v) {
            if (';' === $v || '/*' === \substr($v, 0, 2) && '*/' === \substr($v, -2)) {
                continue;
            }
            $v = \trim($v);
            if (\strpos($v, ':') > 0) {
                [$key, $value] = \preg_split('/\s*:\s*/', $v);
                if (!\array_key_exists($key, $decors)) {
                    $decors[$key] = $value;
                }
            } else if ($key && isset($decors[$key])) {
                $decors[$key] .= $v;
            }
        }
    }
    $out = "";
    foreach ($decors as $k => $v) {
        if (false === $v || null === $v) {
            continue;
        }
        $out .= $k . ': ' . (\is_int($v) ? $v . 'px' : $v) . '; ';
    }
    $out = \trim($out);
    $attr['style'] = "" !== $out ? $out : null;
    return $attr;
}

function _key_set($key) {
    // Convert to scalar so it can be used as a valid array key
    if (\is_object($key)) {
        return \spl_object_id($key);
    }
    return null === $key || \is_scalar($key) ? $key : \md5(\json_encode($key));
}

function _tag_set(array $attr, array $value = []) {
    $tags = (array) ($value['tags'] ?? []);
    if (\array_is_list($tags)) {
        // Convert `[0, 1, 2]` to `{0: true, 1: true, 2: true}`
        $tags = \array_fill_keys($tags, true);
    }
    foreach (['are', 'as', 'can', 'has', 'is', 'not', 'of', 'with'] as $v) {
        if (!empty($value[$v])) {
            foreach ($value[$v] as $kk => $vv) {
                $tags[$v . ':' . $kk] = $vv;
            }
        }
    }
    if (!empty($attr['class']) && \is_string($attr['class'])) {
        foreach (\preg_split('/\s+/', $attr['class']) as $v) {
            if (!\array_key_exists($v, $tags)) {
                $tags[$v] = true;
            }
        }
    }
    $tags = \array_keys(\array_filter($tags));
    \sort($tags);
    $attr['class'] = $tags ? \implode(' ', $tags) : null;
    return $attr;
}

function _type_parent_set($value, $parent) {
    foreach ($value as &$v) {
        $type = $v['type'] ?? "";
        if ($type !== $parent && 0 !== \strpos($type, $parent . '/')) {
            // Add parent to `type`
            $type = $parent . '/' . $type;
        }
        $v['type'] = \trim($type, '/');
    }
    unset($v);
    return $value;
}

function _value_set(array $value, $key = null) {
    return \array_replace_recursive([
        '0' => null,
        '1' => null,
        '2' => [],
        'active' => null,
        'are' => [],
        'as' => [],
        'can' => [],
        'content' => null,
        'count' => null,
        'current' => null,
        'decors' => [],
        'description' => null,
        'has' => [],
        'hint' => null,
        'icon' => null,
        'id' => null,
        'image' => null,
        'is' => [],
        'key' => \x\panel\lot\_key_set($key),
        'keys' => [],
        'link' => null,
        'lot' => [],
        'name' => null,
        'not' => [],
        'of' => [],
        'size' => null,
        'skip' => null,
        'stack' => 10,
        'tags' => [],
        'title' => null,
        'type' => null,
        'url' => null,
        'value' => null,
        'values' => [],
        'with' => []
    ], $value);
}

function type($value, $key) {
    if (\is_string($value) || (\is_object($value) && $value instanceof \XML)) {
        return $value;
    }
    if (false === $value || null === $value || !empty($value['skip'])) {
        return "";
    }
    $value = \x\panel\lot\_value_set($value, $key);
    if (isset($value[1]) && "" !== $value[1]) {
        return new \HTML($value);
    }
    $out = "";
    if ($type = \strtolower(\f2p(\strtr($value['type'] ?? "", '-', '_')) ?? "")) {
        $type_exist = false;
        foreach (\array_values(\step($type, "\\")) as $v) {
            if ("" !== $v && \function_exists($task = __NAMESPACE__ . "\\type\\" . $v)) {
                $type_exist = true;
                if ($v = \call_user_func($task, $value, $key)) {
                    if (\is_array($v)) {
                        \x\panel\_abort($value, $key, $task);
                    } else {
                        $out .= $v;
                    }
                }
                break;
            }
        }
        if (!$type_exist) {
            \x\panel\_abort($value, $key, $task);
        }
    } else {
        // Automatically form an interface without `type` property based on the presence of `content` or `lot` property
        if (isset($value['content'])) {
            if ($v = \x\panel\lot\type\content($value, $key)) {
                $out .= $v;
            }
        } else if (isset($value['lot'])) {
            if ($v = \x\panel\lot\type\lot($value, $key)) {
                $out .= $v;
            }
        } else {
            if (\defined("\\TEST") && \TEST) {
                $out .= \htmlspecialchars(\json_encode($value, \JSON_PRETTY_PRINT));
            }
            // Skip!
        }
    }
    return $out;
}

require __DIR__ . \D . 'lot' . \D . 'type.php';