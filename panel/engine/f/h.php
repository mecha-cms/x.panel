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
    $i = [
        0 => 'textarea',
        1 => \htmlspecialchars($in['value'] ?? ""),
        2 => [
            'class' => "",
            'disabled' => isset($in['active']) && !$in['active'],
            'id' => $in['id'],
            'name' => $in['name'] ?? $key,
            'pattern' => $in['pattern'] ?? null,
            'placeholder' => $in['placeholder'] ?? null,
            'readonly' => !empty($in['read-only']),
            'required' => !empty($in['required'])
        ]
    ];
    $style = "";
    if (isset($in['height']) && $in['height'] !== false) {
        if ($in['height'] === true) {
            $i[2]['class'] .= ' height';
        } else {
            $style .= 'height:' . (\is_numeric($in['height']) ? $in['height'] . 'px' : $in['height']) . ';';
        }
    }
    if (isset($in['width']) && $in['width'] !== false) {
        if ($in['width'] === true) {
            $i[2]['class'] .= ' width';
        } else {
            $style .= 'width:' . (\is_numeric($in['width']) ? $in['width'] . 'px' : $in['width']) . ';';
        }
    }
    $i[2]['class'] = isset($i[2]['class']) && $i[2]['class'] !== "" ? \trim($i[2]['class']) : null;
    $i[2]['style'] = $style !== "" ? $style : null;
    $in['content'] = $i;
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
    return url($value, $in);
}

function url($value) {
    return \is_string($value) ? \URL::long($value, false) : null;
}