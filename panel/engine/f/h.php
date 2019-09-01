<?php namespace _\lot\x\panel\h;

function c($in) {
    $a = \implode(' ', (array) ($in[2]['class'] ?? []));
    $b = \implode(' ', (array) ($in['tags'] ?? []));
    $c = \array_unique(\array_filter(\array_merge(\explode(' ', $a), \explode(' ', $b))));
    \sort($c);
    $c = \implode(' ', $c);
    $in[2]['class'] = $c !== "" ? $c : null;
    return $in[2];
}

function field($in, $key) {
    $in['id'] = $in['id'] ?? 'f:' . \dechex(\crc32($key));
    $name = $in['name'] ?? $key;
    $input = [
        0 => 'textarea',
        1 => \htmlspecialchars($in['value'] ?? ""),
        2 => [
            'class' => "",
            'disabled' => isset($in['active']) && !$in['active'],
            'id' => $in['id'],
            'name' => $name,
            'pattern' => $in['pattern'] ?? null,
            'placeholder' => $in['placeholder'] ?? null,
            'readonly' => !empty($in['read-only']),
            'required' => !empty($in['required'])
        ]
    ];
    $style = "";
    if (isset($in['height']) && $in['height'] !== false) {
        if ($in['height'] === true) {
            $input[2]['class'] .= ' height';
        } else {
            $style .= 'height:' . (\is_numeric($in['height']) ? $in['height'] . 'px' : $in['height']) . ';';
        }
    }
    if (isset($in['width']) && $in['width'] !== false) {
        if ($in['width'] === true) {
            $input[2]['class'] .= ' width';
        } else {
            $style .= 'width:' . (\is_numeric($in['width']) ? $in['width'] . 'px' : $in['width']) . ';';
        }
    }
    $input[2]['class'] = isset($input[2]['class']) && $input[2]['class'] !== "" ? \trim($input[2]['class']) : null;
    $input[2]['style'] = $style !== "" ? $style : null;
    $in['content'] = $input;
    return $in;
}

function icon($in) {
    $icon = \array_replace([null, null], (array) $in);
    if ($icon[0] && strpos($icon[0], '<') === false) {
        $GLOBALS['SVG'][$id = \dechex(\crc32($icon[0]))] = $icon[0];
        $icon[0] = '<svg><use href="#i:' . $id . '"></use></svg>';
    }
    if ($icon[1] && strpos($icon[1], '<') === false) {
        $GLOBALS['SVG'][$id = \dechex(\crc32($icon[1]))] = $icon[1];
        $icon[1] = '<svg><use href="#i:' . $id . '"></use></svg>';
    }
    return $icon;
}

function link($value) {
    return url($value);
}

function session($name, $in) {
    $out = [
        'file' => (array) ($in['file'] ?? []),
        'pattern' => $in['pattern'] ?? null,
        'required' => $in['required'] ?? null,
        'read-only' => $in['read-only'] ?? null,
        'task' => $in['task'] ?? null
    ];
    // Store setting to be used by security
    $_SESSION['panel']['field'][$name] = \array_filter($out);
}

function url($value) {
    return \is_string($value) ? \URL::long($value, false) : null;
}